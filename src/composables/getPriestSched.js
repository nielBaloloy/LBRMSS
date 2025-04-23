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

let scheduleOnePriest = ref([]);
const getScheduledIndividualPriest = (accountId) => {
  return new Promise((resolve, reject) => {
    api
      .post("priestScheduleListOne.php", { acc: accountId })
      .then((response) => {
        scheduleOnePriest.value = response.data.data;
        console.log(schedulePriest.value);
      })
      .catch((error) => {
        reject(error);
      });
  });
};

export {
  getScheduledPriest,
  schedulePriest,
  getScheduledIndividualPriest,
  scheduleOnePriest,
};
