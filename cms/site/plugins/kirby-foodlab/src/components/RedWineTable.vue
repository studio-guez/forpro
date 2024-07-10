<template>
    <table class="k-table" style="margin-top: 20px; margin-bottom: 25px">
        <thead>
            <tr>
                <th class="k-table-index-column"></th>
                <th>Nom</th>
                <th>Domaine</th>
                <th>Millésime</th>
                <th>Description</th>
                <th>10cl</th>
                <th>50cl</th>
                <th>75cl</th>
                <th class="k-table-options-column"></th>
            </tr>
        </thead>
        <k-draggable
            :list="redWines"
            :handle="true"
            @change="updateOrder('redWines')"
            :options="{
                fallbackClass: 'k-table-row-fallback',
                ghostClass: 'k-table-row-ghost',
            }"
            element="tbody"
        >
            <tr v-for="(item, index) in redWines" :key="item.id">
                <td class="k-table-index-column" data-sortable="true">
                    <span class="k-table-index">{{ index + 1 }}</span>
                    <k-sort-handle />
                </td>
                <td>{{ item.name }}</td>
                <td>{{ item.domain }}</td>
                <td>{{ item.mill }}</td>
                <td>{{ item.description }}</td>
                <td>{{ item.price10cl }}</td>
                <td>{{ item.price50cl }}</td>
                <td>{{ item.price75cl }}</td>
                <td class="k-table-options-column">
                    <k-options-dropdown
                        :options="[
                            {
                                text: 'Modifier',
                                icon: 'edit',
                                click: () =>
                                    $dialog(`menu/redwine/${item.id}/edit`),
                            },
                            {
                                text: 'Supprimer',
                                icon: 'trash',
                                click: () =>
                                    $dialog(`menu/redwine/${item.id}/delete`),
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
        redWines: Array,
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
