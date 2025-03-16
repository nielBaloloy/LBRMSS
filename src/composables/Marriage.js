import { ref, readonly } from "vue";
import { api } from "../boot/axios";
import { getSerivce } from "./SeviceData";

/**
 * This function accepts parameters of an array then
 * set the passed array to unitWork data.
  @param {} object
 */

// declarations
let Marriage_PendingData = ref([]);
let msg = ref();
let msgColor = ref();
const sendMarriageData = (event_Payload, ServicePayload) => {
  return new Promise((resolve, reject) => {
    if (event_Payload.Service === "Marriage") {
      api
        .post("MarriageAPI.php", {
          eventData: event_Payload,
          MarriageData: ServicePayload,
        })
        .then((response) => {
          if (response.data.Status == "Success") {
            msg.value = "Application Submitteds";
            msgColor.value = "green-5";
          } else {
            msg.value = "It's not you, its me";
            msgColor.value = "red-5";
          }
          getSerivce("pending");
          console.log(Marriage_PendingData.value, msgColor.value);
        })
        .catch((error) => {
          msg.value = "an error occured";
          msgColor.value = "red-5";
          reject(error);
        });
    }
    if (event_Payload.Service === "Baptism") {
      api
        .post("BaptismApi.php", {
          eventData: event_Payload,
          BaptismData: ServicePayload,
        })
        .then((response) => {
          if (response.data.Status == "Success") {
            Marriage_PendingData.value = response.data.Pending;
            msg.value = "Application Submitteds";
            msgColor.value = "green-5";
          } else {
            msg.value = "It's not you , its me";
            msgColor.value = "red-5";
          }

          console.log(Marriage_PendingData.value, msgColor.value);
        })
        .catch((error) => {
          msg.value = "an error occured";
          msgColor.value = "red-5";
          reject(error);
        });
    }
  });
};

//================== get DONE Marriage  ===========================
let MarraigeData = ref([]);
const getMarriage = () => {
  return new Promise((resolve, reject) => {
    api
      .get("MarriageAPI.php")
      .then((response) => {
        MarraigeData.value = response.data.data;
      })
      .catch((error) => {
        reject(error);
      });
  });
};

export { sendMarriageData, getMarriage, msg, msgColor, MarraigeData };
