<script>
/**
 * Media picker of the restaurant content form.
 *
 * Kirby's own `k-files-field` is reused as is, so the field looks and behaves
 * exactly like the `type: files` fields of the former site blueprint: same
 * "Select"/"Upload" buttons, same item list, same dropzone. Only the upload
 * callback differs — there is no Kirby model behind this view, so the panel
 * content state must not be refreshed once a file has been uploaded.
 *
 * Both endpoints (picker + upload) are served by the foodlab plugin under
 * `restaurant/fields/<name>`, see routes/index.php.
 */
export default {
    extends: "k-files-field",
    computed: {
        uploadOptions() {
            return {
                accept: this.uploads.accept,
                max: this.max,
                multiple: this.multiple,
                preview: this.uploads.preview,
                url:
                    this.$panel.urls.api +
                    "/" +
                    this.endpoints.field +
                    "/upload",
                on: {
                    done: (files) => {
                        if (this.multiple === false) {
                            this.selected = [];
                        }

                        for (const file of files) {
                            const exists = this.selected.find(
                                (item) => item.id === file.id,
                            );

                            if (exists === undefined) {
                                this.selected.push(file);
                            }
                        }

                        this.onInput();
                    },
                },
            };
        },
    },
};
</script>
