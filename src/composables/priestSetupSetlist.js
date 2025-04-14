import { ref, readonly } from "vue";

import { api } from "../boot/axios";

/**
 * This function accepts parameters of an array then
 * set the passed array to unitWork data.
  @param {} object
 */
let priestList = ref([]);
const loadPriestSetup = (payload) => {
  return new Promise((resolve, reject) => {
    api
      .get("priestSetup.php")
      .then((response) => {
        console.log(response);
        if (response.data.Status == "Success") {
          priestList.value = response.data.data;
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

let posList = ref([]);
const loadPos = (payload) => {
  return new Promise((resolve, reject) => {
    api
      .get("getPosition.php")
      .then((response) => {
        console.log(response);
        if (response.data.Status == "Success") {
          posList.value = response.data.data;
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

export { loadPriestSetup, priestList, posList, loadPos };
