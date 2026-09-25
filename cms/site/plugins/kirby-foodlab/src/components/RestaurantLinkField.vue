<script>
/**
 * Link field of the restaurant form.
 *
 * Kirby's `k-link-field` with a `media` type in place of the native `file`
 * one: the restaurant media lives in the plugin, not in a Kirby model, so the
 * built-in file browser (which reads `$panel.view.props.model`) cannot list
 * it. Everything else — the type dropdown, the inputs, the validation — is
 * Kirby's.
 */
export default {
    extends: "k-link-field",
    props: {
        mediaPath: {
            type: String,
            default: "/",
        },
    },
    computed: {
        activeTypes() {
            const types = this.$helper.link.types(this.options);

            const media = {
                detect: (value) => value.startsWith(this.mediaPath),
                icon: "attachment",
                id: "media",
                input: "restaurant-media",
                label: this.$t("file"),
                link: (value) => value,
                placeholder: this.$t("select") + " …",
                value: (value) => value,
            };

            // `custom` matches anything, so it must stay last or `$helper.link.detect()` never reaches `media`.
            const { custom, ...rest } = types;

            return custom === undefined
                ? { ...types, media }
                : { ...rest, media, custom };
        },
    },
};
</script>
