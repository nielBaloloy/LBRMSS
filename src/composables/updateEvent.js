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

export { sched, updateSchedule };
