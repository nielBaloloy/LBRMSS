import { ref, readonly } from "vue";
import { api } from "../boot/axios";

let scheduleToday = ref([]);
const getToday = () => {
  return new Promise((resolve, reject) => {
    api
      .get("todaySchedule.php")
      .then((response) => {
        scheduleToday.value = response.data.data;
      })
      .catch((error) => {
        reject(error);
      });
  });
};

export { getToday, scheduleToday };
