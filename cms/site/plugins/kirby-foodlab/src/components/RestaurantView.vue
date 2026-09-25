<template>
    <k-panel-inside class="k-restaurant-view">
        <k-header>
            Restaurant

            <template #buttons>
                <k-form-controls
                    :has-diff="hasDiff"
                    :is-processing="isProcessing"
                    :modified="modified"
                    @discard="onDiscard"
                    @submit="onSubmit"
                />
            </template>
        </k-header>

        <k-form
            :fields="fields"
            :validate="true"
            :value="values"
            @input="onInput"
            @submit="onSubmit"
        />
    </k-panel-inside>
</template>

<script>
/**
 * Restaurant content form.
 *
 * It behaves like a model view — the form autosaves into an unsaved version,
 * `k-form-controls` shows the orange discard/save buttons as soon as there is
 * a difference, cmd+s saves and leaving with unsaved changes warns — but it
 * cannot use `$panel.content` for it: that state posts to `<api>/changes/…`,
 * a path Kirby claims with a catch-all core api route that resolves the model
 * from the url. So the same flow runs against the plugin's own endpoints,
 * `<endpoint>/content/{save,publish,discard}` (see routes/index.php).
 */
export default {
    props: {
        endpoint: String,
        fields: Object,
        latest: {
            type: Object,
            default: () => ({}),
        },
        modified: String,
        changes: {
            type: Object,
            default: () => ({}),
        },
    },
    data() {
        return {
            values: this.$helper.object.clone(this.changes),
            isProcessing: false,
            isSaved: true,
            saveTimer: null,
            saveAbortController: null,
        };
    },
    computed: {
        /**
         * Same comparison `$panel.content.diff()` makes: a field counts as
         * changed when its form value differs from the published one
         */
        hasDiff() {
            const keys = new Set([
                ...Object.keys(this.values),
                ...Object.keys(this.latest),
            ]);

            for (const key of keys) {
                if (
                    JSON.stringify(this.values[key]) !==
                    JSON.stringify(this.latest[key])
                ) {
                    return true;
                }
            }

            return false;
        },
    },
    watch: {
        changes(changes) {
            this.values = this.$helper.object.clone(changes);
            this.isSaved = true;
        },
    },
    mounted() {
        this.$events.on("beforeunload", this.onBeforeUnload);
        this.$events.on("view.save", this.onViewSave);
    },
    destroyed() {
        this.cancelSaving();
        this.$events.off("beforeunload", this.onBeforeUnload);
        this.$events.off("view.save", this.onViewSave);
    },
    methods: {
        /**
         * Same as `$panel.content.cancelSaving()`: a save scheduled or
         * running while the user publishes or discards would otherwise land
         * afterwards and recreate the unsaved version just removed
         */
        cancelSaving() {
            clearTimeout(this.saveTimer);
            this.saveTimer = null;
            this.saveAbortController?.abort();
            this.saveAbortController = null;
        },
        onBeforeUnload(event) {
            if (this.isSaved === false || this.isProcessing === true) {
                event.preventDefault();
                event.returnValue = "";
            }
        },
        async onDiscard() {
            if (this.isProcessing === true) {
                return;
            }

            this.cancelSaving();
            this.isProcessing = true;

            try {
                await this.$api.post(this.endpoint + "/content/discard");
                await this.$panel.view.refresh();
            } catch (error) {
                this.$panel.error(error);
            } finally {
                this.isProcessing = false;
            }
        },
        onInput(values) {
            this.values = values;
            this.isSaved = false;
            this.saveLazy();
        },
        async onSubmit() {
            if (this.isProcessing === true) {
                return;
            }

            this.cancelSaving();
            this.isProcessing = true;

            try {
                await this.$api.post(
                    this.endpoint + "/content/publish",
                    this.values,
                );
                this.isSaved = true;
                this.$panel.notification.success();
                await this.$panel.view.refresh();
            } catch (error) {
                this.$panel.notification.error(error);
            } finally {
                this.isProcessing = false;
            }
        },
        onViewSave(event) {
            event?.preventDefault?.();
            this.onSubmit();
        },
        /**
         * Autosaves the unsaved version, so a reload or an accidental
         * navigation does not lose what has been typed
         */
        async save() {
            this.cancelSaving();

            const values = this.values;
            const controller = (this.saveAbortController =
                new AbortController());

            try {
                await this.$api.post(
                    this.endpoint + "/content/save",
                    values,
                    { silent: true, signal: controller.signal },
                );

                if (this.values === values) {
                    this.isSaved = true;
                }
            } catch (error) {
                if (error.name !== "AbortError") {
                    this.$panel.error(error);
                }
            } finally {
                if (this.saveAbortController === controller) {
                    this.saveAbortController = null;
                }
            }
        },
        // `$helper.debounce` cannot be cancelled, hence the explicit timer
        saveLazy() {
            clearTimeout(this.saveTimer);
            this.saveTimer = setTimeout(this.save, 1000);
        },
    },
};
</script>
