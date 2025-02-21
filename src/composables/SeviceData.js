import { ref, readonly } from "vue";
import { api } from "../boot/axios";
import {} from "./Marriage";

/**
 * This function accepts parameters of an array then
 * set the passed array to unitWork data.
  @param {} object
 */

// declarations
let Data = ref([]);

// this promise will get all the pending service from all table
const getSerivce = (load) => {
  console.log(load);
  return new Promise((resolve, reject) => {
    api
      .get("Service.php", { params: { type: load } })
      .then((response) => {
        if (response.data.Status == "Success") {
          Data.value = response.data.data;
        } else {
          Data.value = response.data.data;
        }
      })
      .catch((error) => {
        reject(error);
      });
  });
};

export { getSerivce, Data };
