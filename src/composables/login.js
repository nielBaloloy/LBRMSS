import { ref, readonly } from "vue";

import { api } from "../boot/axios";

const status = ref();
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
          account.value = response.data.loginData[0];

          position.value = response.data.loginData[0].accessLvl;

          isActive.value = response.data.loginData[0].isActive;
        }
        if (status.value == "Failed") {
          account.value = "";
          position.value = "";
          isActive.value = "0";
        }
      })
      .catch((error) => {
        setTimeout(() => {
          Swal.fire({
            title: "Unable to connect!",
            text: "Login request failed due to a server error. Please contact support. ",
            error,
            icon: "error",
          });
        }, 2000);
      });
  });
};

export { LoginPayload, account, status, position, isActive };
