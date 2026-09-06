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
        // api path of the plugin endpoints, without the /api prefix
        endpoint: String,
        fields: Object,
        // published content, to compare the form against
        latest: {
            type: Object,
            default: () => ({}),
        },
        modified: String,
        // unsaved version, the form is filled with it
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
        // the view is refreshed after saving and discarding
        changes(changes) {
            this.values = this.$helper.object.clone(changes);
            this.isSaved = true;
        },
    },
    created() {
        this.saveLazy = this.$helper.debounce(this.save, 1000);
    },
    mounted() {
        this.$events.on("beforeunload", this.onBeforeUnload);
        this.$events.on("view.save", this.onViewSave);
    },
    destroyed() {
        this.$events.off("beforeunload", this.onBeforeUnload);
        this.$events.off("view.save", this.onViewSave);
    },
    methods: {
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
            const values = this.values;

            try {
                await this.$api.post(
                    this.endpoint + "/content/save",
                    values,
                    { silent: true },
                );

                if (this.values === values) {
                    this.isSaved = true;
                }
            } catch (error) {
                this.$panel.error(error);
            }
        },
    },
};
</script>
