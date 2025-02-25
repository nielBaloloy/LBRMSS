<template>
  <div class="main-container q-pa-md">
    <div class="title-dashboard q-mb-md">Dashboard</div>
    <div class="header flex">
      <div class="title-text text-grey-6 q-pa-md">
        Daily Transaction Records
      </div>
    </div>
    <div class="transactions-container">
      <div class="title-card q-pa-md">
        <div class="total flex flex-center">
          <div class="text-total text-weight-medium">Php. 100</div>
        </div>
        <div class="desc flex flex-center">Total Earnings</div>
      </div>
      <q-scroll-area style="height: 215px; width: 100%">
        <div class="transactions-container q-pa-xs row no-wrap">
          <q-card flat class="card-finance q-pa-sm" style="width: 200px">
            <div class="total flex flex-center">
              <div class="text-total">0</div>
            </div>
            <div class="desc flex flex-center">Confirmation Services</div>
          </q-card>
          <q-card flat class="card-finance q-pa-sm" style="width: 200px">
            <div class="total flex flex-center">
              <div class="text-total">0</div>
            </div>
            <div class="desc flex flex-center">Baptismal Services</div>
          </q-card>
          <q-card flat class="card-finance q-pa-sm" style="width: 200px"
            ><div class="total flex flex-center">
              <div class="text-total">1</div>
            </div>
            <div class="desc flex flex-center">Marriage Services</div></q-card
          >
          <q-card flat class="card-finance q-pa-sm" style="width: 200px">
            <div class="total flex flex-center">
              <div class="text-total">0</div>
            </div>
            <div class="desc flex flex-center">Funeral Services</div>
          </q-card>
          <q-card flat class="card-finance q-pa-sm" style="width: 200px">
            <div class="total flex flex-center">
              <div class="text-total">0</div>
            </div>
            <div class="desc flex flex-center">Other Services</div>
          </q-card>
        </div>
      </q-scroll-area>
    </div>
    <div class="CHARTCONTAINER q-pa-lg" style="width: 100%">
      DAILY GRAPH
      <canvas ref="canvasRef" id="myChart"></canvas>
    </div>
    <!-- daily record table -->
  </div>
</template>
<script>
import { defineComponent, ref, onMounted } from "vue";
import { Chart } from "chart.js";
import { useQuasar, SessionStorage } from "quasar";
import { useRouter } from "vue-router";

export default defineComponent({
  setup() {
    const $q = useQuasar();
    const router = useRouter();
    const canvasRef = ref(null);
    let barchart = null;

    const logout = () => {
      $q.dialog({
        title: "",
        message: "Are you sure you want to logout?",
        cancel: true,
        persistent: true,
      }).onOk(() => {
        SessionStorage.clear();
        router.push({ path: "/" });
      });
    };

    const chart = () => {
      if (!canvasRef.value) return;

      barchart = new Chart(canvasRef.value, {
        type: "bar",
        data: {
          labels: ["Marriage", "Baptism", "Confirmation", "Burial", "Others"],
          datasets: [
            {
              label: "Certificate",
              data: [1, 0, 0, 0, 0],
              backgroundColor: "blue",
            },
            {
              label: "Ceremony",
              data: [1, 1, 0, 0, 0],
              backgroundColor: "green",
            },
            {
              label: "Anointing of the Sick",
              data: [0, 0, 0, 0, 0],
            },
            {
              label: "Blessing",
              data: [0, 0, 0, 0, 0],
              backgroundColor: "purple",
            },
            {
              label: "Mass Intention",
              data: [0, 0, 0, 0, 0],
              backgroundColor: "orange",
            },
            {
              label: "Thanksgiving",
              data: [0, 0, 0, 0, 0],
              backgroundColor: "yellow",
            },
            { label: "Permit", data: [0, 0, 0, 0, 0], backgroundColor: "pink" },
          ],
        },
        options: {
          animation: {
            duration: 2000,
            easing: "easeOutBounce",
          },
          scales: {
            x: { stacked: true },
            y: { stacked: true, beginAtZero: true },
          },
        },
      });
    };

    onMounted(() => {
      chart();
    });

    return {
      logout,
      canvasRef,
    };
  },
});
</script>
