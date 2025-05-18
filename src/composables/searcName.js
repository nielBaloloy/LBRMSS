import { ref, readonly } from "vue";

import { api } from "../boot/axios";

/**
 * This function accepts parameters of an array then
 * set the passed array to unitWork data.
  @param {} object
 */
let names = ref([]);
const loadSearch = (payload) => {
  return new Promise((resolve, reject) => {
    api
      .get("documentaryCertificate.php", { params: { data: payload } })
      .then((response) => {
        console.log(response);
        if (response.data.Status == "Success") {
          names.value = response.data.data;
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

export { loadSearch, names };
