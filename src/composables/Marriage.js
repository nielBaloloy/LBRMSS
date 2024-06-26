import { ref, readonly } from "vue";
import { api } from "../boot/axios";


/**
 * This function accepts parameters of an array then
 * set the passed array to unitWork data.
  @param {} object
 */


// declarations
let Marriage_PendingData =ref([]);
let msg = ref();
let msgColor =ref();
const sendMarriageData = ( event_Payload,ServicePayload) => {

  return new Promise((resolve, reject) => {
    api.post("MarriageAPI.php", {eventData:event_Payload,MarriageData:ServicePayload})
      .then((response) => {
        if(response.data.Status == "Success"){
          Marriage_PendingData.value = response.data.Pending;
          msg.value = "Application Submitteds"
            msgColor.value = "green-5"
        }else{
           msg.value = "It's not you , its me"
           msgColor.value = "red-5"
        }

       console.log(Marriage_PendingData.value,msgColor.value)
      })
      .catch((error) => {
         msg.value = "an error occured"
          msgColor.value = "red-5"
        reject(error);
      });
  });
};

export {sendMarriageData,msg,Marriage_PendingData,msgColor};
