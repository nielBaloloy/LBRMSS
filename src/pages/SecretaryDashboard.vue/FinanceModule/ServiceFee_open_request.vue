<template>
  <!-- <div>{{ requestData }}</div> -->
  <div class="requestpanel">
    <q-form @submit="submitForm" ref="marriageForm">
      <q-card-section>
        <q-table flat bordered :rows="feeRows" :columns="columns" hide-bottom>
          <template v-slot:body="props">
            <q-tr :props="props">
              <q-td
                ><p class="text-subtitle">{{ props.row.name }}</p>
              </q-td>
              <q-td style="padding: 1em">
                <q-input
                  v-model.number="fees[props.row.model]"
                  type="number"
                  outlined
                  dense
                  :rules="[(val) => val >= 0 || 'Enter a valid amount']"
                />
              </q-td>
            </q-tr>
          </template>
        </q-table>

        <q-select
          v-model="fees.paymentStatus"
          :options="paymentStatusOptions"
          label="Payment Status"
          outlined
          class="q-mt-md"
        />
      </q-card-section>

      <q-card-actions align="right">
        <q-btn unelevated label="Submit" type="submit" color="primary" />
        <q-btn unelevated label="Reset" type="reset" @click="resetForm" />
      </q-card-actions>
    </q-form>
  </div>
</template>

<script setup>
import { defineProps, defineEmits, computed, ref, watch } from "vue";
import { useQuasar } from "quasar";
// Props
const props = defineProps({
  modelValue: Boolean,
  title: { type: String, default: "Dialog Title" },
  message: { type: String, default: "Dialog message goes here." },
  actions: { type: Array, default: () => [{ label: "OK", color: "primary" }] },
  requestData: { type: Object, default: () => ({}) },
});

// Emits
const emit = defineEmits(["update:modelValue", "update:eventData", "action"]);
const $q = useQuasar();
// Local Reactive Copy of eventData
const requestData = ref({ ...props.requestData });

// Watch for Changes in Parent eventData and Sync
watch(
  () => props.requestData,
  (newData) => {
    requestData.value = { ...newData };
  },
  { deep: true }
);

// Save Function
const saveEvent = () => {
  console.log("Saved Event Data:", requestData.value);
  emit("update:eventData", requestData.value);
};

// Dialog Show Control
const showDialog = computed({
  get: () => props.modelValue,
  set: (val) => emit("update:modelValue", val),
});

const tab = ref("event");
const fees = ref({
  marriageCeremonyFee: 0,
  seminarFee: 0,
  documentationFee: 0,
  reservationFee: 0,
  priestStipend: 0,
  paymentStatus: "Pending",
});

const paymentStatusOptions = ["Pending", "Partial", "Paid"];

const columns = [
  { name: "feeName", label: "Fee Name", align: "center", field: "name" },
  { name: "amount", label: "Amount", align: "cenetr", field: "amount" },
];

const feeRows = [
  { name: "Marriage Ceremony Fee", model: "marriageCeremonyFee" },
  { name: "Marriage Seminar Fee", model: "seminarFee" },
  { name: "Documentation Fee", model: "documentationFee" },
  { name: "Church Reservation Fee", model: "reservationFee" },
  { name: "Stipend for Officiating Priest", model: "priestStipend" },
];

const submitForm = () => {
  console.log("Form Submitted:", fees.value);
  $q.notify({
    type: "positive",
    message: "Marriage fees submitted successfully!",
  });
};

const resetForm = () => {
  fees.value = {
    marriageCeremonyFee: 0,
    seminarFee: 0,
    documentationFee: 0,
    reservationFee: 0,
    priestStipend: 0,
    paymentStatus: "Pending",
  };
};
</script>
<style scoped>
.requestpanel {
  width: 100%;
  height: auto;
}
.q-card {
  max-width: 500px;
  margin: auto;
}
</style>
