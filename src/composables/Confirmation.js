import { ref, readonly } from "vue";
import { api } from "../boot/axios";

/**
 * This function accepts parameters of an array then
 * set the passed array to unitWork data.
  @param {} object
 */
let msgs = ref();
let msgColors = ref();
const sendConfirmationData = (event_Payload, ServicePayload) => {
  return new Promise((resolve, reject) => {
    api
      .post("Confirmation.php", {
        eventData: event_Payload,
        ConfirmationData: ServicePayload,
      })
      .then((response) => {
        if (response.data.Status == "Success") {
          msgs.value = "Application Submitteds";
          msgColors.value = "green-5";
        } else {
          msgs.value = "It's not you , its me";
          msgColors.value = "red-5";
        }
        console.log(msgs.value);
      })
      .catch((error) => {
        reject(error);
      });
  });
};

let ConfirmationData = ref([]);
const getConfirmation = () => {
  return new Promise((resolve, reject) => {
    api
      .get("Confirmation.php")
      .then((response) => {
        ConfirmationData.value = response.data.data;
      })
      .catch((error) => {
        reject(error);
      });
  });
};

export {
  sendConfirmationData,
  msgs,
  msgColors,
  ConfirmationData,
  getConfirmation,
};
