import { ref, readonly } from "vue";
import { api } from "../boot/axios";

let BaptismData = ref([]);
const getBaptism = () => {
  return new Promise((resolve, reject) => {
    api
      .get("BaptismAPI.php")
      .then((response) => {
        BaptismData.value = response.data.data;
      })
      .catch((error) => {
        reject(error);
      });
  });
};

export { getBaptism, BaptismData };
