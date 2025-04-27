<template>
  <div class="service row">
    <div class="col-sm">
      <label class="text-h6">My Schedule</label>
      <div class="content" v-if="schedValue.length > 0">
        <div class="schedule">
          <div
            v-for="sched in schedValue"
            :key="sched.event_id"
            class="col-xs-12 col-sm-12 col-md-12 q-mt-md"
          >
            <q-card
              class="q-pa-md rounded-borders bg-grey-1"
              style="min-width: 340px"
              flat
            >
              <div class="row items-center no-wrap q-gutter-md">
                <!-- Avatar -->
                <q-avatar size="30" text-color="amber">
                  <q-icon name="event_note" size="40px" />
                </q-avatar>
                <!-- Info -->
                <div class="column">
                  <div class="text-subtitle2 text-weight-medium">
                    {{ sched.event_name }}
                  </div>
                  <div class="text-subtitle2 text-weight-light">
                    {{ sched.venue_name }}
                  </div>
                  <div class="text-subtitle2 text-weight-light">
                    {{ formatDate(sched.date_from) }},

                    {{ formatTime(sched.time_from) }} -
                    {{ formatTime(sched.time_to) }}
                  </div>

                  <divb class="text-subtitle2 text-weight-medium text-red">
                    {{ countRemaining(sched.date_from, sched.time_from) }}</divb
                  >
                  <div class="view q-pt-md q-gutter-sm">
                    <q-btn
                      size="10px"
                      unelevated
                      color="amber-7"
                      label="Mark as Done"
                      no-caps
                      @click="markAsDone(sched)"
                    />
                    <q-btn
                      size="10px"
                      unelevated
                      color="grey-3"
                      text-color="grey-7"
                      no-caps
                      @click="viewEvent(sched)"
                      label="View"
                    />
                  </div>
                </div>
              </div>
            </q-card>
          </div>
        </div>
      </div>
      <div class="div" v-else>
        <div
          class="card"
          style="
            height: 100vh;
            width: 350px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-top: -40%;
            box-sizing: border-box;
            text-align: center;
          "
        >
          <q-img
            src="src/assets/undraw_no-data_ig65.svg"
            style="max-width: 90%; height: auto"
          />
          <div style="margin-top: 1.5rem; font-size: 1.5rem; font-weight: 500">
            No Current Event
          </div>
        </div>
      </div>

      <!--Dialog for viewing-->
      <q-dialog v-model="alert">
        <q-card flat bordered class="q-pa-md clean-card">
          <div
            v-for="event in eventContainer"
            :key="event.sched_id"
            class="q-mb-md event-card"
          >
            <q-card-section>
              <div class="event-title">{{ event.event_name }}</div>
              <q-separator spaced />

              <div class="event-detail">
                <span class="label">Client:</span>
                <span class="value">{{ event.name }}</span>
              </div>
              <div class="event-detail">
                <span class="label">Date:</span>
                <span class="value">{{ event.date }}</span>
              </div>
              <div class="event-detail">
                <span class="label">Time:</span>
                <span class="value"
                  >{{ event.time_from }} - {{ event.time_to }}</span
                >
              </div>
              <div class="event-detail">
                <span class="label">Venue:</span>
                <span class="value">{{ event.venue_name }}</span>
              </div>
            </q-card-section>

            <q-card-actions align="right">
              <q-btn flat label="OK" color="primary" v-close-popup />
            </q-card-actions>
          </div>
        </q-card>
      </q-dialog>
    </div>
  </div>
</template>

<script>
import { defineComponent, reactive, watch, ref, onMounted } from "vue";
import { api } from "src/boot/axios";
import gsap from "gsap";
import { useQuasar, SessionStorage } from "quasar";
import { getScheduledIndividualPriest } from "src/composables/getPriestSched";
export default defineComponent({
  name: "priestModule",
  props: {
    schedValue: { type: Array, default: () => [] },
  },
  setup(props) {
    console.log(props.schedValue);
    let alert = ref(false);
    let eventContainer = ref([]);
    let myObject = ref();
    const $q = useQuasar();
    let sessionkey = SessionStorage.getItem("log");
    myObject.value = JSON.parse(sessionkey);
    console.log(myObject.value);
    watch(
      () => props.schedValue,
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
    function viewEvent(payload) {
      alert.value = true;
      let type = payload.etype_id;
      let eventid = payload.event_id;

      api
        .post("viewEvent.php", { eventtype: type, eventid: eventid })
        .then((response) => {
          eventContainer.value = response.data.data;
        })
        .catch((error) => {
          console.log(error);
        });
    }
    function markAsDone(payload) {
      let eventid = payload.event_id;
      $q.dialog({
        title: "Mark as Done",
        message: "Mark this event as done",
        cancel: true,
        persistent: true,
      })
        .onOk(() => {
          api
            .post("markSchedule.php", {
              eventid: eventid,
            })
            .then((response) => {
              if (response.data.msg == "") {
                $q.notify({
                  message: "Updated Succesfully",
                  color: "green-6",
                  position: "bottom-right",
                });

                getScheduledIndividualPriest(myObject.value.account_id);
              } else {
                $q.notify({
                  message: "Update was unsuccesful",
                  color: "red-6",
                  position: "bottom-right",
                });
              }
            })
            .catch((error) => {
              console.log(error);
            });
        })

        .onCancel(() => {
          // console.log('>>>> Cancel')
        });
    }
    function countRemaining(dateFrom, time) {
      // Fixed time: 9:00:00
      const timeFrom = time;

      // build JS Date for the target
      // Build JS Date for the target
      const [h, m, s] = timeFrom.split(":").map((x) => parseInt(x, 10));
      const [Y, M, D] = dateFrom.split("-").map((x) => parseInt(x, 10));
      const target = new Date(Y, M - 1, D, h, m, s);

      // Calculate the difference in seconds
      const now = new Date();
      let diff = Math.floor((target.getTime() - now.getTime()) / 1000);

      if (diff <= 0) {
        return "Start Soon";
      }

      // If the remaining time is more than 1 hour, don't show countdown
      if (diff > 3600) {
        return "";
      }

      // Calculate hours, minutes, and seconds
      const hrs = Math.floor(diff / 3600);
      diff %= 3600;
      const mins = Math.floor(diff / 60);
      const secs = diff % 60;

      return `Starting in : ${hrs}h ${mins}m ${secs}s`;
    }
    return {
      countRemaining,
      formatDate,
      formatTime,
      viewEvent,
      alert,
      eventContainer,
      markAsDone,
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
.clean-card {
  background-color: #ffffff;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.event-card {
  background-color: #f9f9f9;
  border-radius: 8px;
  padding: 16px;
  transition: box-shadow 0.3s ease;
}

.event-card:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.event-title {
  font-size: 1.25rem;
  font-weight: 600;
  margin-bottom: 8px;
}

.event-detail {
  display: flex;
  justify-content: space-between;
  margin-top: 6px;
  font-size: 0.95rem;
}

.label {
  font-weight: 500;
  color: #555;
}

.value {
  font-weight: 400;
  color: #333;
}
</style>
