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

// this function gets the pending event (no priest assigned)

let nopriestEvent = ref([]);
const getNopriestEvent = () => {
  return new Promise((resolve, reject) => {
    api
      .get("ios_get_event_pending.php")
      .then((response) => {
        nopriestEvent.value = response.data.data;
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
  getNopriestEvent,
  nopriestEvent,
};
