<template>
  <div class="requestpanel">
    <div class="row justify-center q-mt-md">
      <p class="text-h6">Payment Breakdown</p>
    </div>

    <q-form @submit="submitForm" ref="marriageForm">
      <q-card-section>
        <q-table
          flat
          bordered
          :rows="feeRows"
          :columns="columns"
          hide-bottom
          :pagination="pagination"
        >
          <template v-slot:body="props">
            <q-tr :props="props">
              <q-td>
                <p class="text-subtitle">{{ props.row.name }}</p>
              </q-td>
              <q-td style="padding: 1em">
                <q-input
                  v-model.number="fees[props.row.model]"
                  type="number"
                  outlined
                  dense
                  readonly
                  :rules="[(val) => val >= 0 || 'Enter a valid amount']"
                />
              </q-td>
            </q-tr>
          </template>
        </q-table>

        <!-- ✅ Display Total Fees Below the Table -->

        <div class="row justify-end q-mt-md">
          <p class="text-h6">
            Total Amount: <strong>₱{{ totalFees }}</strong>
          </p>
        </div>

        <!-- ✅ Down Payment Checkbox -->
        <div class="downpaymentCon" v-show="downpaymentCon">
          <div class="row justify-end">
            <q-checkbox
              v-model="isDownPayment"
              color="primary"
              label="Do you prefer to pay a downpayment first?"
            />
          </div>

          <!-- ✅ Down Payment Input (Auto-Filled) -->
          <div class="row justify-end" v-if="isDownPayment">
            <p class="text-subtitle">
              Down Payment (50%): <strong>₱{{ downPayment }}</strong>
            </p>
          </div>
        </div>

        <!-- ✅ Remaining Balance Calculation -->
        <div class="row justify-end">
          <p class="text-subtitle">
            Remaining Balance: <strong>₱{{ remainingBalance }}</strong>
          </p>
        </div>

        <!-- ✅ Balance After Client Payment -->
        <!-- <div class="row justify-end">
          <p class="text-subtitle">
            Balance After Payment: <strong>₱{{ balanceAfterPayment }}</strong>
          </p>
        </div> -->
        <!-- ✅ Client's Payment Input -->
        <div class="row justify-end">
          <p class="text-subtitle">
            Client's Payment:
            <q-input
              v-model.number="payment_info.payment"
              type="number"
              outlined
              dense
              style="width: 300px"
              :rules="[(val) => val >= 0 || 'Enter a valid amount']"
            />
          </p>
        </div>
        <!-- ✅ Payment Status Selection -->
      </q-card-section>

      <q-card-actions align="right">
        <q-btn unelevated label="Submit" type="submit" color="primary" />
        <q-btn unelevated label="Reset" type="reset" @click="resetForm" />
      </q-card-actions>
    </q-form>
  </div>
</template>

<script setup>
import {
  defineProps,
  defineEmits,
  computed,
  ref,
  watch,
  watchEffect,
  onMounted,
} from "vue";
import { useQuasar, SessionStorage } from "quasar";
import { api } from "../../../boot/axios";
const props = defineProps({
  modelValue: Boolean,
  requestData: { type: Object, default: () => ({}) },
  feeRows: { type: Array, default: () => [] },
});

const emit = defineEmits([
  "update:modelValue",
  "update:eventData",
  "action",
  "closeDialog",
]);
const $q = useQuasar();
let myObject = ref();
let sessionkey = SessionStorage.getItem("log");
myObject.value = JSON.parse(sessionkey);
console.log(myObject.value);

const columns = [
  {
    name: "feeName",
    label: "Fee Name",
    align: "center",
    field: "name",
    sortable: false,
  },
  {
    name: "amount",
    label: "Amount",
    align: "center",
    field: "amount",
    sortable: false,
  },
];

// ✅ Fee rows with default amounts
let downpaymentCon = ref(false);
console.log(props.feeRows);

// ✅ Initialize fees dynamically from feeRows
const fees = ref(
  props.feeRows.reduce(
    (acc, row) => {
      acc[row.model] = parseFloat(row.amount) || 0; // Convert amount to a proper number
      return acc;
    },
    { paymentStatus: "Pending" }
  )
);

// ✅ Computed total fees
const totalFees = computed(() => {
  return parseFloat(
    Object.values(fees.value)
      .filter((val) => typeof val === "number" && !isNaN(val)) // Ensure it's a valid number
      .reduce((sum, val) => sum + val, 0) // Sum up values
      .toFixed(2)
  );
});

// ✅ Down payment logic (50% of total fees)
const isDownPayment = ref(false);
const paymentType = ref(1); // Default to 1 when unchecked
const downPayment = computed(() =>
  isDownPayment.value ? totalFees.value * 0.5 : 0
);

// ✅ Remaining balance after down payment
const remainingBalance = computed(() => totalFees.value - downPayment.value);

// ✅ Balance after client's payment
const balanceAfterPayment = computed(
  () => remainingBalance.value - (payment_info.value.payment || 0)
);

const paymentStatusOptions = ["Pending", "Partial", "Paid"];

