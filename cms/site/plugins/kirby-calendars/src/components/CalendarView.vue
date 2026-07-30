<template>
  <k-panel-inside>
    <k-header>
      {{ $t('kirbycalendars.calendar') }} - {{ calendar.name }}

      <k-button-group layout="collapsed" slot="buttons">
        <k-button
            text="Modifier"
            variant="filled"
            icon="edit"
            @click="$dialog(`calendar/${calendar.id}/edit`)"
        />
      </k-button-group>
    </k-header>

    <k-button-group layout="collapsed">
      <k-button
          :text="$t('kirbycalendars.schedules')"
          variant="filled"
          icon="clock"
          size="lg"
          @click="goto(`/kirby-calendars/calendar/${calendar.id}/schedules`)"
      />
      <k-button
          :text="$t('kirbycalendars.leaves')"
          variant="filled"
          icon="sun"
          size="lg"
          @click="goto(`/kirby-calendars/calendar/${calendar.id}/leaves`)"
      />
      <k-button
          :text="$t('kirbycalendars.events')"
          variant="filled"
          icon="calendar"
          size="lg"
          @click="goto(`/kirby-calendars/calendar/${calendar.id}/events`)"
      />
    </k-button-group>

    <k-grid style="margin-top: 25px; gap: 0.25rem; --columns: 2">

      <k-field input="text" label="Nom">
        <div class="k-table">
          <table id="link">
            <tbody>
            <tr>
              <td data-mobile="true" class="k-table-cell">
                <k-text-field-preview :value="calendar.name"/>
              </td>
            </tr>
            </tbody>
          </table>
        </div>
      </k-field>

      <k-field label="Responsable">
        <div class="k-table">
          <table id="link">
            <tbody>
            <tr>
              <td data-mobile="true" class="k-table-cell">
                <k-text-field-preview :value="calendar.email"/>
              </td>
            </tr>
            </tbody>
          </table>
        </div>
      </k-field>

    </k-grid>

    <k-grid style="margin-top: 25px; gap: 0.25rem; --columns: 1">
      <k-field input="link" label="URL">
        <div class="k-table">
          <table id="link">
            <tbody>
            <tr>
              <td data-mobile="true" class="k-table-cell">
                <k-url-field-preview :value="calendar.ical"/>
              </td>
            </tr>
            </tbody>
          </table>
        </div>
      </k-field>
    </k-grid>

  </k-panel-inside>
</template>

<script>

export default {
  props: {
    calendar: Array,
  },
  methods: {
    goto(path) {
      this.$go(path);
    },
  },
};
</script>

<style>
.k-url-field-preview {
  padding-inline: var(--table-cell-padding);
}

.k-url-field-preview[data-link] {
  color: var(--link-color);
}

.k-url-field-preview a {
  display: inline-flex;
  align-items: center;
  height: var(--height-xs);
  padding-inline: var(--spacing-1);
  margin-inline: calc(var(--spacing-1) * -1);
  border-radius: var(--rounded);
  max-width: 100%;
  min-width: 0;
}

.k-url-field-preview a > * {
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
  text-decoration: underline;
  text-underline-offset: var(--link-underline-offset);
}

.k-url-field-preview a:hover {
  color: var(--color-text);
}
</style>
