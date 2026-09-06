<template>
    <div class="k-restaurant-media-input">
        <k-button
            :disabled="disabled"
            :icon="value ? 'attachment' : 'add'"
            :text="text"
            size="sm"
            variant="filled"
            @click="open"
        />
        <k-button
            v-if="value && !disabled"
            :title="$t('remove')"
            icon="remove"
            size="sm"
            @click="$emit('input', '')"
        />
    </div>
</template>

<script>
/**
 * The input `restaurantlink` renders for its `media` type: picks a file from
 * the restaurant media library through Kirby's own files dialog and emits the
 * stored path.
 *
 * The link field only passes the standard input props on, so the picker
 * endpoint is read from the view itself (see views/restaurant.php).
 */
export default {
    props: {
        disabled: Boolean,
        id: [String, Number],
        placeholder: String,
        required: Boolean,
        value: {
            type: String,
            default: "",
        },
    },
    emits: ["input"],
    computed: {
        endpoint() {
            return this.$panel.view.props.endpoint + "/fields/link";
        },
        text() {
            return this.value
                ? this.value.split("/").pop()
                : (this.placeholder ?? this.$t("select") + " …");
        },
    },
    methods: {
        open() {
            if (this.disabled === true) {
                return;
            }

            this.$panel.dialog.open({
                component: "k-files-dialog",
                props: {
                    endpoint: this.endpoint,
                    hasSearch: true,
                    multiple: false,
                    value: [],
                },
                on: {
                    submit: (files) => {
                        this.$panel.dialog.close();

                        const file = files?.[0];

                        if (file?.path) {
                            this.$emit("input", file.path);
                        }
                    },
                },
            });
        },
    },
};
</script>

<style>
.k-restaurant-media-input {
    display: flex;
    align-items: center;
    gap: var(--spacing-1);
    padding: var(--spacing-1);
    min-width: 0;
}
.k-restaurant-media-input .k-button-text {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
</style>
