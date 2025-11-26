<template>
    <table class="k-table" style="margin-top: 20px; margin-bottom: 25px">
        <thead>
            <tr>
                <th class="k-table-index-column"></th>
                <th>Nom</th>
                <th>Provenance</th>
                <th class="k-table-options-column"></th>
            </tr>
        </thead>
        <k-draggable
            :list="origins"
            :handle="true"
            @change="updateOrder('origins')"
            :options="{
                fallbackClass: 'k-table-row-fallback',
                ghostClass: 'k-table-row-ghost',
            }"
            element="tbody"
        >
            <tr v-for="(item, index) in origins" :key="item.id">
                <td data-sortable="true">
                  <k-sort-handle />
                </td>
                <td>{{ item.name }}</td>
                <td>{{ item.origin }}</td>
                <td class="k-table-options-column">
                    <k-options-dropdown
                        :options="[
                            {
                                text: 'Modifier',
                                icon: 'edit',
                                click: () =>
                                    $dialog(`menu/origin/${item.id}/edit`),
                            },
                            {
                                text: 'Supprimer',
                                icon: 'trash',
                                click: () =>
                                    $dialog(`menu/origin/${item.id}/delete`),
                            },
                        ]"
                    />
                </td>
            </tr>
        </k-draggable>
    </table>
</template>

<script>
export default {
    props: {
        title: {
            type: String,
            required: true,
        },
        origins: {
            type: Array,
            required: true,
        },
    },
    methods: {
        updateOrder(listName) {
            this.$emit("update-order", listName);
        },
        $dialog(path) {
            this.$emit("open-dialog", path);
        },
    },
};
</script>
