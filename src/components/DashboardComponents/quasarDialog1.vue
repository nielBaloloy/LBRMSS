<template>
  <div>
    <q-btn
      :label="buttonLabel"
      :color="buttonColor"
      :icon="icon"
      @click="showDialog"
    />
  </div>
</template>

<script setup>
import { useQuasar } from "quasar";
import { defineProps } from "vue";

const $q = useQuasar();

const props = defineProps({
  title: String,
  message: String, // ✅ Accepts HTML
  buttonLabel: String,
  buttonColor: String,
  icon: String,
});

const showDialog = () => {
  $q.dialog({
    title: props.title,
    message: props.message,
    html: true,
  })
    .onOk(() => {
      console.log("OK clicked");
    })
    .onCancel(() => {
      console.log("Cancel clicked");
    })
    .onDismiss(() => {
      console.log("Dialog dismissed");
    });
};
</script>
