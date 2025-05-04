<template>
  <div class="q-pa-sm row items-start q-gutter-md">
    <q-card
      class="my-card bg-white hover-card"
      flat
      bordered
      data-aos="fade-up"
      data-aos-duration="100"
    >
      <q-card-section>
        <div class="CONTENT row justify-between">
          <p class="text-h6">Scheduled Events</p>
          <span class="count text-h1 text-warning">
            {{ scheduledTween.number.toFixed(0) }}
          </span>
        </div>
      </q-card-section>
    </q-card>

    <q-card
      class="my-card bg-white hover-card"
      flat
      bordered
      data-aos="fade-up"
      data-aos-duration="200"
    >
      <q-card-section>
        <div class="CONTENT row justify-between">
          <p class="text-h6">Pending Events</p>
          <span class="count text-h1 text-warning">
            {{ pendingTween.number.toFixed(0) }}
          </span>
        </div>
      </q-card-section>
    </q-card>

    <q-card
      class="my-card bg-white hover-card"
      flat
      bordered
      data-aos="fade-up"
      data-aos-duration="300"
    >
      <q-card-section>
        <div class="CONTENT row justify-between">
          <p class="text-h6">Completed Events</p>
          <span class="count text-h1 text-warning">
            {{ completedTween.number.toFixed(0) }}
          </span>
        </div>
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
import {
  defineComponent,
  reactive,
  watch,
  ref,
  onMounted,
  nextTick,
} from "vue";
import gsap from "gsap";
import {
  dashboardUtil,
  pending,
  scheduled,
  done,
} from "src/composables/dashboardUtil";
import AOS from "aos";
import "aos/dist/aos.css";
import { api } from "src/boot/axios";
export default defineComponent({
  name: "EventCard",
  props: {
    pending: { type: Number, default: 0 },
    scheduled: { type: Number, default: 0 },
    done: { type: Number, default: 0 },
  },
  setup(props) {
    AOS.init();
    // Internal state refs
    const scheduledVal = ref(0);
    const pendingVal = ref(0);
    const completedVal = ref(0);

    const scheduledTween = reactive({ number: 0 });
    const pendingTween = reactive({ number: 0 });
    const completedTween = reactive({ number: 0 });

    watch(
      scheduledVal,
      (n) => {
        gsap.to(scheduledTween, { duration: 1, number: n || 0 });
      },
      { immediate: true }
    );

    watch(
      pendingVal,
      (n) => {
        gsap.to(pendingTween, { duration: 1, number: n || 0 });
      },
      { immediate: true }
    );

    watch(
      completedVal,
      (n) => {
        gsap.to(completedTween, { duration: 1, number: n || 0 });
      },
      { immediate: true }
    );

    onMounted(() => {
      api
        .get("dashbooardCounter.php")
        .then((response) => {
          if (response.data.Status == "Success") {
            scheduledVal.value = response.data.scheduled;
            pendingVal.value = response.data.pending;
            completedVal.value = response.data.complete;
          } else {
            console.log(response.data.data);
          }
        })
        .catch((error) => {
          reject(error);
        });
    });

    // Expose data for template
    return {
      scheduledTween,
      pendingTween,
      completedTween,
    };
  },
});
</script>

<style scoped>
.my-card {
  margin-top: 2em;
  width: 31%;
  min-width: 20%;
  height: 140px;
}
.count {
  font-weight: bold;
}
.hover-card {
  transition: background-color 0.3s ease, color 0.3s ease;
  cursor: pointer;
}

.hover-card .count {
  transition: color 0.3s ease;
}

.hover-card:hover {
  background-color: #fdc542 !important; /* Amber */
  color: white !important;
}

.hover-card:hover .count {
  color: white !important;
}
</style>
