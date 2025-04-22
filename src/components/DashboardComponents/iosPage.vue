<template>
  <div class="service row">
    <div class="col-sm">
      <label class="text-h6">Today's Schedule</label>
    </div>
  </div>
</template>

<script>
import { defineComponent, reactive, watch, ref, onMounted } from "vue";

import gsap from "gsap";

export default defineComponent({
  name: "priestModule",
  props: {
    cardValue: { type: Array, default: () => [] },
  },
  setup(props) {
    return {};
  },
});
</script>
<style scoped>
.my-card {
  margin-top: 1em;
  height: 300px;
}
.count {
  font-weight: bold;
}
</style>
