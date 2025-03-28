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
let EventListData = ref([]);
let msg = ref();
let msgColor = ref();
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

const sendEventCeremonyData = (eventType, event_Payload, ServicePayload) => {
  return new Promise((resolve, reject) => {
    if (event_Payload.Service === "1") {
      api
        .post("MarriageAPI.php", {
          eventData: event_Payload,
          MarriageData: ServicePayload,
        })
        .then((response) => {
          if (response.data.Status == "Success") {
            msg.value = "Application Submitted";
            msgColor.value = "green-5";
            getSerivce(0);
          } else {
            msg.value = "It's not you, its me";
            msgColor.value = "red-5";
          }

          console.log(msg.value, msgColor.value);
        })
        .catch((error) => {
          msg.value = "an error occured";
          msgColor.value = "red-5";
          reject(error);
        });
    } else if (event_Payload.Service === "2") {
      api
        .post("BaptismApi.php", {
          eventData: event_Payload,
          BaptismData: ServicePayload,
        })
        .then((response) => {
          if (response.data.Status == "Success") {
            msg.value = "Application Submitteds";
            msgColor.value = "green-5";
            getSerivce(0);
          } else {
            msg.value = "It's not you , its me";
            msgColor.value = "red-5";
          }

          console.log(msg.value, msgColor.value);
        })
        .catch((error) => {
          msg.value = "an error occured";
          msgColor.value = "red-5";
          reject(error);
        });
    }
  });
};
const FilterRange = (filterpayload) => {
  console.log(filterpayload);
  api
    .get("Service.php", { params: { filter: filterpayload } })
    .then((response) => {
      if (response.data.Status == "Success") {
        Data.value = response.data.data;
        getSerivce(0);
      } else {
        Data.value = response.data.data;
      }
    })
    .catch((error) => {
      msg.value = "an error occured";
      msgColor.value = "red-5";
      reject(error);
    });
};

const countPendingPayment = () => {
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

/** Finance Get Pending Request
 * Pending , Paid
 */

const PaymentList = ref([]);
const paymentSetList = (load) => {
  console.log(load);
  return new Promise((resolve, reject) => {
    api
      .get("financial_page_service_request.php", { params: { type: load } })
      .then((response) => {
        if (response.data.Status == "Success") {
          PaymentList.value = response.data.data;
        } else {
          PaymentList.value = response.data.data;
        }
      })
      .catch((error) => {
        reject(error);
      });
  });
};

export {
  getSerivce,
  Data,
  sendEventCeremonyData,
  msg,
  msgColor,
  FilterRange,
  paymentSetList,
  PaymentList,
};
