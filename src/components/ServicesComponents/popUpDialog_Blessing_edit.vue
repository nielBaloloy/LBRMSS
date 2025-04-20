div
<template>
  <div class="q-gutter-y-md" style="width: 70vw">
    <q-card class="q-pa-lg">
      <div class="event_container">
        <div
          class="d-flex col align-items-start q-gutter-md"
          v-for="event in events"
          :key="event.event_id"
        >
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
            Select Service
            <div class="q-gutter-md row">
              <q-select
                readonly
                outlined
                ref="step1Ref"
                :dense="true"
                v-model="event.Service"
                use-input
                hide-selected
                fill-input
                input-debounce="0"
                :options="filterOptions"
                option-label="event_name"
                option-value="etype_id"
                emit-value
                map-options
                @new-value="createValue"
                @filter="filterFn"
                style="width: 100%"
                :rules="[(val) => !!val || 'Field is required']"
              >
                <template v-slot:no-option>
                  <q-item>
                    <q-item-section class="text-grey">
                      No results
                    </q-item-section>
                  </q-item>
                </template>
              </q-select>
            </div>
          </div>
          <div class="Venue">
            Description
            <q-input
              outlined
              v-model="blessing[0].notes"
              :dense="true"
              class="input-field"
              type="textarea"
            />
          </div>
          <div class="date">
            <div class="mb-1">Date of Event</div>
            <q-input
              class="w-full"
              ref="step1Ref"
              :dense="true"
              outlined
              v-model="event.Date"
              :placeholder="currentDatePlaceholder"
            >
              <template v-slot:append>
                <q-icon name="event" class="cursor-pointer">
                  <q-popup-proxy
                    cover
                    transition-show="scale"
                    transition-hide="scale"
                  >
                    <q-date mask="YYYY-MM-DD" v-model="event.Date">
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

          <div class="timeFrom flex-1">
            <div class="mb-1">Time From</div>
            <q-input
              class="w-full"
              outlined
              :dense="true"
              v-model="event.Time_to"
              mask="time"
            >
              <template v-slot:append>
                <q-icon name="access_time" class="cursor-pointer">
                  <q-popup-proxy
                    cover
                    transition-show="scale"
                    transition-hide="scale"
                  >
                    <q-time v-model="event.Time_to">
                      <div class="row items-center justify-end">
                        <q-btn
                          v-close-popup
                          label="Close"
                          color="primary"
                          flat
                        />
                      </div>
                    </q-time>
                  </q-popup-proxy>
                </q-icon>
              </template>
            </q-input>
          </div>

          <div class="timeTo flex-1 min-w-[200px]">
            <div class="mb-1">Time To</div>
            <q-input
              class="w-full"
              ref="step1Ref"
              outlined
              :dense="true"
              v-model="event.Time_from"
              mask="time"
            >
              <template v-slot:append>
                <q-icon name="access_time" class="cursor-pointer">
                  <q-popup-proxy
                    cover
                    transition-show="scale"
                    transition-hide="scale"
                  >
                    <q-time
                      v-model="event.Time_from"
                      @update:model-value="durationInMinutesEvent(formData)"
                    >
                      <div class="row items-center justify-end">
                        <q-btn
                          v-close-popup
                          label="Close"
                          color="primary"
                          flat
                        />
                      </div>
                    </q-time>
                  </q-popup-proxy>
                </q-icon>
              </template>
            </q-input>
          </div>

          <div class="timeDurat hidden">
            <!-- {{ event.all.duration }} -->
            {{ (event.all.duration = timeDurationEvent) }}
          </div>

          <div class="venue">
            <p>Venue Type</p>
            <q-btn-toggle
              unelevated
              v-model="event.Venuetype"
              toggle-color="primary"
              :options="[
                { label: 'Pastoral Center', value: 2 },
                { label: 'Church', value: 1 },
                { label: 'Outside', value: 3 },
              ]"
              @update:model-value="setAddress(event.Venuetype, event)"
            />
          </div>

          <div class="Venue">
            Venue Address
            <q-input
              outlined
              v-model="blessing[0].location"
              :dense="true"
              class="input-field"
            />
          </div>

          <div class="servicefield">
            Assigned Priest
            <q-select
              :dense="true"
              outlined
              clearable
              ref="step1Ref"
              v-model="event.Assigned_Priest"
              :options="
                availablePriest && availablePriest.length
                  ? availablePriest
                  : [
                      {
                        priest_id: null,
                        priest_name: 'No available priest',
                      },
                    ]
              "
              option-value="priest_id"
              option-label="priest_name"
              :rules="[(val) => !!val || 'Field is required']"
            />
          </div>
        </div>
        <div class="nav row justify-end">
          <q-btn unelevated color="primary">cancel</q-btn>
          <q-btn unelevated color="primary" @click="saveBlessing">Save</q-btn>
        </div>
      </div>
    </q-card>
  </div>
