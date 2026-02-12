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

    <k-headline style="margin-top: 1.5rem; margin-bottom: 0.75rem">foodLab</k-headline>

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
                  text: 'Dupliquer',
                  icon: 'copy',
                  click: () => $dialog(`menu-du-jour/${item.id}/duplicate`),
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

    <!-- foddLab -->
    <k-headline style="margin-top: 3rem; margin-bottom: 0.75rem">foddLab</k-headline>

    <k-button
      text="Ajouter"
      variant="filled"
      icon="add"
      style="margin-bottom: 0.75rem"
      @click="$dialog('fodd-lab/create')"
    />

    <table class="k-table k-fodd-lab" v-if="foddLabItems && foddLabItems.length">
      <thead>
        <tr>
          <th class="k-table-index-column" style="text-align: center">#</th>
          <th>Date</th>
          <th>Menu</th>
          <th>Prix</th>
          <th class="k-table-options-column"></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in foddLabItems" :key="item.id">
          <td class="k-table-index-column" style="text-align: center">
            {{ index + 1 }}
          </td>
          <td>{{ item.date }}</td>
          <td>{{ item.menu || '–' }}</td>
          <td>{{ item.prix || '–' }}</td>
          <td class="k-table-options-column">
            <k-options-dropdown
              :options="[
                {
                  text: 'Modifier',
                  icon: 'edit',
                  click: () => $dialog(`fodd-lab/${item.id}/edit`),
                },
                {
                  text: 'Dupliquer',
                  icon: 'copy',
                  click: () => $dialog(`fodd-lab/${item.id}/duplicate`),
                },
                {
                  text: 'Supprimer',
                  icon: 'trash',
                  click: () => $dialog(`fodd-lab/${item.id}/delete`),
                },
              ]"
            />
          </td>
        </tr>
      </tbody>
    </table>

    <k-empty v-else icon="calendar" @click="$dialog('fodd-lab/create')">
      Aucun élément foddLab
    </k-empty>
  </k-inside>
</template>

<script>
export default {
  props: {
    items: Array,
    foddLabItems: Array,
  },
};
</script>

<style>
.k-table.k-menu-du-jour,
.k-table.k-fodd-lab {
  table-layout: fixed;
}
</style>
