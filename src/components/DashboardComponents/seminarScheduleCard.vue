<template>
  <div class="service row">
    <q-card class="my-card bg-white" style="width: 50vw" flat>
      <div
        v-for="sched in cardValues"
        :key="sched.event_id"
        class="col-xs-12 col-sm-6 col-md-4"
      >
        <q-card class="q-pa-md rounded-borders" flat>
          <div class="row items-center no-wrap q-gutter-md">
            <!-- Avatar -->
            <q-avatar size="30" text-color="black">
              <q-icon name="event_note" size="30px" />
            </q-avatar>

            <!-- Info (Left-Aligned) -->
            <div class="column" style="flex-grow: 1">
              <div class="text-subtitle1 text-weight-medium">
                {{ formatDate(sched.Date) }}
              </div>
              <div class="text-subtitle1 text-weight-medium">
                {{ sched.Client }}
              </div>
              <div class="text-subtitle1 text-weight-medium">
                {{ sched.Event }}
              </div>
              <div class="text-subtitle2 text-weight-light">
                {{ formatTime(sched.Time_from) }} -
                {{ formatTime(sched.Time_to) }}
              </div>
            </div>

            <!-- Info (Right-Aligned) -->
            <div
              class="column q-pl-none"
              style="flex-shrink: 0; text-align: right"
            >
              <div class="text-subtitle2 text-weight-light">
                {{ sched.Venue_name }}
              </div>
              <div class="text-subtitle2 text-weight-light">
                {{ sched.Priest_assigned }}
              </div>
              <q-btn unelevated>See more</q-btn>
            </div>
          </div>
        </q-card>
      </div>
    </q-card>
  </div>
</template>

<script>
import { defineComponent, reactive, watch, ref, onMounted } from "vue";
import { upcomingSchedule, upcoming } from "src/composables/updateEvent";
import gsap from "gsap";

export default defineComponent({
  name: "upcomingCard",
  props: {
    cardValues: { type: Array, default: () => [] },
  },
  setup(props) {
    console.log(props.cardValues);
    onMounted(() => {
      console.log("Mounted value:", props.cardValues);
    });

    // Watch for changes
    watch(
      () => props.cardValues,
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
