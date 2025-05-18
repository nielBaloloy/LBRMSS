import { ref, readonly } from "vue";
import { api } from "../boot/axios";

let sched = ref([]);
const updateSchedule = () => {
  return new Promise((resolve, reject) => {
    api
      .put("trackScheduledEvents.php")
      .then((response) => {})
      .catch((error) => {
        reject(error);
      });
  });
};

let upcoming = ref([]);
const upcomingSchedule = () => {
  return new Promise((resolve, reject) => {
    api
      .get("upcomingSchedule.php")
      .then((response) => {
        console.log("data", response.data.data);
        upcoming.value = response.data.data;
      })
      .catch((error) => {
        reject(error);
      });
  });
};

export { sched, updateSchedule, upcomingSchedule, upcoming };
