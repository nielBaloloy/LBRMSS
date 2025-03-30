import { ref, readonly } from "vue";

import { api } from "../boot/axios";

const pendingService_Marriage = ref([]);

/**
 * This function accepts parameters of an array then
 * set the passed array to unitWork data.
  @param {} object
 */

const pendingCounter_Marriage = (serviceId) => {
  return new Promise((resolve, reject) => {
    api
      .get("pendingServiceCounter.php", {
        params: { service: serviceId },
      })
      .then((response) => {
        if (response.data.Status == "Success") {
          console.log(response.data.data);
          pendingService_Marriage.value = response.data.data[0].counter;
        } else {
          console.log(response.data.data);
          pendingService_Marriage.value = [];
        }
      })
      .catch((error) => {
        reject(error);
      });
  });
};

export { pendingCounter_Marriage, pendingService_Marriage };
