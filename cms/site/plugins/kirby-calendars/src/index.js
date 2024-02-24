import SchedulesView from "./components/SchedulesView.vue";
import ServicesView from "./components/ServicesView.vue";
import CalendarsView from "./components/CalendarsView.vue";
import CalendarView from "./components/CalendarView.vue";
import EventsView from "./components/EventsView.vue";
import LeavesView from "./components/LeavesView.vue";

panel.plugin("mediumsans/kirby-calendars", {
  components: {
    'k-calendars-view': CalendarsView,
    'k-calendar-view': CalendarView,
    'k-schedules-view': SchedulesView,
    'k-services-view': ServicesView,
    'k-events-view': EventsView,
    'k-leaves-view': LeavesView
  }
});
