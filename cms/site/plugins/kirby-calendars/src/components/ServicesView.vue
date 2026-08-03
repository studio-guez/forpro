<template>
  <k-panel-inside>
    <k-header>
      {{ $t('kirbycalendars.services') }}

      <k-button-group slot="buttons">
        <k-button
            text="Ajouter"
            variant="filled"
            icon="add"
            @click="$dialog(`service/${calendar.id}/create`)"
        />
      </k-button-group>
    </k-header>

    <table class="k-table">

      <thead>
      <tr>
        <th class="k-table-index-column" style="text-align: center;">
          #
        </th>
        <th>Nom</th>
        <th>Durée</th>
        <th class="k-table-index-column"></th>
      </tr>
      </thead>

      <tbody>
      <tr v-for="(service, id, index) in services" :key="id">
        <td class="k-table-index-column" style="text-align: center;">
          {{ index }}
        </td>
        <td style="width: 10%;">{{ service.name }}</td>
        <td>{{ service.duration }}</td>
        <td class="k-table-options-column">
          <k-options-dropdown :options="[
                {
                  text: 'Modifier',
                  icon: 'edit',
                  click: () => $dialog(`service/${id}/edit`)
                },
                {
                  text: 'Supprimer',
                  icon: 'trash',
                  click: () => $dialog(`service/${id}/delete`)
                }
              ]"/>
        </td>
      </tr>
      </tbody>

    </table>
  </k-panel-inside>
</template>

<script>
export default {
  props: {
    calendar: Array,
    schedules: Array,
    services: Array,
  },
  methods: {
    goto(path) {
      this.$go(path);
    }
  }
};
</script>

<style>
.k-table {
  table-layout: fixed;
}
</style>
