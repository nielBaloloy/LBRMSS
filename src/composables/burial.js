import { ref, readonly } from "vue";
import { api } from "../boot/axios";
import {getSerivce} from "./SeviceData"
/**
 * This function accepts parameters of an array then
 * set the passed array to unitWork data.
  @param {} object
 */


// declarations

let BU_msg = ref();
let BU_msgColor =ref();
const send_burial_data = (event_Payload,ServicePayload) => {
  
    return new Promise((resolve, reject) => {

         api.post("burial.php", {eventData:event_Payload,BurialData:ServicePayload})
         .then((response) => {
           if(response.data.Status == "Success"){
            BU_msg.value = "Application Submitted"
            BU_msgColor.value = "green-5"
            console.log( BU_msg.value);
           }else{
            BU_msg.value = "It's not you , its me"
            BU_msgColor.value = "red-5"
           }
           getSerivce("pending");
         
         })
            .catch((error) => {
              BU_msg.value = "an error occured"
                BU_msgColor.value = "red-5"
            reject(error);
            });
        
       });
};

export {BU_msgColor,BU_msg,send_burial_data};
