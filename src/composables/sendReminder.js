import { ref, readonly } from "vue";
import { api } from "../boot/axios";

let remind = ref([]);
const remindClient = () => {
  return new Promise((resolve, reject) => {
    api
      .get("alertEvent.php")
      .then((response) => {})
      .catch((error) => {
        reject(error);
      });
  });
};

export { remind, remindClient };
