import { ref, readonly } from "vue";

import { api } from "../boot/axios";

/**
 * This function accepts parameters of an array then
 * set the passed array to unitWork data.
  @param {} object
 */
let financeData = ref([]);
const loadFinanceData = (payload) => {
  return new Promise((resolve, reject) => {
    api
      .get("graphMain.php")
      .then((response) => {
        console.log(response);
        if (response.data.Status == "Success") {
          financeData.value = response.data.data;
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

export { financeData, loadFinanceData };
