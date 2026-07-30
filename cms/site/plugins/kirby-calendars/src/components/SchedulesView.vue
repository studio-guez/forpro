<template>
  <k-panel-inside>
    <k-header>
      {{ $t('kirbycalendars.schedules') }}

      <k-button-group slot="buttons">
        <k-button
            text="Ajouter"
            variant="filled"
            icon="add"
            @click="$dialog(`schedule/${calendar.id}/create`)"
        />
      </k-button-group>
    </k-header>

    <table class="k-table">

      <thead>
        <tr>
          <th class="k-table-index-column" style="text-align: center;">
            #
          </th>
          <th>Jours</th>
          <th>Ouverture</th>
          <th>Fermeture</th>
          <th>Fermé ?</th>
          <th class="k-table-index-column"></th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="(schedule, id, index) in schedules" :key="id">
          <td class="k-table-index-column" style="text-align: center;">
            {{ index }}
          </td>
          <td style="width: 10%;">{{ getDayFromId(schedule.day_id) }}</td>
          <td>{{ schedule.opening_hour }}</td>
          <td>{{ schedule.closing_hour }}</td>
          <td>{{ formatIsClosed(schedule.is_closed) }}</td>
          <td class="k-table-options-column">
            <k-options-dropdown :options="[
                {
                  text: 'Modifier',
                  icon: 'edit',
                  click: () => $dialog(`schedule/${id}/edit`)
                },
                {
                  text: 'Supprimer',
                  icon: 'trash',
                  click: () => $dialog(`schedule/${id}/delete`)
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
    },
    getDayFromId(id) {
      let days = [
        'Dimanche',
        'Lundi',
        'Mardi',
        'Mercredi',
        'Jeudi',
        'Vendredi',
        'Samedi',
      ]
      return days[id];
    },
    formatIsClosed(state) {
      return state ? 'Oui' : 'Non';
    }
  }
};
</script>

<style>
.k-table {
  table-layout: fixed;
}
</style>
