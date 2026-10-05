<template>
    <k-input :value="name" type="text" :icon="icon" @input="input" />
</template>

<script>
export default {
    name: "MenuTitleInput",
    props: {
        title: String,
        endpoint: {
            type: String,
            required: true,
        },
        // Posted to `endpoint` along with the title, which is sent as `value`
        params: {
            type: Object,
            default: () => ({}),
        },
    },
    data() {
        return {
            // `title` only changes when the view reloads: an input bound to it
            // directly is reset to the old title on every re-render
            name: this.title,
            isSaving: false,
            hasBeenSaved: false,
        };
    },
    watch: {
        title(title) {
            // A reload answered while a save is pending carries the old title
            if (this.isSaving === false) {
                this.name = title;
            }
        },
    },
    created() {
        this.save = this.$helper.debounce(this.save, 500);
    },
    methods: {
        input(value) {
            this.name = value;
            this.isSaving = true;
            this.hasBeenSaved = false;
            this.save(value);
        },
        async save(value) {
            let saved = true;

            try {
                await this.$api.post(this.endpoint, { ...this.params, value });
            } catch (error) {
                saved = false;
            }

            // A newer edit is still being saved and will report instead
            if (value !== this.name) {
                return;
            }

            this.isSaving = false;

            if (saved === false) {
                this.$panel.notification.error(
                    "Le titre n'a pas pu être enregistré",
                );
                return;
            }

            this.hasBeenSaved = true;
            setTimeout(() => {
                this.hasBeenSaved = false;
            }, 2000);
        },
    },
    computed: {
        icon() {
            if (this.isSaving) {
                return "loader";
            }

            return this.hasBeenSaved ? "check" : "edit";
        },
    },
};
</script>
