import { ref, readonly } from "vue";

import { api } from "../boot/axios";

const overall = ref([]);
const frecord = ref([]);
/**
 * This function accepts parameters of an array then
 * set the passed array to unitWork data.
  @param {} object
 */

const getFinanceRecord = () => {
  return new Promise((resolve, reject) => {
    api
      .get("financialRecord.php")
      .then((response) => {
        if (response.data.Status == "Success") {
          console.log(response.data);
          frecord.value = response.data.data;
          overall.value = response.data.overall;
        } else {
          overall.value = [];
          frecord.value = [];
        }
      })
      .catch((error) => {
        reject(error);
      });
  });
};

export { getFinanceRecord, overall, frecord };
