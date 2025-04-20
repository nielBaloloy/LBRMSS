<template>
  <div class="q-pa-sm row items-start q-gutter-md">
    <q-card class="my-card bg-white" flat bordered>
      <q-card-section>
        <div class="CONTENT row justify-between">
          <p class="text-h6">Scheduled Events</p>
          <span class="count text-h2 text-warning">
            {{ scheduledTween.number.toFixed(0) }}
          </span>
        </div>
      </q-card-section>
    </q-card>

    <q-card class="my-card bg-white" flat bordered>
      <q-card-section>
        <div class="CONTENT row justify-between">
          <p class="text-h6">Pending Events</p>
          <span class="count text-h2 text-warning">
            {{ pendingTween.number.toFixed(0) }}
          </span>
        </div>
      </q-card-section>
    </q-card>

    <q-card class="my-card bg-white" flat bordered>
      <q-card-section>
        <div class="CONTENT row justify-between">
          <p class="text-h6">Completed Events</p>
          <span class="count text-h2 text-warning">
            {{ completedTween.number.toFixed(0) }}
          </span>
        </div>
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
import { defineComponent, reactive, watch, ref, onMounted } from "vue";
import gsap from "gsap";

export default defineComponent({
  name: "EventCard",
  setup() {
    const scheduled = ref(0);
    const pending = ref(0);
    const completed = ref(0);

    const scheduledTween = reactive({ number: 0 });
    const pendingTween = reactive({ number: 0 });
    const completedTween = reactive({ number: 0 });

    watch(scheduled, (n) => {
      gsap.to(scheduledTween, { duration: 1, number: n || 0 });
    });

    watch(pending, (n) => {
      gsap.to(pendingTween, { duration: 1, number: n || 0 });
    });

    watch(completed, (n) => {
      gsap.to(completedTween, { duration: 1, number: n || 0 });
    });

    // Trigger animation by setting values on mount
    onMounted(() => {
      scheduled.value = 50;
      pending.value = 20;
      completed.value = 75;
    });

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
</style>
