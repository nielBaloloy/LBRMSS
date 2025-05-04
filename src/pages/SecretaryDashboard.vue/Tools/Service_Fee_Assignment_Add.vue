<template>
  <q-card class="">
    <q-banner class="bg-amber-5 text-white text-subtitle1">
      <q-icon name="payments" size="sm"></q-icon>
      Fee Assignment
    </q-banner>
    <q-card-section>
      <q-form @submit="submitForm" @reset="resetForm">
        <!-- Fee Name -->
        <div class="">
          <q-item-label>Fee Name</q-item-label>
          <q-input
            v-model="form.name"
            outlined
            dense
            lazy-rules
            class="input-width"
            :rules="[(val) => !!val || 'Fee Name is required']"
          />
        </div>

        <!-- Amount -->
        <div class="">
          <q-item-label>Amount</q-item-label>
          <q-input
            v-model="form.amount"
            type="number"
            outlined
            dense
            lazy-rules
            class="input-width"
            :rules="[(val) => val > 0 || 'Amount must be greater than zero']"
          />
        </div>

        <!-- Service Type -->
        <div class="">
          <q-item-label>Service</q-item-label>
          <q-select
            v-model="form.service_type"
            :options="categoryOption"
            outlined
            dense
            emit-value
            map-options
            lazy-rules
            class="input-width"
            :rules="[(val) => !!val || 'Please select a service type']"
          />
        </div>
        <div class="">
          <q-item-label>Service Type</q-item-label>
          <q-select
            v-model="form.service_fee_id"
            :options="serviceTypeOptions"
            outlined
            dense
            emit-value
            map-options
            lazy-rules
            class="input-width"
            :rules="[(val) => !!val || 'Please select a service type']"
          />
        </div>

        <!-- Venue Type -->
        <div class="q-mb-md">
          <q-item-label>Venue Type</q-item-label>
          <q-option-group
            v-model="form.VenueType"
            :options="venueOptions"
            inline
          />
        </div>

        <!-- Buttons -->
        <div class="q-mt-md row justify-end">
          <q-btn label="Reset" type="reset" color="grey" flat class="q-mr-sm" />
          <q-btn label="Submit" type="submit" color="primary" />
        </div>
      </q-form>
    </q-card-section>
  </q-card>
</template>

<script setup>
import { ref } from "vue";
import { useQuasar } from "quasar";
import { api } from "src/boot/axios";
import Swal from "sweetalert2";
const $q = useQuasar();
const form = ref({
  name: "", // Fee Name
  model: "", // v-model for each fee
  amount: null, // Amount
  service_type: null,
  service_fee_id: null, // Service Type
  VenueType: 0, // 0 = Church, 1 = Outside
});

const emit = defineEmits(["closeDialog"]);
const serviceTypeOptions = [
  { label: "Mass", value: 1 },
  { label: "Special", value: 2 },
  { label: "Burial - Traditional", value: 3 },
  { label: "Burial - Cremation", value: 4 },
  { label: "Certificate", value: 5 },
];

const venueOptions = [
  { label: "Church", value: 0 },
  { label: "Outside", value: 1 },
];

const categoryOption = [
  { label: "Marriage", value: 1 },
  { label: "Burial", value: 4 },
  { label: "Baptism", value: 2 },
  { label: "Confirmation", value: 3 },
  { label: "Blessing", value: 6 },
  { label: "Annointing of the Sick", value: 5 },
];

const submitForm = () => {
  $q.dialog({
    title: "Confirm",
    message: "Save Information?",
    cancel: true,
    persistent: true,
    icon: "wifi",
  })
    .onOk(() => {
      api
        .post("tools_setup_loadfee.php", {
          AssignedFee: form.value,
        })
        .then((response) => {
          console.log(response);
          window.location.reload();
        })
        .catch((error) => {
          reject(error);
        });
      window.location.reload();
      emit("closeDialog");
    })

    .onCancel(() => {
      // console.log('>>>> Cancel')
    });
};

const resetForm = () => {
  form.value = {
    name: "",
    model: "",
    amount: null,
    service_type: null,
    service_fee_id: null,
    VenueType: 0,
  };
};
</script>

<style scoped>
.input-width {
  width: 450px;
}
</style>
