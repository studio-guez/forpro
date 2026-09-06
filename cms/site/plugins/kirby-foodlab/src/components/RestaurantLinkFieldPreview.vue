<template>
    <!--
        Media links get the same tag preview Kirby gives a `file` link: icon
        (or thumbnail) plus filename. Everything else is left to Kirby.
    -->
    <ul v-if="file" class="k-restaurantlink-field-preview k-tags">
        <li>
            <!-- no `link`: the path is not a panel route, and clicking the
                 row already opens the editor -->
            <k-tag
                :image="file.image"
                :text="file.text"
                element="div"
                theme="light"
            />
        </li>
    </ul>

    <k-link-field-preview
        v-else
        :column="column"
        :field="field"
        :value="value"
    />
</template>

<script>
const IMAGE_EXTENSIONS = ["jpg", "jpeg", "png", "gif", "webp", "svg"];

/**
 * Preview of a `restaurantlink` value in structure and object tables.
 *
 * Kirby renders a `file` link as a tag with a thumbnail and the filename, but
 * it only recognises `file://` uuids — a media link is a plain path, which
 * would show up as the raw url. This rebuilds the same tag from the path.
 */
export default {
    inheritAttrs: false,
    props: {
        column: {
            type: Object,
            default: () => ({}),
        },
        field: {
            type: Object,
            default: () => ({}),
        },
        value: {
            default: "",
        },
    },
    computed: {
        mediaPath() {
            return this.field.mediaPath ?? this.column.mediaPath;
        },
        file() {
            if (
                typeof this.value !== "string" ||
                !this.mediaPath ||
                this.value.startsWith(this.mediaPath) === false
            ) {
                return null;
            }

            const filename = this.value.slice(this.mediaPath.length);
            const extension = filename.split(".").pop().toLowerCase();
            const isImage = IMAGE_EXTENSIONS.includes(extension);

            return {
                text: filename,
                image: {
                    back: "pattern",
                    color: isImage ? "gray-500" : "red-400",
                    cover: true,
                    icon: isImage ? "image" : "file-document",
                    src: isImage ? this.value : null,
                },
            };
        },
    },
};
</script>
