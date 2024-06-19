<template>
  <k-inside>
    <k-header>
      {{ $t('kirbycalendars.events') }}
    </k-header>

    <table class="k-table">
      <thead>
      <tr>
        <th class="k-table-index-column"
            style="text-align: center;">
          #
        </th>
        <th>Titre</th>
        <th>Sujet</th>
        <th>Horaire</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Date de la demande</th>
        <th>Email</th>
        <th>Téléphone</th>
        <th>Confirmé</th>
        <th>Invitations / Assignés</th>
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
        <template v-if="event.date_request?.date">
          <td :title="formatDate(event.date_request?.date)"
              data-align="center"
            >{{ formatDate(event.date_request?.date) }}
          </td>
        </template>
        <template v-else>
          <td style="color: lightgrey">
            before request save option
          </td>
        </template>

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
        <td>
          <ul v-for="invitation in event.invitations" :key="invitation.id">
            <li>{{ invitation.email }}</li>
          </ul>
        </td>
        <td class="k-table-options-column">
          <k-options-dropdown :options="[
              {
                text: 'Partager',
                icon: 'share',
                click: () => $dialog(`event/${id}/share`)
              },
              {
                text: 'Supprimer',
                icon: 'trash',
                click: () => $dialog(`event/${id}/delete`)
              },
            ]"/>
        </td>
      </tr>
      </tbody>
    </table>
    <div class="k-events-view__exports">
      <k-button-group>
        <k-button
          variant="filled"
          size="lg"
        >CSV export</k-button>
      </k-button-group>
    </div>
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

.k-events-view__exports {
  padding-top: 1em;
}

.k-events-view__exports > .k-button-group {
  justify-content: flex-end;
}
</style>
