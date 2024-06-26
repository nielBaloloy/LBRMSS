import { ref, readonly } from "vue";
import { api } from "../boot/axios";


/**
 * This function accepts parameters of an array then
 * set the passed array to unitWork data.
  @param {} object
 */


// declarations
let PendingData =ref([]);

// this promise will get all the pending service from all table 
const getpendingSerivce = () => {

  return new Promise((resolve, reject) => {
    api.get("Service.php",{ params: { type: "pending" } })
      .then((response) => {
       
      })
      .catch((error) => {
         msg.value = "an error occured"
          msgColor.value = "red-5"
        reject(error);
      });
  });
};

export {getpendingSerivce,};
