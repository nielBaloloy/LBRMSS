<template>
  <div class="q-gutter-y-md" style="width: 70vw">
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
        <q-tab name="witness" label="Additional Info" no-caps />
        <q-tab name="seminarReq" label="Semninar and Requirements" no-caps />
      </q-tabs>

      <q-separator />
      <!-- Event Details -->
      <q-tab-panels v-model="tab" animated>
        <q-tab-panel name="events">
          <div class="event_container q-pa-md">
            <div
              class="d-flex col align-items-start q-gutter-md"
              v-for="event in events"
              :key="event.event_id"
            >
              <div class="servicfield">
                Client Name
                <q-input
                  readonly
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

              <div class="servicfield">
                Service Type
                <div class="q-gutter-sm">
                  <q-radio
                    disable
                    v-model="event.Type"
                    val="Special"
                    label="Special"
                  />
                  <q-radio
                    disable
                    v-model="event.Type"
                    val="Mass"
                    label="Mass"
                  />
                </div>
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
                        <q-time v-model="event.Time_from">
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
                        <q-time
                          v-model="event.TimeTo"
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

              <div class="Venue" v-show="Address_A">
                Venue Address
                <q-input
                  outlined
                  v-model="event.Venue"
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
              <q-btn
                unelevated
                color="primary"
                @click="() => (tab = 'personaldetails')"
                >Next</q-btn
              >
              <q-btn
                unelevated
                color="primary"
                @click="() => (tab = 'personaldetails')"
                >Save</q-btn
              >
            </div>
          </div>
        </q-tab-panel>
        <!-- Personal Details -->
        <q-tab-panel name="personaldetails">
          <div
            class="event_container q-pa-md"
            v-for="personalDetails in personaldetails"
            :key="personalDetails.bpid"
          >
            <!-- Last Name -->
            <div class="inputStep">
              <p class="q-mb-xs">Last Name</p>
              <q-input dense outlined v-model="personalDetails.bu_lname" />
            </div>

            <!-- First Name -->
            <div class="inputStep">
              <p class="q-mb-xs">First Name</p>
              <q-input dense outlined v-model="personalDetails.bu_fname" />
            </div>

            <!-- Middle Name -->
            <div class="inputStep">
              <p class="q-mb-xs">Middle Name</p>
              <q-input dense outlined v-model="personalDetails.bu_mname" />
            </div>

            <!-- Suffix Name -->
            <div class="inputStep">
              <p class="q-mb-xs">Suffix Name</p>
              <q-input dense outlined v-model="personalDetails.bu_suffix" />
            </div>

            <!-- Gender -->
            <div class="inputStep">
              <p class="q-mb-xs">Gender</p>
              <q-radio
                size="xs"
                v-model="personalDetails.bu_gender"
                :val="1"
                label="Male"
              />
              <q-radio
                size="xs"
                v-model="personalDetails.bu_gender"
                :val="2"
                label="Female"
              />
            </div>

            <!-- Birth Date -->
            <div class="inputStep">
              <p class="q-mb-xs">Birth Date</p>
              <q-input dense outlined v-model="personalDetails.bu_birthdate">
                <template v-slot:append>
                  <q-icon name="event" class="cursor-pointer">
                    <q-popup-proxy
                      cover
                      transition-show="scale"
                      transition-hide="scale"
                    >
                      <q-date
                        v-model="personalDetails.bu_birthdate"
                        mask="YYYY-MM-DD"
                      >
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

            <!-- Birthplace -->
            <div class="inputStep">
              <p class="q-mb-xs">Birthplace</p>
              <q-input dense outlined v-model="personalDetails.bu_birthplace" />
            </div>

            <!-- Nationality -->
            <div class="inputStep">
              <p class="q-mb-xs">Nationality</p>
              <q-select
                dense
                outlined
                v-model="personalDetails.bu_nationality"
                :options="NationalityList"
              />
            </div>

            <!-- Father's Name -->
            <div class="inputStep">
              <p class="q-mb-xs">Father's Name</p>
              <q-input dense outlined v-model="personalDetails.bu_father" />
            </div>

            <!-- Mother's Name -->
            <div class="inputStep">
              <p class="q-mb-xs">Mother's Name</p>
              <q-input dense outlined v-model="personalDetails.bu_mother" />
            </div>

            <!-- Legitimacy (Status) -->
            <div class="inputStep">
              <p class="q-mb-xs">Legitimacy</p>
              <q-radio
                v-model="personalDetails.bu_status"
                :val="0"
                label="Illegal"
                color="amber"
              />
              <q-radio
                v-model="personalDetails.bu_status"
                :val="1"
                label="Legal"
                color="amber"
              />
            </div>

            <!-- Address -->
            <div class="AddressField flex row">
              <div class="ServiceAddresss">
                <label>Region*</label>
                <q-select
                  v-model="personalDetails.bu_reg"
                  :options="regionOptions"
                  label="Region"
                  @input="onRegionChange()"
                  dense
                  outlined
                />
              </div>
              <div class="ServiceAddresss">
                <label>Province*</label>
                <q-select
                  v-model="personalDetails.bu_prov"
                  :options="provinceOptions"
                  label="Province"
                  dense
                  outlined
                  :disable="!selectedRegion"
                />
              </div>
              <div class="ServiceAddresss">
                <label>City*</label>
                <q-select
                  v-model="personalDetails.bu_city"
                  :options="cityOptions"
                  label="City"
                  dense
                  outlined
                  :disable="!selectedProvince"
                />
              </div>
              <div class="ServiceAddresss">
                <label>Barangay*</label>
                <q-select
                  v-model="personalDetails.bu_brgy"
                  :options="barangayOptions"
                  label="Barangay"
                  dense
                  outlined
                  :disable="!selectedCity"
                />
              </div>
            </div>

            <div class="assignment hidden">
              {{ (personalDetails.Region = grEGION) }}
              {{ (personalDetails.Province = gProvince) }}
              {{ (personalDetails.City = selectedCity) }}
              {{ (personalDetails.Barangay = brgy_g) }}
            </div>
          </div>
        </q-tab-panel>

        <!-- Witness -->
        <q-tab-panel name="witness">
          <div class="nav row justify-end q-gutter-sm">
            <div
              class="event_container q-pa-md"
              v-for="personalDetails in personaldetails"
              :key="personalDetails.bpid"
            >
              <div class="inputStep">
                <p class="q-mb-xs">Date of Death</p>
                <q-input dense outlined v-model="personalDetails.date_of_death">
                  <template v-slot:append>
                    <q-icon name="event" class="cursor-pointer">
                      <q-popup-proxy
                        cover
                        transition-show="scale"
                        transition-hide="scale"
                      >
                        <q-date
                          v-model="personalDetails.date_of_death"
                          mask="YYYY-MM-DD"
                        >
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

              <!-- Date of Burial -->
              <div class="inputStep">
                <p class="q-mb-xs">Date of Burial</p>
                <q-input
                  dense
                  outlined
                  v-model="personalDetails.date_of_burial"
                >
                  <template v-slot:append>
                    <q-icon name="event" class="cursor-pointer">
                      <q-popup-proxy
                        cover
                        transition-show="scale"
                        transition-hide="scale"
                      >
                        <q-date
                          v-model="personalDetails.date_of_burial"
                          mask="YYYY-MM-DD"
                        >
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

              <!-- Cause of Death -->
              <div class="inputStep">
                <p class="q-mb-xs">Cause of Death</p>
                <q-input
                  dense
                  outlined
                  v-model="personalDetails.cause_of_death"
                />
              </div>

              <!-- Place of Interment -->
              <div class="inputStep">
                <p class="q-mb-xs">Place of Interment</p>
                <q-select
                  dense
                  outlined
                  v-model="personalDetails.place_of_internment"
                  :options="InternmentPlaceList"
                  label="Select Place"
                />
              </div>
              <q-btn
                unelevated
                color="secondary"
                text-color="black"
                @click="() => (tab = 'personaldetails')"
                >Prev</q-btn
              >
              <q-btn
                unelevated
                color="primary"
                @click="() => (tab = 'seminarReq')"
                >Next</q-btn
              >
            </div>
          </div>
        </q-tab-panel>
        <!-- Requirements -->
        <q-tab-panel name="seminarReq">
          <div class="q-gutter-sm">
            <table border="1" style="width: 100%; text-align: center">
              <thead>
                <tr>
                  <th>List</th>
                  <th>Checkbox</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Baptismal Certificate</td>
                  <td>
                    <q-checkbox
                      v-model="C_requirementsList.Baptismal"
                      color="amber"
                      true-value="true"
                      false-value="no"
                      @update:model-value="
                        C_checkAllPropertiesTrue(C_requirementsList)
                      "
                    />
                  </td>
                </tr>
                <tr>
                  <td>Marriage License</td>
                  <td>
                    <q-checkbox
                      v-model="C_requirementsList.Marriage_License"
                      color="amber"
                      true-value="true"
                      false-value="no"
                      @update:model-value="
                        C_checkAllPropertiesTrue(C_requirementsList)
                      "
                    />
                  </td>
                </tr>
                <tr>
                  <td>Confirmation</td>
                  <td>
                    <q-checkbox
                      v-model="C_requirementsList.Confirmation"
                      color="amber"
                      true-value="true"
                      false-value="no"
                      @update:model-value="
                        C_checkAllPropertiesTrue(C_requirementsList)
                      "
                    />
                  </td>
                </tr>
                <tr>
                  <td>Birth Certificate</td>
                  <td>
                    <q-checkbox
                      v-model="C_requirementsList.LiveBirthCert"
                      color="amber"
                      true-value="true"
                      false-value="no"
                      @update:model-value="
                        C_checkAllPropertiesTrue(C_requirementsList)
                      "
                    />
                  </td>
                </tr>
                <tr>
                  <td>Cenomar</td>
                  <td>
                    <q-checkbox
                      v-model="C_requirementsList.Cenomar"
                      color="amber"
                      true-value="true"
                      false-value="no"
                      @update:model-value="
                        C_checkAllPropertiesTrue(C_requirementsList)
                      "
                    />
                  </td>
                </tr>
                <tr>
                  <td>Confession</td>
                  <td>
                    <q-checkbox
                      v-model="C_requirementsList.Confession"
                      color="amber"
                      true-value="true"
                      false-value="no"
                      @update:model-value="
                        C_checkAllPropertiesTrue(C_requirementsList)
                      "
                    />
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </q-tab-panel>
      </q-tab-panels>
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
    const emit = defineEmits(["closeDialog"]);

    let regionOptions = ref([]);
    let provinceOptions = ref([]);
    let cityOptions = ref([]);
    let barangayOptions = ref([]);
    let events = ref([props.editables[0]]);
    let personaldetails = ref([props.editables[0].burial]);

    let Requirement = ref([props.editables[0].Requirement]);

    let $q = useQuasar();
    console.log("event:", events.value);
    console.log("personaldetails:", personaldetails.value);
    console.log("Requirement:", Requirement.value);

    /**===================================================================== */
    // Convert event.Date to a Date object for manipulation
    let formattedDate = ref(events.value.Date); // Initialize formattedDate with the string date

    // Watch the formattedDate and update event.Date when it changes
    watch(formattedDate, (newDate) => {
      events.value.Date = newDate; // Update event.Date when formattedDate changes
    });

    /** ============================ Input functions  =================================*/
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

    const createValue = (val, done) => {
      if (val.length > 0) {
        const exists = stringOptions.value.some(
          (item) => item.event_name.toLowerCase() === val.toLowerCase()
        );

        if (!exists) {
          const newId = (stringOptions.value.length + 1).toString();
          const newItem = { etype_id: newId, event_name: val };
          stringOptions.value.push(newItem);
        }

        done(val, "toggle");
      }
    };

    const filterFn = (val, update, abort) => {
      update(() => {
        if (val === "") {
          filterOptions.value = [...stringOptions.value];
        } else {
          const needle = val.toLowerCase();
          filterOptions.value = stringOptions.value.filter((v) =>
            v.events_name.toLowerCase().includes(needle)
          );
        }
      });
    };

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
    const eventDetails = computed(() => ({
      date: events.value.Date,
      timeFrom: events.value.Time_from,
      timeTo: events.value.Time_to,
    }));
    watch(
      () => [events.value.Date, events.value.Time_from, events.value.Time_to],
      ([newDate, newTimeFrom, newTimeTo]) => {
        if (newDate && newTimeFrom && newTimeTo) {
          events.value.Assigned_Priest = null;
          getAvailablePriest(eventDetails.value);
          // Pass event details to function
        }
      }
    );
    // Get ADDRESS Groom
    let selectedRegion = ref(null);
    let grEGION = ref();
    let gProvince = ref();
    let selectedProvince = ref(null);
    let selectedCity = ref(null);
    let selectedBarangay = ref(null);

    onMounted(() => {
      console.log(philippineData);
      regionOptions.value = Object.keys(philippineData)
        .map((regionCode) => ({
          label: /^(0[1-9]|1[0-3]|[4][A-B]|[1-3]?[0-9])$/.test(regionCode)
            ? `Region ${regionCode}`
            : regionCode,
          value: regionCode,
        }))
        .sort((a, b) => a.label.localeCompare(b.label));
      console.log(regionOptions);
    });
    watch(selectedRegion, (newValue, oldValue) => {
      if (newValue !== oldValue) {
        onRegionChange();
      }
    });

    watch(selectedProvince, (newValue, oldValue) => {
      if (newValue !== oldValue) {
        onProvinceChange();
      }
    });

    watch(selectedCity, (newValue, oldValue) => {
      if (newValue !== oldValue) {
        onCityChange();
      }
    });

    watch(selectedBarangay, (newValue, oldValue) => {
      if (newValue !== oldValue) {
        onBarangayChange();
      }
    });
    const onRegionChange = () => {
      selectedProvince.value = null;
      selectedCity.value = null;
      selectedBarangay.value = null;

      let selectedR = selectedRegion.value.value;
      const regionData = philippineData[selectedR]?.province_list;

      provinceOptions.value = Object.keys(regionData).map((provinceName) => ({
        label: provinceName,
        value: provinceName,
      }));
      // updateSuppAddress();
      grEGION.value = selectedRegion.value.value;
    };

    const onProvinceChange = () => {
      if (selectedProvince.value !== null) {
        selectedCity.value = null;
        selectedBarangay.value = null;
        let selectedR = selectedRegion.value.value;
        let selectedP = selectedProvince.value.value;
        const selectedProvinceData =
          philippineData[selectedR]?.province_list[selectedP];
        if (selectedProvinceData) {
          const municipalityData = selectedProvinceData.municipality_list;
          cityOptions.value = Object.keys(municipalityData).map(
            (municipalityName) => ({
              label: municipalityName,
              value: municipalityName,
            })
          );
          gProvince.value = selectedProvince.value.value;
        }
      }
    };

    const onCityChange = () => {
      if (selectedCity.value !== null) {
        selectedBarangay.value = null;
        let selectedR = selectedRegion.value.value;
        let selectedP = selectedProvince.value.value;
        let selectedC = selectedCity.value.value;
        const selectedMunicipalityData =
          philippineData[selectedR]?.province_list[selectedP]
            ?.municipality_list[selectedC];
        if (selectedMunicipalityData) {
          const barangayList = selectedMunicipalityData.barangay_list;
          barangayOptions.value = barangayList.map((barangayName) => ({
            label: barangayName,
            value: barangayName,
          }));
          selectedCity.value = selectedCity.value.value;
        }
      }
    };
    let brgy_g = ref();
    const onBarangayChange = () => {
      let brgy = ref();
      if (selectedBarangay.value !== null) {
        brgy.value = selectedBarangay.value;
        brgy_g.value = brgy.value.value;
        // updateSuppAddress();
      }
    };

    /** requirement */
    let C_requirementsList = ref({
      req_id: "",
      Baptismal: "false",
      LiveBirthCert: "false",
      Cenomar: "false",
      Confession: "false",
      Confirmation: "false",
      Marriage_License: "false",
    });

    onMounted(() => {
      const data = Requirement.value[0];
      console.log("df", data[0]);
      // Map backend fields to local checkbox fields
      C_requirementsList.value.req_id = data[0].req_id;
      C_requirementsList.value.Baptismal = data[0].baptismal_certificate;
      C_requirementsList.value.LiveBirthCert = data[0].birth_certificate;
      C_requirementsList.value.Cenomar = data[0].cenomar;
      C_requirementsList.value.Confession = data[0].confession;
      C_requirementsList.value.Confirmation = data[0].confirmation;
      C_requirementsList.value.Marriage_License = data[0].marriage_license;
    });

    // seminar

    /** ================= edit variables ================================ */
    let event = ref([
      {
        event_id: null,
        Client: "",
        Service: null,
        Type: "",
        Date: "",
        Time_from: "",
        Time_to: "",
        Venuetype: null,
        Venue: "",
        Assigned_Priest: null,
        all: {
          duration: "",
        },
      },
    ]);
    /**  ==================== Save Edit Details  ============================= */
    const saveEditDetails = () => {
      console.log("Updated Event Info:", events.value);
      console.log("Updated Requirements:", C_requirementsList.value);

      // Optional: combine them for submission
      const payload = {
        event: events.value[0],
        requirements: C_requirementsList.value,
      };

      console.log("Final Payload:", payload);

      //== save api======
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
            .put("BaptismApi.php", { payload })
            .then((response) => {
              console.log(response);
            })
            .catch((error) => {
              console.log(error);
            });
          emit("closeDialog");
        })
        .onCancel(() => {
          emit("closeDialog");
        });
    };

    return {
      events,
      saveEditDetails,

      C_requirementsList,
      philippineData,
      selectedRegion,
      selectedProvince,
      selectedCity,
      selectedBarangay,
      provinceOptions,
      cityOptions,
      barangayOptions,
      regionOptions,
      onRegionChange,
      grEGION,
      gProvince,
      brgy_g,
      Address_A,
      setAddress,
      durationInMinutesEvent,
      filterOptions,
      event,
      personaldetails,

      Requirement,
      formattedDate,
      tab: ref("events"),
    };
  },
});
</script>
<style lang="css" scoped>
.requirements-table {
  width: 100%;
  border-collapse: collapse;
}

.requirements-table tr {
  border-bottom: 1px solid #ccc;
}

.label-column {
  text-align: left;
  padding: 10px;
  font-weight: bold;
  width: 70%;
}

.checkbox-column {
  text-align: right;
  padding: 10px;
  width: 30%;
}
.seminar-card {
  background: #f9f9f9;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  display: flex;
  align-items: center;
  justify-content: space-between;
  position: relative;
}

/* Equal Input Sizing */
.field {
  flex: 1;
  min-width: 160px;
}

.field label {
  display: block;
  font-size: 0.9rem;
  font-weight: 600;
  margin-bottom: 4px;
}

/* Remove Button */
.remove-btn {
  background: #e24144;
  color: white;
  transition: 0.3s ease-in-out;
  margin-left: auto;
}

.remove-btn:hover {
  background: #ff7875;
}
table {
  border-collapse: collapse;
  width: 100%;
}

th,
td {
  padding: 10px;
  text-align: left;
}

th {
  background-color: #f4f4f4;
  font-weight: bold;
}

td {
  border-top: 1px solid #ddd;
}

q-checkbox {
  margin-top: 5px;
}
</style>
