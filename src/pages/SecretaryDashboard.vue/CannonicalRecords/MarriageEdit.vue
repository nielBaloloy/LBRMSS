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
      <q-tab
        name="sponsor"
        icon="groups"
        no-caps
        inline-label
        label="Sponsors / Testium"
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
        <div class="text-h6">Alarms</div>
        Lorem ipsum dolor sit amet consectetur adipisicing elit.
      </q-tab-panel>

      <q-tab-panel name="bride">
        <div class="text-h6">Movies</div>
        Lorem ipsum dolor sit amet consectetur adipisicing elit.
      </q-tab-panel>
      <q-tab-panel name="sponsor">
        <div class="text-h6">Movies</div>
        Lorem ipsum dolor sit amet consectetur adipisicing elit.
      </q-tab-panel>
    </q-tab-panels>
  </q-card>
</template>

<script setup>
import { defineProps, defineEmits, computed, ref, watch } from "vue";

// Props
const props = defineProps({
  modelValue: Boolean,
  title: { type: String, default: "Dialog Title" },
  message: { type: String, default: "Dialog message goes here." },
  actions: { type: Array, default: () => [{ label: "OK", color: "primary" }] },
  eventData: { type: Object, default: () => ({}) },
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

const tab = ref("event");
</script>
