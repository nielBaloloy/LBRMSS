import { ref, readonly } from "vue";
import { api } from "../boot/axios";
import { getSerivce } from "./SeviceData";
/**
 * This function accepts parameters of an array then
 * set the passed array to unitWork data.
  @param {} object
 */

// declarations

let OS_msg = ref();
let OS_msgColor = ref();
const send_Annointing_Data = (ServicePayload) => {
  return new Promise((resolve, reject) => {
    api
      .post("Service.php", { eventData: ServicePayload })
      .then((response) => {
        if (response.data.Status == "Success") {
          OS_msg.value = "Application Submitted";
          OS_msgColor.value = "green-5";
          console.log(OS_msg.value);
        } else {
          OS_msg.value = "It's not you , its me";
          OS_msgColor.value = "red-5";
        }
        getSerivce("pending");
      })
      .catch((error) => {
        OS_msg.value = "an error occured";
        OS_msgColor.value = "red-5";
        reject(error);
      });
  });
};

const send_blessing = (ServicePayload) => {
  return new Promise((resolve, reject) => {
    api
      .post("BlessingAPI.php", { eventData: ServicePayload })
      .then((response) => {
        if (response.data.Status == "Success") {
          OS_msg.value = "Application Submitted";
          OS_msgColor.value = "green-5";
          console.log(OS_msg.value);
        } else {
          OS_msg.value = "It's not you , its me";
          OS_msgColor.value = "red-5";
        }
        getSerivce("pending");
      })
      .catch((error) => {
        OS_msg.value = "an error occured";
        OS_msgColor.value = "red-5";
        reject(error);
      });
  });
};

const send_mass = (ServicePayload) => {
  return new Promise((resolve, reject) => {
    api
      .post("MassAPI.php", { eventData: ServicePayload })
      .then((response) => {
        if (response.data.Status == "Success") {
          OS_msg.value = "Application Submitted";
          OS_msgColor.value = "green-5";
          console.log(OS_msg.value);
        } else {
          OS_msg.value = "It's not you , its me";
          OS_msgColor.value = "red-5";
        }
        getSerivce("pending");
      })
      .catch((error) => {
        OS_msg.value = "an error occured";
        OS_msgColor.value = "red-5";
        reject(error);
      });
  });
};
export { OS_msgColor, OS_msg, send_Annointing_Data, send_blessing, send_mass };
