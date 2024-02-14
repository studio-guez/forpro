<template>
  <k-inside>
    <k-header>
      Évènements

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
          <th>Description</th>
          <th>Date</th>
          <th>Début</th>
          <th>Fin</th>
          <th>Email</th>
          <th>Téléphone</th>
          <th class="k-table-index-column"></th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="(event, id, index) in events" :key="id">
          <td class="k-table-index-column" style="text-align: center;">
            {{ index }}
          </td>
          <td style="width: 10%;">{{ event.name }}</td>
          <td>{{ event.description }}</td>
          <td data-align="center">{{ event.date }}</td>
          <td data-align="center">{{ event.start_time }}</td>
          <td data-align="center">{{ event.end_time }}</td>
          <td>{{ event.email }}</td>
          <td>{{ event.phone }}</td>
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
