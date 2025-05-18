<template>
  <q-card flat style="width: 900px">
    <q-tabs
      v-model="tab"
      dense
      class="text-grey"
      active-color="primary"
      indicator-color="primary"
      align="justify"
      draggable="true"
      inline-label
    >
      <q-tab
        name="event"
        icon="event"
        label="Event Details"
        no-caps
        inline-label
      />
      <q-tab
        name="groom"
        icon="person"
        no-caps
        inline-label
        label="Groom Information"
      />
      <q-tab
        name="bride"
        icon="person_3"
        no-caps
        inline-label
        label="Bride Information"
      />
    </q-tabs>

    <q-separator />

    <q-tab-panels v-model="tab" animated>
      <q-tab-panel name="event">
        <div class="eventPanel q-pa-md">
          <div class="row q-col-gutter-md">
            <!-- Date Fields -->
            <div class="event col-6">
              <label class="text-subtitle">Date of Marriage</label>
              <q-input
                dense
                outlined
                v-model="localEventData.all.date"
                type="date"
                readonly
              />
            </div>
            <div class="event col-3">
              <label class="text-subtitle">Time Start</label>
              <q-input
                dense
                outlined
                v-model="localEventData.all.time_from"
                type="time"
                readonly
              />
            </div>
            <div class="event col-3">
              <label class="text-subtitle">Time End</label>
              <q-input
                dense
                outlined
                v-model="localEventData.all.time_to"
                type="time"
                readonly
              />
            </div>
            <div class="event col-12">
              <label class="text-subtitle">Venue</label>
              <q-input
                outlined
                v-model="localEventData.all.venue_name"
                class="col-5"
                readonly
              />
            </div>
            <div class="event col-12">
              <label class="text-subtitle">Assigned Priest</label>
              <div class="row q-col-gutter-md">
                <q-input
                  outlined
                  v-model="localEventData.Assigned_Priest"
                  class="col-12"
                  readonly
                />
              </div>
            </div>

            <!-- Submit Button -->
          </div>
        </div>
      </q-tab-panel>

      <q-tab-panel name="groom">
        <div class="row q-col-gutter-md">
          <!-- Date Fields -->
          <div class="event col-3">
            <label class="text-subtitle">First Name</label>
            <q-input dense outlined v-model="localEventData.all.groom_fname" />
          </div>
          <div class="event col-3">
            <label class="text-subtitle">Middle Name</label>
            <q-input dense outlined v-model="localEventData.all.groom_mname" />
          </div>
          <div class="event col-3">
            <label class="text-subtitle">Last Name</label>
            <q-input dense outlined v-model="localEventData.all.groom_lname" />
          </div>
          <div class="event col-3">
            <label class="text-subtitle">Suffix Name</label>
            <q-input dense outlined v-model="localEventData.all.groom_suffix" />
          </div>
          <div class="event col-12">
            <label class="text-subtitle">Age</label>
            <q-input outlined v-model="localEventData.all.age" class="col-5" />
          </div>
          <div class="event col-12">
            <label class="text-subtitle">Date of Birth</label>
            <q-input
              outlined
              v-model="localEventData.all.dob"
              class="col-5"
              type="date"
            />
          </div>
          <div class="event col-3">
            <label class="text-subtitle">Region</label>
            <q-input
              outlined
              v-model="localEventData.all.g_region"
              class="col-5"
              type="date"
            />
          </div>
          <div class="event col-3">
            <label class="text-subtitle">Province</label>
            <q-input
              outlined
              v-model="localEventData.all.g_province"
              class="col-5"
            />
          </div>
          <div class="event col-3">
            <label class="text-subtitle">City</label>
            <q-input
              outlined
              v-model="localEventData.all.g_city"
              class="col-5"
            />
          </div>
          <div class="event col-3">
            <label class="text-subtitle">Barangay</label>
            <q-input
              outlined
              v-model="localEventData.all.g_brgy"
              class="col-5"
            />
          </div>
          <div class="event col-6">
            <label class="text-subtitle">Father</label>
            <div class="row q-col-gutter-md">
              <q-input
                outlined
                v-model="localEventData.all.g_father"
                class="col-12"
              />
            </div>
          </div>
          <div class="event col-6">
            <label class="text-subtitle">Mother</label>
            <div class="row q-col-gutter-md">
              <q-input
                outlined
                v-model="localEventData.all.g_mother"
                class="col-12"
              />
            </div>
          </div>

          <!-- Submit Button -->
        </div>
      </q-tab-panel>

      <q-tab-panel name="bride">
        <div class="row q-col-gutter-md">
          <!-- Date Fields -->
          <div class="event col-3">
            <label class="text-subtitle">First Name</label>
            <q-input dense outlined v-model="localEventData.all.bride_fname" />
          </div>
          <div class="event col-3">
            <label class="text-subtitle">Middle Name</label>
            <q-input dense outlined v-model="localEventData.all.bride_mname" />
          </div>
          <div class="event col-3">
            <label class="text-subtitle">Last Name</label>
            <q-input dense outlined v-model="localEventData.all.bride_lname" />
          </div>
          <div class="event col-3">
            <label class="text-subtitle">Suffix Name</label>
            <q-input dense outlined v-model="localEventData.all.bride_suffix" />
          </div>
          <div class="event col-12">
            <label class="text-subtitle">Age</label>
            <q-input outlined v-model="localEventData.all.age" class="col-5" />
          </div>
          <div class="event col-12">
            <label class="text-subtitle">Date of Birth</label>
            <q-input
              outlined
              v-model="localEventData.all.dob"
              class="col-5"
              type="date"
            />
          </div>
          <div class="event col-3">
            <label class="text-subtitle">Region</label>
            <q-input
              outlined
              v-model="localEventData.all.b_region"
              class="col-5"
              type="date"
            />
          </div>
          <div class="event col-3">
            <label class="text-subtitle">Province</label>
            <q-input
              outlined
              v-model="localEventData.all.b_province"
              class="col-5"
            />
          </div>
          <div class="event col-3">
            <label class="text-subtitle">City</label>
            <q-input
              outlined
              v-model="localEventData.all.b_city"
              class="col-5"
            />
          </div>
          <div class="event col-3">
            <label class="text-subtitle">Barangay</label>
            <q-input
              outlined
              v-model="localEventData.all.b_brgy"
              class="col-5"
            />
          </div>
          <div class="event col-6">
            <label class="text-subtitle">Father</label>
            <div class="row q-col-gutter-md">
              <q-input
                outlined
                v-model="localEventData.all.b_father"
                class="col-12"
              />
            </div>
          </div>
          <div class="event col-6">
            <label class="text-subtitle">Mother</label>
            <div class="row q-col-gutter-md">
              <q-input
                outlined
                v-model="localEventData.all.b_mother"
                class="col-12"
              />
            </div>
          </div>

          <!-- Submit Button -->
        </div>
      </q-tab-panel>
    </q-tab-panels>
  </q-card>
