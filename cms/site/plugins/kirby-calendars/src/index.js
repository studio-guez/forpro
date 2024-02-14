import SchedulesView from "./components/SchedulesView.vue";
import ServicesView from "./components/ServicesView.vue";
import CalendarsView from "./components/CalendarsView.vue";
import EventsView from "./components/EventsView.vue";

panel.plugin("mediumsans/kirby-calendars", {
  components: {
    'k-calendars-view': CalendarsView,
    'k-schedules-view': SchedulesView,
    'k-services-view': ServicesView,
    'k-events-view': EventsView
  }
});
