<template>
  <div class="service row">
    <div class="col-sm">
      <label class="text-h6">Upcoming Event</label>
      <div class="sched" v-if="schedValue.length > 0">
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
              <q-avatar size="30" text-color="green">
                <q-icon name="event_note" size="40px" />
              </q-avatar>
              <!-- Info -->
              <div class="column">
                <div class="text-subtitle2 text-weight-medium">
                  {{ sched.service }}
                </div>
                <div class="text-subtitle2 text-color='grey-3'">
                  <div class="client" v-if="sched.title == 'N/A'"></div>
                  <div class="client" v-else>
                    {{ sched.title }}
                  </div>
                </div>
                <div class="text-subtitle2 text-weight-light">
                  {{ sched.venue }}
                </div>
                <div class="text-subtitle2 text-weight-light">
                  {{ formatDate(sched.date) }},

                  {{ formatTime(sched.time_from) }} -
                  {{ formatTime(sched.time_to) }}
                </div>
                <div class="view q-pt-md q-gutter-sm">
                  <q-btn
                    size="10px"
                    unelevated
                    color="green-5"
                    label="Take this Schedule"
                    no-caps
                    @click="takeSchedule(sched)"
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
        <q-card>
          <div
            v-for="event in eventContainer"
            :key="event.sched_id"
            class="col-xs-12 col-sm-12 col-md-12 q-mt-md"
          >
            <q-card-section>
              <div class="text-h6">{{ event.title }}</div>
              <q-separator></q-separator>

              <div class="div">
                Client :
                <div class="text-h6">{{ event.event_name }}</div>
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

export default defineComponent({
  name: "priestModule",
  props: {
    schedValue: { type: Array, default: () => [] },
  },
  setup(props) {
    const $q = useQuasar();
    let sessionkey = SessionStorage.getItem("log");
    let UserData = JSON.parse(sessionkey);
    console.log("Session", UserData);
    let alert = ref(false);
    let eventContainer = ref([]);

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
    function takeSchedule(sched) {
      let personId = UserData.person_id;
      let event = sched.all;
      console.log(event);
      $q.dialog({
        title: "Take this Schedule?",
        message: "Would you like to take this Schedule?",
        cancel: true,
        persistent: true,
      })
        .onOk(() => {
          api
            .post("ios_take_schedule.php", {
              priestId: personId,
              event: event,
            })
            .then((response) => {
              console.log(response.data.data);
            })
            .catch((error) => {
              console.log(error);
            });
        })
        .onCancel(() => {
          console.log(">>>> Cancel");
        });
    }
    return {
      formatDate,
      formatTime,
      viewEvent,
      alert,
      eventContainer,
      takeSchedule,
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
