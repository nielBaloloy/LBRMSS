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
});

const calendarOptions = ref({
  plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin, listPlugin],
  initialView: props.initialView,
  headerToolbar: {
    left: "prev,next today",
    center: "title",
    right: "dayGridMonth,timeGridWeek,timeGridDay,listMonth",
  },
  selectable: true,
  editable: true,
  events: props.events,
});

// Watch for changes in events and update dynamically
watch(
  () => props.events,
  (newEvents) => {
    calendarOptions.value.events = newEvents;
  },
  { deep: true }
);
</script>

<style scoped></style>
