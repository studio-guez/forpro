<template>
  <k-inside>
    <k-header>
      Congés

      <k-button-group slot="buttons">
        <k-button
            text="Ajouter"
            variant="filled"
            icon="add"
            @click="$dialog('leave/create')"
        />
      </k-button-group>
    </k-header>

    <table class="k-table">
      <thead>
      <tr>
        <th class="k-table-index-column"
            style="text-align: center;">
          #
        </th>
        <th>Nom</th>
        <th>Début</th>
        <th>Fin</th>
        <th class="k-table-index-column"></th>
      </tr>
      </thead>

      <tbody>
      <tr v-for="(leave, id, index) in leaves" :key="id">
        <td class="k-table-index-column" style="text-align: center;">
          {{ index }}
        </td>
        <td :title="leave.name" style="width: 10%;">{{ leave.name }}</td>
        <td :title="leave.start_datetime" style="width: 10%;">{{ leave.start_datetime }}</td>
        <td :title="leave.end_datetime">{{ leave.end_datetime }}</td>
        <td class="k-table-options-column">
          <k-options-dropdown :options="[
              {
                text: 'Modifier',
                icon: 'edit',
                click: () => $dialog(`leave/${id}/edit`)
              },
              {
                text: 'Supprimer',
                icon: 'trash',
                click: () => $dialog(`leave/${id}/delete`)
              },
            ]"/>
        </td>
      </tr>
      </tbody>
    </table>
  </k-inside>
</template>

<script>
export default {
  props: {
    leaves: Array,
    options: Array
  },
  methods: {
    goto(path) {
      this.$go(path);
    },
    formatDate(dateStr) {
      if (dateStr) {
        const [date, time] = dateStr.split(' ');
        const [year, month, day] = date.split('-');
        return `${day}.${month}.${year}`;
      }
      return '';
    },
    getIsConfirmedIcon(isConfirmed) {
      return isConfirmed ? 'check' : 'cancel';
    },
    getIsConfirmedTheme(isConfirmed) {
      return isConfirmed ? 'positive' : 'negative';
    }
  }
};
</script>

<style>
.k-table {
  table-layout: fixed;
}
</style>
