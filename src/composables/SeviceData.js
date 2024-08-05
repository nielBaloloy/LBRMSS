import { ref, readonly } from "vue";
import { api } from "../boot/axios";
import {} from "./Marriage";

/**
 * This function accepts parameters of an array then
 * set the passed array to unitWork data.
  @param {} object
 */

// declarations
let PendingData = ref([]);
let ScheduledData = ref([]);
let DoneData = ref([]);
// this promise will get all the pending service from all table
const getSerivce = (load) => {
  console.log(load);
  return new Promise((resolve, reject) => {
    api
      .get("Service.php", { params: { type: "pending" } })
      .then((response) => {
        let wrapper = response.data.Pending;

        // filter for pending service
        if ((load = "Pending")) {
          PendingData.value = wrapper
            .filter((res) => res.EventProgress == "Pending")
            .reverse();
          console.log(PendingData.value);
        }
        if ((load = "Scheduled")) {
          ScheduledData.value = wrapper
            .filter((res) => res.EventProgress == "Scheduled")
            .reverse();
          console.log(ScheduledData.value);
        }
        if ((load = "Done")) {
          DoneData.value = wrapper
            .filter((res) => res.EventProgress == "Done")
            .reverse();
          console.log(DoneData.value);
        }
      })
      .catch((error) => {
        msg.value = "an error occured";
        msgColor.value = "red-5";
        reject(error);
      });
  });
};

export { getSerivce, PendingData, ScheduledData, DoneData };
