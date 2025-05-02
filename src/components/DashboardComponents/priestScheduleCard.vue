<template>
  <div class="service row">
    <q-card class="my-card bg-white" style="width: 100vw" flat>
      <p class="text-h6 q-pa-md">Today's Schedule</p>
      <div
        v-for="sched in cardValue"
        :key="sched.event_id"
        class="col-xs-12 col-sm-6 col-md-4"
      >
        <q-card class="q-pa-md rounded-borders" flat>
          <div class="row items-center no-wrap q-gutter-md">
            <!-- Avatar -->
            <q-avatar size="30" text-color="black">
              <q-icon name="event_note" size="30px" />
            </q-avatar>
            <!-- Info -->
            <div class="column">
              <div class="text-subtitle1 text-weight-medium">
                {{ formatDate(sched.date) }}
              </div>
              <div class="text-subtitle1 text-weight-medium">
                {{ sched.servicename }}
              </div>
              <div class="text-subtitle2 text-weight-light">
                {{ formatTime(sched.time_to) }} -
                {{ formatTime(sched.time_to) }}
              </div>
              <div class="text-subtitle2 text-weight-light">
                {{ sched.venue_name }}
              </div>
              <div class="text-subtitle2 text-weight-light">
                Fr.{{ sched.fullname }}
              </div>
            </div>
          </div>
        </q-card>
      </div>
    </q-card>
  </div>
</template>

<script>
import { defineComponent, reactive, watch, ref, onMounted } from "vue";

import gsap from "gsap";

export default defineComponent({
  name: "EventCard",
  props: {
    cardValue: { type: Array, default: () => [] },
  },
  setup(props) {
    console.log(props.cardValue);
    onMounted(() => {
      console.log("Mounted value:", props.cardValue);
    });

    // Watch for changes
    watch(
      () => props.cardValue,
      (newVal) => {
        console.log("Updated value:", newVal);
      },
      { immediate: true, deep: true }
    );
    function formatDate(dateStr) {
      const options = { year: "numeric", month: "long", day: "numeric" };
      return new Date(dateStr).toLocaleDateString("en-US", options);
    }
    function formatTime(timeStr) {
      const [hours, minutes] = timeStr.split(":");
      const date = new Date();
      date.setHours(+hours);
      date.setMinutes(+minutes);
      return date.toLocaleTimeString("en-US", {
        hour: "numeric",
        minute: "2-digit",
        hour12: true,
      });
    }
    return {
      formatTime,
      formatDate,
    };
  },
});
</script>
<style scoped>
.my-card {
  margin-top: 1em;
  height: 300px;
}
.count {
  font-weight: bold;
}
</style>
