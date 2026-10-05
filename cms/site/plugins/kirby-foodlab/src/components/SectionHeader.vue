<template>
    <k-grid style="margin-top: 40px">
        <div
            class="k-column"
            style="--width: 1/3; display: flex; justify-content: space-between"
        >
            <k-button-group layout="collapsed">
                <k-button
                    variant="filled"
                    icon="angle-down"
                    @click="$emit('down')"
                ></k-button>
                <k-button
                    variant="filled"
                    icon="angle-up"
                    @click="$emit('up')"
                ></k-button>
            </k-button-group>
            <k-menu-title-input
                :title="title"
                endpoint="/restaurant/menu/metadata/name"
                :params="{ category }"
            />
        </div>
        <div class="k-column" style="--width: 2/3; justify-self: end">
            <k-button-group layout="collapsed">
                <k-button
                    variant="filled"
                    :tooltip="showHide ? 'Afficher' : 'Cacher'"
                    :icon="showHide ? 'hidden' : 'preview'"
                    @click="$emit('hide')"
                />
                <k-button variant="filled" icon="plus" @click="$emit('create')">
                    Ajouter
                </k-button>
            </k-button-group>
        </div>
    </k-grid>
</template>

<script>
export default {
    name: "SectionHeader",
    props: {
        category: {
            type: String,
            required: true,
        },
        title: {
            type: String,
            required: true,
        },
        showHide: {
            type: Boolean,
            required: true,
        },
    },
};
</script>
