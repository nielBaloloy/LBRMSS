<template>
  <q-page class="q-pa-md">
    <q-card>
      <q-card-section>
        <div class="text-h6">Church Services Statistics</div>
      </q-card-section>
      <q-card-section>
        <canvas ref="chartCanvas"></canvas>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script>
import { defineComponent, onMounted, ref } from "vue";
import { Chart, registerables } from "chart.js";

Chart.register(...registerables);

export default defineComponent({
  setup() {
    const chartCanvas = ref(null);

    onMounted(() => {
      new Chart(chartCanvas.value, {
        type: "bar",
        data: {
          labels: ["Marriage", "Baptism", "Burial", "Confession"], // Services on X-axis
          datasets: [
            {
              label: "Ceremonies",
              data: [15, 30, 25, 10], // Number of ceremonies per service
              backgroundColor: "rgba(54, 162, 235, 0.7)", // Blue
              borderColor: "rgba(54, 162, 235, 1)",
              borderWidth: 1,
            },
            {
              label: "Certificates",
              data: [10, 20, 15, 8], // Number of certificates per service
              backgroundColor: "rgba(255, 99, 132, 0.7)", // Red
              borderColor: "rgba(255, 99, 132, 1)",
              borderWidth: 1,
            },
          ],
        },
        options: {
          responsive: true,
          scales: {
            x: {
              stacked: true, // Stack bars horizontally
            },
            y: {
              stacked: true, // Stack bars vertically
              beginAtZero: true,
              title: {
                display: true,
                text: "Count",
                font: { size: 14 },
              },
            },
          },
          plugins: {
            legend: {
              display: true,
              position: "top",
            },
          },
        },
      });
    });

    return { chartCanvas };
  },
});
</script>
