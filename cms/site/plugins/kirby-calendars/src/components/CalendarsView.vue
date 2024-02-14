<template>
  <k-inside>
    <k-header>
      Calendrier

      <k-button-group slot="buttons">
        <k-button
            text="Ajouter"
            variant="filled"
            icon="add"
            @click="$dialog('calendar/create')"
        />
      </k-button-group>
    </k-header>

    <table class="k-table">
      <thead>
        <tr>
          <th class="k-table-index-column"
              style="text-align: center;">#
          </th>
          <th>Nom</th>
          <th>Description</th>
          <th>Services</th>
          <th>Horaires</th>
          <th class="k-table-index-column"></th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="(calendar, id, index) in calendars" :key="id">
          <td class="k-table-index-column" style="text-align: center;">
            {{ index }}
          </td>
          <td style="width: 10%;">{{ calendar.name }}</td>
          <td>{{ calendar.description }}</td>
          <td data-align="center">{{ calendar.nbrServices }}</td>
          <td data-align="center">
            <k-button
                :icon="calendar.scheduleState.icon"
                :theme="calendar.scheduleState.theme"
                variant="dimmed"
            >
              {{ calendar.scheduleState.status }}
            </k-button>
          </td>
          <td class="k-table-options-column">
            <k-options-dropdown :options="[
              {
                text: 'Modifier',
                icon: 'edit',
                click: () => $dialog(`calendar/${id}/edit`)
              },
              {
                text: 'Supprimer',
                icon: 'trash',
                click: () => $dialog(`calendar/${id}/delete`)
              },
              {
                text: 'Services',
                icon: 'dashboard',
                click: () => goto(`/kirby-calendars/calendar/${id}/services`)
              },
              {
                text: 'Horaires',
                icon: 'clock',
                click: () => goto(`/kirby-calendars/calendar/${id}/schedules`)
              },
              {
                text: 'Évènements',
                icon: 'page',
                click: () => goto(`/kirby-calendars/calendar/${id}/events`)
              }
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
    calendars: Array,
    options: Array
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