</template>

<script setup>
import { defineProps, defineEmits, computed, onMounted, ref, watch } from "vue";
import { loadWitness, witness } from "src/composables/loadWitness";

// Props
const props = defineProps({
  modelValue: Boolean,
  title: { type: String, default: "Dialog Title" },
  message: { type: String, default: "Dialog message goes here." },
  actions: { type: Array, default: () => [{ label: "OK", color: "primary" }] },
  eventData: { type: Object, default: () => ({}) },
});
onMounted(async () => {
  await loadWitness();

  dataLoaded.value = true; // ✅ Mark as loaded
});
// Emits
const emit = defineEmits(["update:modelValue", "update:eventData", "action"]);

// Local Reactive Copy of eventData
const localEventData = ref({ ...props.eventData });

// Watch for Changes in Parent eventData and Sync
watch(
  () => props.eventData,
  (newData) => {
    localEventData.value = { ...newData };
  },
  { deep: true }
);

// Save Function
const saveEvent = () => {
  console.log("Saved Event Data:", localEventData.value);
  emit("update:eventData", localEventData.value);
};

// Dialog Show Control
const showDialog = computed({
  get: () => props.modelValue,
  set: (val) => emit("update:modelValue", val),
});
let witnessCon = witness;
const tab = ref("event");
</script>