watch(isDownPayment, (newValue) => {
  paymentType.value = newValue ? 2 : 1; // 2 if checked, 1 if unchecked
  console.log("Payment Type:", paymentType.value);
});

const payment_info = ref({
  event_fee_id: null,
  event_id: props.requestData.all.event_id, // Foreign key reference to event
  service_id: props.requestData.all.service_id,
  reference_no: props.requestData.all.reference_no, // Unique reference number
  payment_type: paymentType.value,
  amount_total: totalFees.value, // Total amount for the event
  payment: null, // Initial payment
  balance: remainingBalance.value, // Remaining balance
  due_date: null, // Payment due date 1 week before event
  status: "1", // 1 = Pending, 2 = Partially Paid, 3 = Paid
  created_at: null, // Timestamp of creation
  updated_at: null, // Timestamp of last update
  created_by: myObject.value.UID, // User who created the record
  updated_by: null, // User who last updated the record
  remark: "1", // 1 = Show, 0 = Hide
});

watchEffect(() => {
  payment_info.value.payment_type = paymentType.value;
  payment_info.value.amount_total = totalFees.value;
  payment_info.value.balance = remainingBalance.value;
});
watch(balanceAfterPayment, (newBalance) => {
  if (newBalance <= 0) {
    payment_info.value.status = "3"; // Paid
  } else if (payment_info.value.payment > 0) {
    payment_info.value.status = "2"; // Partially Paid
  } else {
    payment_info.value.status = "1"; // Pending
  }
});

/** ========== SAVE ================================= */
const submitForm = () => {
  if (!isDownPayment.value) {
    //check if the type of payment is full payment must be equal to total fees else remaining bal
    if (payment_info.value.payment >= totalFees.value) {
      $q.dialog({
        dark: false,
        title: "Confirm Payment",
        message: "Are you sure you want to proceed with the payment?",
        cancel: true,
        persistent: true,
        icon: "person", // Material Design Icon (for cash/payment)
        ok: {
          label: "Confirm", // Change OK button to Confirm
          color: "positive", // Make it green
          unelevated: true, // Gives a press effect
        },
        cancel: {
          label: "Cancel",
          color: "primary",
          unelevated: true, // Gives a press effect
        },
      })
        .onOk(() => {
          console.log("payment :", payment_info.value);
          console.log("Form Submitted:", fees.value);
          const payload = {
            ...payment_info.value,
            ...fees.value,
          };
          api
            .post("ServiceFee_Requset_save.php", payload)
            .then((response) => {
              if (response.data.message == "") {
                $q.notify({
                  type: "positive",
                  message: "Payments submitted successfully!",
                });
              }
              // window.location.reload();
            })
            .catch((error) => {
              reject(error);
              $q.notify({
                type: "negative",
                message: "Network Error",
              });
            });

          emit("closeDialog");
        })
        .onCancel(() => {
          console.log("Payment Cancelled");
        });
    } else {
      $q.notify({
        type: "negative",
        message: "Insufficient Payment",
      });
    }
  } else {
    if (payment_info.value.payment == downPayment.value) {
      $q.dialog({
        dark: false,
        title: "Confirm Payment",
        message: "Are you sure you want to proceed with the downpayment?",
        cancel: true,
        persistent: true,
        icon: "person", // Material Design Icon (for cash/payment)
        ok: {
          label: "Confirm", // Change OK button to Confirm
          color: "positive", // Make it green
          unelevated: true, // Gives a press effect
        },
        cancel: {
          label: "Cancel",
          color: "primary",
          unelevated: true, // Gives a press effect
        },
      })
        .onOk(() => {
          console.log("payment :", payment_info.value);
          console.log("Form Submitted:", fees.value);
          const payload = {
            ...payment_info.value,
            ...fees.value,
          };
          console.log(payload);

          api
            .post("ServiceFee_Requset_save.php", payload)
            .then((response) => {
              if (response.data.message == "") {
                $q.notify({
                  type: "positive",
                  message: "Payments submitted successfully!",
                });
              }
            })
            .catch((error) => {
              reject(error);
              $q.notify({
                type: "negative",
                message: "Network Error",
              });
            });

          emit("closeDialog");
        })
        .onCancel(() => {
          console.log("Payment Cancelled");
        });
    } else {
      $q.notify({
        type: "negative",
        message: "Insufficient Payment",
      });
    }
  }
};

const resetForm = () => {
  fees.value = feeRows.reduce(
    (acc, row) => {
      acc[row.model] = row.amount;
      return acc;
    },
    { paymentStatus: "Pending" }
  );
  isDownPayment.value = false;
  payment_info.value.payment = null;
};

watch(
  () => props.feeRows,
  (newVal) => {
    console.log(newVal[0]);
    if (newVal[0].service_fee_id == 1 || newVal[0].service_fee_id == 4) {
      downpaymentCon.value = true;
    } else {
      downpaymentCon.value = false;
    }
  },
  { immediate: true }
);

let pagination = ref({
  rowsPerPage: 0,
});
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
