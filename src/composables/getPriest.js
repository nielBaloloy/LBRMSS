import { ref, readonly } from "vue";

import { api } from "../boot/axios";

const serviceList = ref([]);
const position = ref();
const account = ref([]);
const isActive = ref();

/**
 * This function accepts parameters of an array then
 * set the passed array to unitWork data.
  @param {} object
 */
let availablePriest = ref([]);
//this function will get the available preist based on the event date and time
const getAvailablePriest = (eventDate) => {
  console.log(eventDate);
  return new Promise((resolve, reject) => {
    api
      .get("loadPriest.php", { params: { clientSchedule: eventDate } })
      .then((response) => {
        if (response.data.Status == "Success") {
          console.log(response.data.availablePriest);
          availablePriest.value = response.data.availablePriest;
        } else {
          console.log(response.data.availablePriest);
          availablePriest.value = [];
        }
      })
      .catch((error) => {
        reject(error);
      });
  });
};

let getAvailablePriestV2 = ref([]);

const getAvailablePriestver2 = (date, timefrom, timeto) => {
  return new Promise((resolve, reject) => {
    api
      .get("loadPriestv2.php", {
        params: {
          date: date,
          timefrom: timefrom,
          timeto: timeto,
        },
      })
      .then((response) => {
        if (response.data.Status == "Success") {
          console.log(response.data.availablePriest);
          getAvailablePriestV2.value = response.data.availablePriest;
        } else {
          console.log(response.data.availablePriest);
          getAvailablePriestV2.value = [];
        }
      })
      .catch((error) => {
        reject(error);
      });
  });
};

export {
  getAvailablePriest,
  availablePriest,
  getAvailablePriestver2,
  getAvailablePriestV2,
};
