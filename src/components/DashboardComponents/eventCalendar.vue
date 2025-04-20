<template>
  <div class="service row">
    <q-card class="my-card" style="width: 100vw" flat>
      <div class="q-gutter-md q-pa-md">
        <p class="text-subtitle1">Available Priest</p>

        <div
          v-for="priest in cardValue"
          :key="priest.priest_id"
          class="col-xs-12 col-sm-6 col-md-4"
        >
          <q-card class="q-pa-md rounded-borders bg-grey-2" flat>
            <div class="row items-center no-wrap q-gutter-md">
              <!-- Avatar -->
              <q-avatar size="64px" class="bg-grey-2" text-color="amber-5">
                <q-icon name="account_circle" size="50px" />
              </q-avatar>
              <!-- Info -->
              <div class="column">
                <div class="text-subtitle1 text-weight-medium">
                  {{ priest.priest_name }}
                </div>
              </div>
            </div>
          </q-card>
        </div>
      </div>
    </q-card>
  </div>
</template>

<script>
import { defineComponent, reactive, watch, ref, onMounted } from "vue";
import gsap from "gsap";

export default defineComponent({
  name: "EventCard",
  props: {
    cardValue: { type: Array, default: () => [] },
  },
  setup(props) {
    onMounted(() => {
      console.log("Mounted value:", props.cardValue);
    });

    // Watch for changes
    watch(
      () => props.cardValue,
      (newVal) => {
        console.log("Updated value:", newVal);
      },
      { immediate: true, deep: true }
    );

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
  margin-top: 1em;
  height: 500px;
}
.count {
  font-weight: bold;
}
</style>
