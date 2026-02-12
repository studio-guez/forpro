<template>
  <k-inside>
    <k-header>
      Menu du jour

      <k-button-group slot="buttons">
        <k-button
          text="Ajouter"
          variant="filled"
          icon="add"
          @click="$dialog('menu-du-jour/create')"
        />
      </k-button-group>
    </k-header>

    <table class="k-table k-menu-du-jour" v-if="items && items.length">
      <thead>
        <tr>
          <th class="k-table-index-column" style="text-align: center">#</th>
          <th>Date</th>
          <th>Station 1</th>
          <th>Station 2</th>
          <th>Station 3</th>
          <th>Station 4</th>
          <th class="k-table-options-column"></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in items" :key="item.id">
          <td class="k-table-index-column" style="text-align: center">
            {{ index + 1 }}
          </td>
          <td>{{ item.date }}</td>
          <td>{{ item.station1_menu || '–' }}</td>
          <td>{{ item.station2_menu || '–' }}</td>
          <td>{{ item.station3_menu || '–' }}</td>
          <td>{{ item.station4_menu || '–' }}</td>
          <td class="k-table-options-column">
            <k-options-dropdown
              :options="[
                {
                  text: 'Modifier',
                  icon: 'edit',
                  click: () => $dialog(`menu-du-jour/${item.id}/edit`),
                },
                {
                  text: 'Supprimer',
                  icon: 'trash',
                  click: () => $dialog(`menu-du-jour/${item.id}/delete`),
                },
              ]"
            />
          </td>
        </tr>
      </tbody>
    </table>

    <k-empty v-else icon="calendar" @click="$dialog('menu-du-jour/create')">
      Aucun menu du jour
    </k-empty>
  </k-inside>
</template>

<script>
export default {
  props: {
    items: Array,
  },
};
</script>

<style>
.k-table.k-menu-du-jour {
  table-layout: fixed;
}
</style>
