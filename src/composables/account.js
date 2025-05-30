import { ref, readonly } from "vue";

import { api } from "../boot/axios";

/**
 * This function accepts parameters of an array then
 * set the passed array to unitWork data.
  @param {} object
 */
let account = ref([]);
const loadAccount = (payload) => {
  return new Promise((resolve, reject) => {
    api
      .get("Signup.php")
      .then((response) => {
        console.log(response);
        if (response.data.Status == "Success") {
          account.value = response.data.data;
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

const CreateAccount = (payload) => {
  return new Promise((resolve, reject) => {
    api
      .post("Signup.php", { account: payload })
      .then((response) => {
        console.log(response);
        if (response.data.Status == "Success") {
          account.value = response.data.data;
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
export { loadAccount, account };
