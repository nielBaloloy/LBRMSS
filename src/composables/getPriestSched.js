import { ref, readonly } from "vue";
import { api } from "../boot/axios";

let schedulePriest = ref([]);
const getScheduledPriest = () => {
  return new Promise((resolve, reject) => {
    api
      .get("priestScheduleList.php")
      .then((response) => {
        schedulePriest.value = response.data;
      })
      .catch((error) => {
        reject(error);
      });
  });
};

export { getScheduledPriest, schedulePriest };
