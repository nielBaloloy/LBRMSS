<template>
  <div class="q-gutter-y-md" style="width: 50vw">
    <q-card>
      <q-tabs
        v-model="tab"
        dense
        class="text-grey"
        active-color="primary"
        indicator-color="primary"
        align="justify"
        narrow-indicator
      >
        <q-tab name="events" label="Event Details" no-caps />
        <q-tab name="personaldetails" label="Personal Details" no-caps />
        <q-tab name="witness" label="Witness and God Parents" no-caps />
        <q-tab name="seminarReq" label="Semninar and Requirements" no-caps />
      </q-tabs>

      <q-separator />

      <q-tab-panels v-model="tab" animated>
        <q-tab-panel name="events">
          <div class="event_container">
            <div class="d-flex col align-items-start q-gutter-md">
              <div class="servicfield">
                Client Name
                <q-input
                  outlined
                  v-model="event.Client"
                  :dense="true"
                  ref="step1Ref"
                  :rules="[(val) => !!val || 'Field is required']"
                  class="input-field"
                />
              </div>

              <div class="servicfield">
                Type of Mass
                {{ event.Type }}
              </div>

              <div class="date">
                <div class="mb-1">Date of Event</div>
                <q-input
                  class="w-full"
                  ref="step1Ref"
                  :dense="true"
                  outlined
                  v-model="formattedDate"
                  :placeholder="currentDatePlaceholder"
                >
                  <template v-slot:append>
                    <q-icon name="event" class="cursor-pointer">
                      <q-popup-proxy
                        cover
                        transition-show="scale"
                        transition-hide="scale"
                      >
                        <q-date mask="YYYY-MM-DD" v-model="formattedDate">
                          <div class="row items-center justify-end">
                            <q-btn
                              v-close-popup
                              label="Close"
                              color="primary"
                              flat
                            />
                          </div>
                        </q-date>
                      </q-popup-proxy>
                    </q-icon>
                  </template>
                </q-input>
              </div>
            </div>
          </div>
        </q-tab-panel>

        <q-tab-panel name="personaldetails">
          <div class="text-h6">Alarms</div>
          Lorem ipsum dolor sit amet consectetur adipisicing elit.
        </q-tab-panel>

        <q-tab-panel name="witness">
          <div class="text-h6">Movies</div>
          Lorem ipsum dolor sit amet consectetur adipisicing elit.
        </q-tab-panel>
        <q-tab-panel name="seminarReq">
          <div class="text-h6">Movies</div>
          Lorem ipsum dolor sit amet consectetur adipisicing elit.
        </q-tab-panel>
      </q-tab-panels>
    </q-card>
  </div>
</template>
<script>
import { ref, computed, watch, defineEmits, defineComponent } from "vue";
export default defineComponent({
  props: {
    editables: { type: Array, default: () => [] },
  },
  setup(props) {
    let event = ref(props.editables[0]);
    let personaldetails = ref(props.editables.confirmation);
    let witness = ref(props.editables.witness);
    let Requirement = ref(props.editables.Requirement);
    console.log(event);

    /**===================================================================== */
    // Convert event.Date to a Date object for manipulation
    let formattedDate = ref(event.value.Date); // Initialize formattedDate with the string date

    // Watch the formattedDate and update event.Date when it changes
    watch(formattedDate, (newDate) => {
      event.value.Date = newDate; // Update event.Date when formattedDate changes
    });
    return {
      event,
      personaldetails,
      witness,
      Requirement,
      formattedDate,
      tab: ref("events"),
    };
  },
});
</script>
