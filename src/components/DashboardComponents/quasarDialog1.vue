<template>
  <div>
    <q-btn
      :label="buttonLabel"
      :color="buttonColor"
      :icon="icon"
      @click="openDialog"
    />
    <q-dialog v-model="showDialog">
      <q-card>
        <q-card-section>
          <div class="text-h6">{{ title }}</div>
        </q-card-section>

        <q-separator />

        <q-card-section style="max-height: 50vh" class="scroll">
        </q-card-section>

        <q-separator />

        <q-card-actions align="right">
          <q-btn flat label="Decline" color="primary" v-close-popup />
          <q-btn flat label="Accept" color="primary" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>

<script setup>
import { ref, defineProps } from "vue";

defineProps({
  title: String,
  buttonLabel: String,
  buttonColor: String,
  icon: String,
  save: Function,
});

let showDialog = ref(false);

const openDialog = () => {
  showDialog.value = true;
};
</script>
