import { ref, readonly } from "vue";

import { api } from "../boot/axios";

/**
 * This function accepts parameters of an array then
 * set the passed array to unitWork data.
  @param {} object
 */
let Masslist = ref([]);
const loadMasslist = (payload) => {
  return new Promise((resolve, reject) => {
    api
      .get("massesList.php")
      .then((response) => {
        console.log(response);
        if (response.data.Status == "Success") {
          Masslist.value = response.data.data;
        }
      })
      .catch((error) => {
        reject(error);
        $q.notify({
          type: "negative",
          message: "Network Error",
        });
      });
  });
};

export { loadMasslist, Masslist };
