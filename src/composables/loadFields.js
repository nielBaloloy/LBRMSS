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

const LoginPayload = (payload) => {
  return new Promise((resolve, reject) => {
    api
      .post("login.php", payload)
      .then((response) => {
        status.value = response.data.Status;
        if (status.value == "Success") {
          account.value = response.data.loginData;
          position.value = response.data.loginData.AccessLvl;
          isActive.value = response.data.loginData.isActive;
        }
        if (status.value == "Failed") {
          account.value = "";
          position.value = "";
          isActive.value = "0";
        }
      })
      .catch((error) => {
        reject(error);
      });
  });
};

export { LoginPayload, account, status, position, isActive };
