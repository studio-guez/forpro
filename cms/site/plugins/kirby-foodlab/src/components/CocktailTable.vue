<template>
    <table class="k-table" style="margin-top: 20px; margin-bottom: 25px">
        <thead>
            <tr>
                <th class="k-table-index-column"></th>
                <th>Nom</th>
                <th>Description</th>
                <th>Volume</th>
                <th>Prix</th>
                <th class="k-table-options-column"></th>
            </tr>
        </thead>
        <k-draggable
            :list="cocktails"
            :handle="true"
            @change="updateOrder('cocktails')"
            :options="{
                fallbackClass: 'k-table-row-fallback',
                ghostClass: 'k-table-row-ghost',
            }"
            element="tbody"
        >
            <tr v-for="(item, index) in cocktails" :key="item.id">
                <td data-sortable="true">
                    <k-sort-handle />
                </td>
                <td>{{ item.name }}</td>
                <td>{{ item.description }}</td>
                <td>{{ item.volume }}</td>
                <td>{{ item.price }}</td>
                <td class="k-table-options-column">
                    <k-options-dropdown
                        :options="[
                            {
                                text: 'Modifier',
                                icon: 'edit',
                                click: () =>
                                    $dialog(`menu/cocktail/${item.id}/edit`),
                            },
                            {
                                text: 'Supprimer',
                                icon: 'trash',
                                click: () =>
                                    $dialog(`menu/cocktail/${item.id}/delete`),
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
        cocktails: {
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
