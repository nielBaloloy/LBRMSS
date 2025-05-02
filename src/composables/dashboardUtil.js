import { ref, readonly } from "vue";

import { api } from "../boot/axios";

const pending = ref([]);
const scheduled = ref([]);
const done = ref([]);
/**
 * This function accepts parameters of an array then
 * set the passed array to unitWork data.
  @param {} object
 */

const dashboardUtil = (serviceId) => {
  return new Promise((resolve, reject) => {
    api
      .get("dashbooardCounter.php")
      .then((response) => {
        if (response.data.Status == "Success") {
          console.log(response.data);
          pending.value = response.data.pending;
          scheduled.value = response.data.scheduled;
          done.value = response.data.done;
        } else {
          console.log(response.data.data);
        }
      })
      .catch((error) => {
        reject(error);
      });
  });
};

export { dashboardUtil, pending, scheduled, done };
