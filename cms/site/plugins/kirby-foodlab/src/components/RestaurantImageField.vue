<template>
    <k-field :label="label" :help="help" :name="name">
        <div class="k-restaurant-image-field">
            <k-image-frame
                v-if="value"
                :src="src"
                back="pattern"
                ratio="3/2"
                class="k-restaurant-image-field-preview"
            />

            <k-button-group>
                <k-button
                    :icon="isUploading ? 'loader' : 'upload'"
                    :disabled="isUploading"
                    variant="filled"
                    size="sm"
                    @click="$refs.input.click()"
                >
                    {{ value ? "Remplacer" : "Ajouter" }}
                </k-button>
                <k-button
                    v-if="value"
                    icon="trash"
                    theme="negative"
                    variant="filled"
                    size="sm"
                    @click="remove"
                >
                    Supprimer
                </k-button>
            </k-button-group>

            <input
                ref="input"
                type="file"
                accept="image/jpeg,image/png,image/gif,image/webp,image/svg+xml"
                hidden
                @change="upload"
            />
        </div>
    </k-field>
</template>

<script>
export default {
    props: {
        label: String,
        help: String,
        name: String,
        value: String,
        mediaBase: String,
    },
    data() {
        return {
            isUploading: false,
        };
    },
    computed: {
        src() {
            return this.mediaBase + this.value;
        },
    },
    methods: {
        upload(event) {
            const file = event.target.files[0];
            event.target.value = null;

            if (!file) return;

            this.isUploading = true;

            const previousFilename = this.value;

            const reader = new FileReader();

            reader.onload = () => {
                this.$api
                    .post("/restaurant/media/upload", {
                        filename: file.name,
                        mime: file.type,
                        data: reader.result.split(",")[1],
                    })
                    .then((response) => {
                        this.$emit("input", response.filename);

                        if (previousFilename) {
                            this.$api
                                .post("/restaurant/media/delete", {
                                    filename: previousFilename,
                                })
                                .catch(() => {
                                    // best-effort: ignore errors
                                });
                        }
                    })
                    .catch((error) => {
                        this.$panel.notification.error(
                            error.message || "L'envoi de l'image a échoué",
                        );
                    })
                    .finally(() => {
                        this.isUploading = false;
                    });
            };

            reader.onerror = () => {
                this.isUploading = false;
                this.$panel.notification.error("Impossible de lire le fichier");
            };

            reader.readAsDataURL(file);
        },
        remove() {
            const filename = this.value;

            this.$emit("input", "");

            if (filename) {
                this.$api
                    .post("/restaurant/media/delete", { filename })
                    .catch(() => {
                        // best-effort: ignore errors so the form field clears regardless
                    });
            }
        },
    },
};
</script>

<style>
.k-restaurant-image-field-preview {
    max-width: 20rem;
    margin-bottom: var(--spacing-3);
    border-radius: var(--rounded);
}
</style>
