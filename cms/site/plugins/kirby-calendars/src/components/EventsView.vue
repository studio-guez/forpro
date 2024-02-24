<template>
  <k-inside>
    <k-header>
      {{ $t('kirbycalendars.events') }}

      <k-button-group slot="buttons">
        <k-button
            text="Ajouter"
            variant="filled"
            icon="add"
            @click="$dialog('event/create')"
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
        <th>Sujet</th>
        <th>Horaire</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Email</th>
        <th>Téléphone</th>
        <th>Confirmé</th>
        <th class="k-table-index-column"></th>
      </tr>
      </thead>

      <tbody>
      <tr v-for="(event, id, index) in events" :key="id">
        <td class="k-table-index-column" style="text-align: center;">
          {{ index }}
        </td>
        <td :title="event.name" style="width: 10%;">{{ event.name }}</td>
        <td :title="event.subject" style="width: 10%;">{{ event.subject }}</td>
        <td :title="formatDate(event.date) + ' ' + event.start_time + '-' + event.end_time"
            data-align="center">{{ formatDate(event.date) }} {{ event.start_time }}-{{ event.end_time }}
        </td>
        <td :title="event.lastname">{{ event.lastname }}</td>
        <td :title="event.firstname">{{ event.firstname }}</td>
        <td :title="event.email">{{ event.email }}</td>
        <td :title="event.phone">{{ event.phone }}</td>
        <td data-align="center">
          <k-button
              :icon="getIsConfirmedIcon(event.is_confirmed)"
              :theme="getIsConfirmedTheme(event.is_confirmed)"
              variant="dimmed"
          >
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
    events: Array,
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