</template>
<script>
import { useQuasar } from "quasar";
import moment from "moment";
import { api } from "src/boot/axios";
import philippineData from "src/AddressPH/philippine_provinces_cities_municipalities_and_barangays_2019v2.json";
import { NationalityList } from "src/data/nationality";
import {
  ref,
  computed,
  watch,
  defineEmits,
  defineComponent,
  onMounted,
} from "vue";
import { getAvailablePriest, availablePriest } from "src/composables/getPriest";
export default defineComponent({
  props: {
    editables: { type: Array, default: () => [] },
  },
  setup(props) {
    console.log(props);
    let $q = useQuasar();
    let regionOptions = ref([]);
    let provinceOptions = ref([]);
    let cityOptions = ref([]);
    let barangayOptions = ref([]);

    let events = ref([props.editables[0]]);
    let blessing = ref([props.editables[0].blessing]);

    console.log(events.value, blessing.value);
    const stringOptions = ref([
      { etype_id: "1", event_name: "Marriage" },
      { etype_id: "2", event_name: "Baptism" },
      { etype_id: "3", event_name: "Confirmation" },
      { etype_id: "4", event_name: "Burial" },
      { etype_id: "5", event_name: "Anointing of the Sick" },
      { etype_id: "6", event_name: "Blessing" },
      { etype_id: "7", event_name: "Misa" },
      { etype_id: "8", event_name: "Others" },
    ]);
    const filterOptions = ref([...stringOptions.value]);

    const timeDurationEvent = ref(null);
    const durationInMinutesEvent = (payload) => {
      console.log(payload);
      setTimeout(() => {
        if (payload.TimeFrom == null || payload.TimeTo == null) {
          timeDurationEvent.value = 0;
          return;
        } else {
          const start = moment(payload.TimeFrom, "HH:mm");
          const end = moment(payload.TimeTo, "HH:mm");
          const durationTime = moment.duration(end.diff(start));
          timeDurationEvent.value = durationTime.asMinutes();
          console.log("time in minutes", timeDurationEvent.value);
          return;
        }
      }, 500);
    };

    let VenueTypeAddress = ref(false);
    let Address_A = ref(false);
    let setAddress = (venueType, formData) => {
      if (venueType == 1) {
        formData.Venue = "St Raphael Church";
        Address_A.value = false;
      } else if (venueType == 2) {
        formData.Venue = "Pastoral Center";
        Address_A.value = false;
      } else if (venueType === 3) {
        formData.Venue = null;
        Address_A.value = true;
      } else {
        VenueTypeAddress.value = false;
      }
    };
    let blessingList = ref([]); // make sure it's a ref if you want reactivity

    const saveBlessing = () => {
      blessingList.value = events.value.map((event, index) => {
        return {
          ...event,
          blessing: blessing.value[index] || {},
        };
      });

      $q.dialog({
        title: "Save Changes?",
        message:
          "You're about to make some divine changes, Data will be saved to the database",
        cancel: true,
        persistent: true,
        icon: "check_circle",
      })
        .onOk(() => {
          api
            .put("BlessingAPI.php", { blessingList: blessingList.value })
            .then((response) => {
              console.log(response);
              if (response.data.Status == "Success") {
                $q.notify({
                  type: "positive",
                  message: "Updated successfully!",
                });
                emit("closeDialog");
              }
            })
            .catch((error) => {
              console.log(error);
            });
        })
        .onCancel(() => {});
    };
    return {
      saveBlessing,
      events,
      blessing,
      filterOptions,
      durationInMinutesEvent,
      timeDurationEvent,
      setAddress,
      Address_A,
      VenueTypeAddress,
    };
  },
});
</script>
