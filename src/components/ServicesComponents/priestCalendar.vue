<template>
  <FullCalendar :options="calendarOptions" />
</template>

<script setup>
import { ref, watch } from "vue";
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid"; // Month view
import timeGridPlugin from "@fullcalendar/timegrid"; // Week & Day views
import interactionPlugin from "@fullcalendar/interaction"; // Drag & Drop
import listPlugin from "@fullcalendar/list"; // List view

const props = defineProps({
  initialView: { type: String, default: "dayGridMonth" },
  events: { type: Array, default: () => [] },
  //   viewEventFunction: { type: Function, default: () => {} },
});

const calendarOptions = ref({
  plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin, listPlugin],
  initialView: "timeGridWeek",
  dayMaxEvents: true, // Enables the "See More" link when there are too many events

  headerToolbar: {
    left: "prev,next today",
    center: "title",
    right: "dayGridMonth,timeGridWeek,timeGridDay,listMonth",
  },
  selectable: true,
  editable: true,
  events: props.events,

  eventContent: function (arg) {
    let icon = arg.event.extendedProps.icon || "📅"; // Default icon
    let eventTitle = document.createElement("div");
    eventTitle.innerHTML = `${icon} ${arg.event.title}`;
    return { domNodes: [eventTitle] };
  },
  //   eventClick: (info) => {
  //     props.viewEventFunction(info.event); // Call the function prop with the clicked event
  //   },
  eventDidMount: (info) => {
    info.el.style.backgroundColor = "#FFBF00"; // Change event background dynamically
    info.el.style.borderRadius = "5px"; // Optional: Rounded corners
    info.el.style.padding = "5px"; // Optional: Add spacing
    info.el.style.color = "white"; // Optional: Add spacing
  },
});

// Watch for changes in events and update dynamically
watch(
  () => props.events,
  (newEvents) => {
    calendarOptions.value.events = newEvents;
  },
  { deep: true }
);
console.log(calendarOptions.value);
</script>

<style scoped></style>
