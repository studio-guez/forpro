<template>
    <k-panel-inside>
        <k-header>
            Restaurant
            <k-button-group slot="buttons">
                <k-button
                    :icon="isSubmitting ? 'loader' : 'check'"
                    :theme="hasBeenSubmitted ? 'green' : null"
                    :disabled="isSubmitting"
                    variant="filled"
                    @click="submit"
                >
                    Enregistrer
                </k-button>
            </k-button-group>
        </k-header>

        <k-form v-model="values" :fields="fields" @submit="submit" />
    </k-panel-inside>
</template>

<script>
export default {
    props: {
        fields: Object,
        content: Object,
    },
    data() {
        return {
            values: { ...this.content },
            isSubmitting: false,
            hasBeenSubmitted: false,
        };
    },
    methods: {
        submit() {
            if (this.isSubmitting) return;

            this.isSubmitting = true;

            this.$api
                .post("/restaurant/update", this.values)
                .then(() => {
                    this.hasBeenSubmitted = true;
                    this.$panel.notification.success(
                        "Le contenu a été enregistré",
                    );

                    setTimeout(() => {
                        this.hasBeenSubmitted = false;
                    }, 5000);
                })
                .catch((error) => {
                    this.$panel.notification.error(
                        error.message || "L'enregistrement a échoué",
                    );
                })
                .finally(() => {
                    this.isSubmitting = false;
                });
        },
    },
};
</script>
