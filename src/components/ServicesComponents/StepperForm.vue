<template>
  <div class="Stepper">
    <!-- stepper form  -->
    <q-stepper
      v-model="step"
      ref="stepperRef"
      animated
      flat
      done-color="positive"
      active-color="primary"
      inactive-color="accent"
      class="StepperForm"
    >
      <!-- ===================================Event Details Start====================================== -->
      <q-step
        :name="1"
        title="Select Service"
        icon="settings"
        :done="step > 1"
        class="ScheduleData"
      >
        <!-- services form -->
        <div class="d-flex col align-items-start q-gutter-md">
          <div class="servicfield">
            Client Name
            <q-input
              outlined
              v-model="formData.Client"
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
                outlined
                ref="step1Ref"
                :dense="true"
                v-model="formData.Service"
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
                @update:model-value="customField(formData.Service)"
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

          <div class="servicfield" v-show="field1">
            Service Type
            <div class="q-gutter-sm">
              <q-radio
                v-model="formData.Type"
                val="Special"
                label="Special"
                @update:model-value="dateShow(formData.Type)"
              />
              <q-radio
                v-model="formData.Type"
                val="Mass"
                label="Mass"
                @update:model-value="dateShow(formData.Type)"
              />
            </div>
          </div>

          <div class="servicfield" v-show="field2">
            Type of Mass
            <q-select
              outlined
              v-model="formData.TypeofMass"
              :options="massOptions"
              :dense="true"
              class="input-field"
            />
          </div>
          <q-dialog
            v-model="massDialog"
            persistent
            transition-show="scale"
            transition-hide="scale"
          >
            <q-card style="min-width: 800px; max-width: 95vw">
              <q-card-section>
                <div class="text-h6">Select Mass Schedule</div>
              </q-card-section>

              <q-separator />

              <q-card-section>
                <q-table
                  :rows="massrows"
                  :columns="massColumns"
                  row-key="name"
                  @row-click="selectMassSchedule"
                >
                  <template v-slot:body-cell-priest="props">
                    <q-td :props="props">
                      <span v-if="props.row.priest_id">
                        {{ props.row.lname }}, {{ props.row.fname }}
                      </span>
                      <span v-else> Not yet assigned </span>
                    </q-td>
                  </template></q-table
                >
              </q-card-section>

              <q-separator />

              <q-card-actions align="right">
                <q-btn flat label="Close" color="primary" v-close-popup />
              </q-card-actions>
            </q-card>
          </q-dialog>
          <div class="servicfield" v-show="field2"></div>

          <div class="servicfield" v-show="field3">
            Specific Service
            <q-input
              outlined
              v-model="formData.Others"
              :dense="true"
              ref="step1Ref"
              :rules="[(val) => !!val || 'Field is required']"
              class="input-field"
            />
          </div>

          <div
            class="servicfield row q-col-gutter-md q-pa-md"
            v-show="dateField"
          >
            <!-- Date Field -->
            <div class="col-12 col-md-4">
              <div class="mb-1">Date of Event</div>
              <q-input
                class="w-full"
                ref="step1Ref"
                :dense="true"
                outlined
                v-model="formData.Date"
                :placeholder="currentDatePlaceholder"
              >
                <template v-slot:append>
                  <q-icon name="event" class="cursor-pointer">
                    <q-popup-proxy
                      cover
                      transition-show="scale"
                      transition-hide="scale"
                    >
                      <q-date mask="YYYY-MM-DD" v-model="formData.Date">
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

            <!-- Time From Field -->
            <div class="col-12 col-md-4">
              <div class="mb-1">Time From</div>
              <q-input
                class="w-full"
                outlined
                :dense="true"
                v-model="formData.TimeFrom"
                mask="time"
              >
                <template v-slot:append>
                  <q-icon name="access_time" class="cursor-pointer">
                    <q-popup-proxy
                      cover
                      transition-show="scale"
                      transition-hide="scale"
                    >
                      <q-time v-model="formData.TimeFrom">
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

            <!-- Time To Field -->
            <div class="col-12 col-md-4">
              <div class="mb-1">Time To</div>
              <q-input
                class="w-full"
                outlined
                :dense="true"
                v-model="formData.TimeTo"
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
                        v-model="formData.TimeTo"
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

            <!-- Priest List Button -->
            <div class="col-12 q-mt-sm" v-show="openPriestList">
              <q-btn
                color="primary"
                icon="list"
                @click="massDialog = true"
                label="Priest List"
              />
            </div>

            <!-- Hidden Duration Field -->
            <div class="col-12 timeDurat hidden">
              {{ (formData.Duration = timeDurationEvent) }}
            </div>
          </div>

          <!-- ==============================Venue Type================================= -->
          <div class="venue" v-show="venue">
            <p>Venue Type</p>
            <q-btn-toggle
              unelevated
              v-model="formData.Venue_type"
              toggle-color="primary"
              :options="[
                { label: 'Pastoral Center', value: '2' },
                { label: 'Church', value: '1' },
                { label: 'Outside', value: '3' },
              ]"
              @update:model-value="setAddress(formData.Venue_type, formData)"
            />
          </div>

          <div class="Venue" v-show="VenueTypeAddress">
            Venue Address
            <q-input
              outlined
              v-model="formData.Venue"
              :dense="true"
              :rules="[(val) => !!val || 'Field is required']"
              class="input-field"
            />
          </div>

          <!-- mass upload -->
          <div
            class="massUpload q-pa-md q-mt-md q-mb-md"
            v-show="MassUpload"
            style="border: 2px dotted grey; border-radius: 8px"
          >
            <!-- Upload prompt -->
            <div class="text-center q-mb-sm">
              <p class="text-h6 text-grey-8">Upload Files Here</p>
              <span class="text-blue cursor-pointer" @click="click()"
                >Click Here</span
              >
              to generate
            </div>

            <!-- File input -->
            <q-file
              v-model="excelData"
              label="Drop or select your files"
              use-chips
              multiple
              outlined
              accept=".csv,.xlsx"
              class="full-width q-mb-md"
            />

            <!-- Upload button -->
            <q-btn
              unelevated
              color="amber-5"
              label="Upload"
              @click="excelExport(excelData)"
              class="full-width q-mt-sm"
            />

            <!-- JSON Output -->
            <q-separator spaced class="q-mt-md" />
            <div
              class="bg-grey-1 q-pa-sm"
              style="overflow-x: auto; max-height: 300px"
            >
              <pre class="text-caption text-left">{{ jsonData }}</pre>
            </div>
          </div>
          <!-- Contact Number -->
          <div class="servicefield">
            Contact Number
            <q-input outlined v-model="formData.Contact_Number" dense>
              <template v-slot:prepend>
                <div class="text-body2">+63</div>
              </template>
            </q-input>
          </div>

          <div
            class="lastColumn flex justify-between q-mt-lg"
            style="width: 100%; display: flex"
          >
            <div
              class="AddressField flex row q-gutter-md"
              v-show="Address_A"
              style="width: 100%; display: flex"
            >
              <div
                class="ServiceAddresss col"
                style="flex: 1 1 25%; min-width: 0; padding-right: 10px"
              >
                <label>Region<span>*</span></label>
                <q-select
                  v-model="selectedARegion"
                  :options="regionOptions"
                  label="Region"
                  @input="onRegionChangeA()"
                  dense
                  outlined
                  class="full-width"
                  style="width: 100%"
                />
              </div>
              <div
                class="ServiceAddresss col"
                style="flex: 1 1 25%; min-width: 0; padding-right: 10px"
              >
                <label>Province<span>*</span></label>
                <q-select
                  v-model="selectedAProvince"
                  :options="provinceOptions"
                  label="Province"
                  dense
                  outlined
                  :disable="!selectedARegion"
                  class="full-width"
                  style="width: 100%"
                />
              </div>
              <div
                class="ServiceAddresss col"
                style="flex: 1 1 25%; min-width: 0; padding-right: 10px"
              >
                <label>City<span>*</span></label>
                <q-select
                  v-model="selectedACity"
                  :options="cityOptions"
                  label="City"
                  dense
                  outlined
                  :disable="!selectedAProvince"
                  class="full-width"
                  style="width: 100%"
                />
              </div>
              <div
                class="ServiceAddresss col"
                style="flex: 1 1 25%; min-width: 0"
              >
                <label>Barangay<span class="ServiceAddresss">*</span></label>
                <q-select
                  v-model="selectedABarangay"
                  :options="barangayOptions"
                  label="Barangay"
                  dense
                  outlined
                  :disable="!selectedACity"
                  class="full-width"
                  style="width: 100%"
                />
              </div>
            </div>
          </div>

          <div class="assignment hidden">
            {{ (formData.Event_Region = regionA) }}
            {{ (formData.Event_Province = ProvinceA) }}
            {{ (formData.Event_City = CityA) }}
            {{ (formData.Event_Barangay = BrgyA) }}
          </div>

          <div style="max-width: 100%" v-show="Description_Field">
            Description
            <q-input v-model="formData.Description" outlined type="textarea" />
          </div>

          <!-- Assigned Priest -->
          <div class="servicefield" v-show="Preffered_Priest">
            Assigned Priest
            <q-select
              :dense="true"
              outlined
              clearable
              ref="step1Ref"
              v-model="formData.Assigned_Priest"
              :options="
                availablePriest && availablePriest.length
                  ? availablePriest
                  : [{ priest_id: null, priest_name: 'No available priest' }]
              "
              option-value="priest_id"
              option-label="priest_name"
            />
          </div>
        </div>
      </q-step>

      <!-- ===================================Event Details End====================================== -->
      <q-step
        :name="2"
        title="Personal Details"
        icon="create_new_folder"
        :done="step > 2"
        v-if="stepper2panel"
      >
        <div class="d-flex col align-items-center q-gutter-xs">
          <div class="MarriageField" v-if="formData.Service == '1'">
            <div class="GroomSections">
              <!-- ============================Groom Information======================================= -->
              <h6 class="q-mb-md q-mt-sm">Groom Information</h6>
              <div class="Marriage flex justify-between q-gutter-xs">
                <div class="serviceField">
                  Last Name <span style="color: red">*</span>
                  <q-input
                    :dense="true"
                    ref="step2Ref"
                    v-model="marriageData.Groom_Last_Name"
                    outlined
                    :rules="[(val) => !!val || 'Field is required']"
                  />
                </div>
                <div class="serviceField">
                  First Name <span style="color: red">*</span>
                  <q-input
                    :dense="true"
                    ref="step2Ref"
                    v-model="marriageData.Groom_First_Name"
                    outlined
                    :rules="[(val) => !!val || 'Field is required']"
                  />
                </div>
                <div class="serviceField">
                  Middle Name
                  <q-input
                    :dense="true"
                    ref="step2Ref"
                    v-model="marriageData.Groom_Middle_Name"
                    outlined
                  />
                </div>
                <div class="serviceField">
                  Suffix Name
                  <q-input
                    :dense="true"
                    ref="step2Ref"
                    v-model="marriageData.Groom_Suffix"
                    outlined
                  />
                </div>
                <div class="serviceField q-mb-lg">
                  BirthDate <span style="color: red">*</span>
                  <q-input
                    :dense="true"
                    outlined
                    v-model="marriageData.Groom_BirthDate"
                  >
                    <template v-slot:append>
                      <q-icon name="event" class="cursor-pointer">
                        <q-popup-proxy
                          cover
                          transition-show="scale"
                          transition-hide="scale"
                        >
                          <q-date
                            mask="YYYY-MM-DD"
                            v-model="marriageData.Groom_BirthDate"
                            @update:model-value="
                              computeAge(
                                marriageData.Date_of_Marriage,
                                marriageData.Groom_BirthDate
                              )
                            "
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
                <div class="dateofMariiage hidden">
                  {{ (marriageData.Grooms_Age = Computedage) }}
                </div>
                <div class="serviceField">
                  Age <span style="color: red">*</span>
                  <q-input
                    :dense="true"
                    ref="step2Ref"
                    v-model="marriageData.Grooms_Age"
                    outlined
                  />
                </div>
                <div class="serviceField">
                  Status <span style="color: red">*</span>
                  <div class="status">
                    <q-radio
                      v-model="marriageData.Groom_Status"
                      val="0"
                      label="Widow"
                    />
                    <q-radio
                      v-model="marriageData.Groom_Status"
                      val="1"
                      label="Single"
                    />
                  </div>
                </div>
                <div class="serviceField">
                  Mother's Name <span style="color: red">*</span>
                  <q-input
                    :dense="true"
                    ref="step2Ref"
                    v-model="marriageData.Groom_Mother"
                    outlined
                    :rules="[(val) => !!val || 'Field is required']"
                  />
                </div>
                <div class="serviceField">
                  Father's Name <span style="color: red">*</span>
                  <q-input
                    :dense="true"
                    ref="step2Ref"
                    v-model="marriageData.Groom_Father"
                    outlined
                    :rules="[(val) => !!val || 'Field is required']"
                  />
                </div>

                <div class="row full-width">
                  <div class="serviceField">
                    <label>Region <span style="color: red">*</span></label>
                    <q-select
                      v-model="selectedRegion"
                      :options="regionOptions"
                      label="Region"
                      @input="onRegionChange()"
                      :dense="true"
                      outlined
                    />
                  </div>
                  <div class="serviceField">
                    <label>Province <span style="color: red">*</span></label>
                    <q-select
                      v-model="selectedProvince"
                      :options="provinceOptions"
                      label="Province"
                      :dense="true"
                      outlined
                      :disable="!selectedRegion"
                    />
                  </div>
                  <div class="serviceField" cols="4">
                    <label>City <span style="color: red">*</span></label>
                    <q-select
                      v-model="selectedCity"
                      :options="cityOptions"
                      label="City"
                      :dense="true"
                      outlined
                      :disable="!selectedProvince"
                    />
                  </div>
                  <div class="serviceField" cols="4">
                    <label>Barangay <span style="color: red">*</span></label>
                    <q-select
                      v-model="selectedBarangay"
                      :options="barangayOptions"
                      label="Barangay"
                      :dense="true"
                      outlined
                      :disable="!selectedCity"
                    />
                  </div>
                  <div class="assignment hidden">
                    {{ (marriageData.Groom_Region = grEGION) }}
                    {{ (marriageData.Groom_Province = gProvince) }}
                    {{ (marriageData.Groom_City = selectedCity) }}
                    {{ (marriageData.Groom_Barangay = brgy_g) }}
                  </div>
                </div>
              </div>

              <!-- gROOM eND -->

              <!-- Bride -->
              <h6 class="q-mb-md q-mt-sm q-mt-lg">Bride Information</h6>
              <div class="Marriage flex justify-between q-gutter-xs">
                <div class="serviceField">
                  Last Name
                  <q-input
                    :dense="true"
                    ref="step2Ref"
                    v-model="marriageData.Bride_Last_Name"
                    outlined
                    :rules="[(val) => !!val || 'Field is required']"
                  />
                </div>
                <div class="serviceField">
                  First Name
                  <q-input
                    :dense="true"
                    ref="step2Ref"
                    v-model="marriageData.Bride_First_Name"
                    outlined
                    :rules="[(val) => !!val || 'Field is required']"
                  />
                </div>
                <div class="serviceField">
                  Middle Name
                  <q-input
                    :dense="true"
                    ref="step2Ref"
                    v-model="marriageData.Bride_Middle_Name"
                    outlined
                    :rules="[(val) => !!val || 'Field is required']"
                  />
                </div>

                <div class="dateofMariiage hidden">
                  {{ (marriageData.Date_of_Marriage = formData.Date) }}
                  {{ (marriageData.Bride_Age = ComputedageBride) }}
                </div>
                <div class="serviceField q-mb-lg">
                  BirthDate

                  <q-input
                    :dense="true"
                    outlined
                    v-model="marriageData.Bride_BirthDate"
                    :rules="[(val) => !!val || 'Field is required']"
                  >
                    <template v-slot:append>
                      <q-icon name="event" class="cursor-pointer">
                        <q-popup-proxy
                          cover
                          transition-show="scale"
                          transition-hide="scale"
                        >
                          <q-date
                            mask="YYYY-MM-DD"
                            v-model="marriageData.Bride_BirthDate"
                            @update:model-value="
                              computeAgeBride(
                                marriageData.Date_of_Marriage,
                                marriageData.Bride_BirthDate
                              )
                            "
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
                <div class="dateofMariiage hidden">
                  {{ (marriageData.Bride_Age = ComputedageBride) }}
                </div>
                <div class="serviceField">
                  Age
                  <q-input
                    :dense="true"
                    ref="step2Ref"
                    v-model="marriageData.Bride_Age"
                    outlined
                  />
                </div>
                <div class="serviceField">
                  Status
                  <div class="status">
                    <q-radio
                      v-model="marriageData.Bride_Status"
                      val="0"
                      label="Widow"
                    />
                    <q-radio
                      v-model="marriageData.Bride_Status"
                      val="1"
                      label="Single"
                    />
                  </div>
                </div>
                <div class="serviceField">
                  Mother's Name
                  <q-input
                    :dense="true"
                    ref="step2Ref"
                    v-model="marriageData.Bride_Mother"
                    :rules="[(val) => !!val || 'Field is required']"
                    outlined
                  />
                </div>
                <div class="serviceField">
                  Father's Name
                  <q-input
                    :dense="true"
                    ref="step2Ref"
                    v-model="marriageData.Bride_Father"
                    :rules="[(val) => !!val || 'Field is required']"
                    outlined
                  />
                </div>
                <div class="lastColumn flex justify-between q-mt-lg">
                  <div class="AddressField flex row">
                    <div class="ServiceAddresss">
                      <label>Region<span>*</span></label>
                      <q-select
                        v-model="selectedRegionbride"
                        :options="regionOptions"
                        label="Region"
                        @input="onRegionChangeBride()"
                        dense
                        outlined
                        class="flex-wrap"
                      />
                    </div>
                    <div class="ServiceAddresss">
                      <label>Province<span>*</span></label>
                      <q-select
                        v-model="SelectedProvinceBride"
                        :options="provinceOptions"
                        label="Province"
                        dense
                        outlined
                        :disable="!selectedRegionbride"
                        class="flex-wrap"
                      />
                    </div>
                    <div class="ServiceAddresss">
                      <label>City<span>*</span></label>
                      <q-select
                        v-model="selectedCityBride"
                        :options="cityOptions"
                        label="City"
                        dense
                        outlined
                        :disable="!SelectedProvinceBride"
                        class="flex-wrap"
                      />
                    </div>
                    <div class="ServiceAddresss">
                      <label
                        >Barangay<span class="ServiceAddresss">*</span></label
                      >
                      <q-select
                        v-model="selectedBarangayBride"
                        :options="barangayOptions"
                        label="Barangay"
                        dense
                        outlined
                        :disable="!selectedCityBride"
                      />
                    </div>
                    <div class="assignment hidden">
                      {{ (marriageData.Bride_Region = regionB) }}
                      {{ (marriageData.Bride_Province = ProvinceB) }}
                      {{ (marriageData.Bride_City = CityB) }}
                      {{ (marriageData.Bride_Barangay = BrgyB) }}
                    </div>
                  </div>
                </div>
              </div>
              <!-- Bride End -->
            </div>
            <!-- ==============================Groom Information END=================================== -->
          </div>
          <div class="BaptismField" v-else-if="formData.Service == '2'">
            <div class="container-app q-gutter-lg flex q-pa-lg column">
              <div class="row q-col-gutter-md">
                <div class="col">
                  <p class="q-mb-xs">Last Name</p>
                  <q-input
                    dense
                    ref="step2Ref"
                    v-model="BaptismFormData.Last_Name"
                    outlined
                  />
                </div>
                <div class="col">
                  <p class="q-mb-xs">First Name</p>
                  <q-input
                    dense
                    ref="step2Ref"
                    v-model="BaptismFormData.First_Name"
                    outlined
                  />
                </div>
                <div class="col">
                  <p class="q-mb-xs">Middle Name</p>
                  <q-input
                    dense
                    ref="step2Ref"
                    v-model="BaptismFormData.Middle_Name"
                    outlined
                  />
                </div>
                <div class="col">
                  <p class="q-mb-xs">Suffix Name (Optional)</p>
                  <q-input
                    dense
                    ref="step2Ref"
                    v-model="BaptismFormData.Suffix"
                    outlined
                  />
                </div>
              </div>
              <div class="row q-col-gutter-md">
                <div class="col">
                  <p class="q-mb-xs">Gender</p>
                  <q-radio
                    size="sm"
                    v-model="BaptismFormData.Gender"
                    val="1"
                    label="Male"
                  />
                  <q-radio
                    size="sm"
                    v-model="BaptismFormData.Gender"
                    val="2"
                    label="Female"
                  />
                </div>
                <div class="col">
                  <p class="q-mb-xs">Birth Date</p>
                  <q-input dense outlined v-model="BaptismFormData.Birth_Date">
                    <template v-slot:append>
                      <q-icon name="event" class="cursor-pointer">
                        <q-popup-proxy
                          cover
                          transition-show="scale"
                          transition-hide="scale"
                        >
                          <q-date
                            v-model="BaptismFormData.Birth_Date"
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
                <div class="col">
                  <p class="q-mb-xs">Birth Place</p>
                  <q-input
                    outlined
                    v-model="BaptismFormData.Birth_Place"
                    dense
                  />
                </div>
              </div>

              <!-- Third Row: Parent's Names -->
              <div class="row q-col-gutter-md">
                <div class="col">
                  <p class="q-mb-xs">Father's Name</p>
                  <q-input
                    outlined
                    v-model="BaptismFormData.Father_Name"
                    dense
                  />
                </div>
                <div class="col">
                  <p class="q-mb-xs">Mother's Name</p>
                  <q-input
                    outlined
                    v-model="BaptismFormData.Mother_Name"
                    dense
                  />
                </div>
                <div class="col">
                  <p class="q-mb-xs">Legitimacy*</p>
                  <q-radio
                    name="shape"
                    color="amber"
                    v-model="BaptismFormData.Legitamacy"
                    val="0"
                    label="Illegal"
                  />
                  <q-radio
                    name="shape"
                    color="amber"
                    v-model="BaptismFormData.Legitamacy"
                    val="1"
                    label="Legal"
                  />
                </div>
              </div>

              <div class="row q-col-gutter-md">
                <div class="col">
                  <label>Region<span>*</span></label>
                  <q-select
                    v-model="selectedRegion"
                    :options="regionOptions"
                    label="Region"
                    @input="onRegionChange()"
                    dense
                    outlined
                  />
                </div>
              </div>
              <div class="row q-col-gutter-md">
                <div class="col">
                  <label>Province<span>*</span></label>
                  <q-select
                    v-model="selectedProvince"
                    :options="provinceOptions"
                    label="Province"
                    dense
                    outlined
                    :disable="!selectedRegion"
                  />
                </div>
              </div>
              <div class="row q-col-gutter-md">
                <div class="col">
                  <label>City<span>*</span></label>
                  <q-select
                    v-model="selectedCity"
                    :options="cityOptions"
                    label="City"
                    dense
                    outlined
                    :disable="!selectedProvince"
                  />
                </div>
              </div>
              <div class="row q-col-gutter-md">
                <div class="col">
                  <label>Barangay<span>*</span></label>
                  <q-select
                    v-model="selectedBarangay"
                    :options="barangayOptions"
                    label="Barangay"
                    dense
                    outlined
                    :disable="!selectedCity"
                  />
                </div>
              </div>

              <div class="assignment hidden">
                {{ (BaptismFormData.Region = grEGION) }}
                {{ (BaptismFormData.Province = gProvince) }}
                {{ (BaptismFormData.City = selectedCity) }}
                {{ (BaptismFormData.Barangay = brgy_g) }}
              </div>

              <div class="inputStep"></div>
            </div>
          </div>
          <div class="ConfirmationField" v-else-if="formData.Service == '3'">
            <div class="container-app q-gutter-md flex q-pa-lg justify-start">
              <div class="inputStep">
                <p class="q-mb-xs">Last Name</p>
                <q-input
                  :dense="true"
                  ref="step2Ref"
                  v-model="ConfirmationData.Last_Name"
                  outlined
                />
              </div>
              <div class="inputStep">
                <p class="q-mb-xs">First Name</p>
                <q-input
                  :dense="true"
                  ref="step2Ref"
                  v-model="ConfirmationData.First_Name"
                  outlined
                />
              </div>
              <div class="inputStep">
                <p class="q-mb-xs">Middle Name</p>
                <q-input
                  :dense="true"
                  ref="step2Ref"
                  v-model="ConfirmationData.Middle_Name"
                  outlined
                />
              </div>
              <div class="inputStep">
                <p class="q-mb-xs">Suffix Name(Optional)</p>
                <q-input
                  :dense="true"
                  ref="step2Ref"
                  v-model="ConfirmationData.Suffix"
                  outlined
                />
              </div>
              <div class="inputStep">
                <p class="q-mb-xs">Gender</p>
                <q-radio
                  size="xs"
                  v-model="ConfirmationData.Gender"
                  val="1"
                  label="Male"
                />
                <q-radio
                  size="xs"
                  v-model="ConfirmationData.Gender"
                  val="2"
                  label="Female"
                />
              </div>
              <div class="inputStep">
                <p class="text-grey-9 q-mb-xs text-subtitle">Birth Date</p>
                <q-input
                  :dense="true"
                  outlined
                  v-model="ConfirmationData.Birth_Date"
                >
                  <template v-slot:append>
                    <q-icon name="event" class="cursor-pointer">
                      <q-popup-proxy
                        cover
                        transition-show="scale"
                        transition-hide="scale"
                      >
                        <q-date
                          v-model="ConfirmationData.Birth_Date"
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
              <div class="inputStep">
                <p class="text-grey-9 q-mb-xs text-subtitle">Birth Place</p>
                <q-input
                  outlined
                  v-model="ConfirmationData.Birth_Place"
                  lazy-rules
                  :dense="true"
                />
              </div>
              <div class="inputStep">
                <p class="text-grey-9 q-mb-xs text-subtitle">Nationality</p>
                <q-select
                  outlined
                  v-model="ConfirmationData.Nationality"
                  :options="NationalityList"
                  :dense="true"
                />
              </div>

              <div class="inputStep">
                <p class="text-grey-9 q-mb-xs text-subtitle">Father's Name</p>
                <q-input
                  outlined
                  v-model="ConfirmationData.Father_Name"
                  lazy-rules
                  :dense="true"
                />
              </div>
              <div class="inputStep">
                <p class="text-grey-9 q-mb-xs text-subtitle">Mother's Name</p>
                <q-input
                  outlined
                  v-model="ConfirmationData.Mother_Name"
                  lazy-rules
                  :dense="true"
                />
              </div>
              <div class="inputStep">
                <p class="text-grey-9 q-mb-xs text-subtitle">Legitimacy*</p>
                <div class="listStatus q-ml-md">
                  <q-radio
                    name="shape"
                    color="amber"
                    v-model="ConfirmationData.Legitamacy"
                    val="0"
                    label="illegal"
                  />
                  <q-radio
                    name="shape"
                    color="amber"
                    v-model="ConfirmationData.Legitamacy"
                    val="1"
                    label="legal"
                  />
                </div>
              </div>
              <div class="AddressField flex row">
                <div class="ServiceAddresss">
                  <label>Region<span>*</span></label>
                  <q-select
                    v-model="selectedRegion"
                    :options="regionOptions"
                    label="Region"
                    @input="onRegionChange()"
                    dense
                    outlined
                    class="flex-wrap"
                  />
                </div>
                <div class="ServiceAddresss">
                  <label>Province<span>*</span></label>
                  <q-select
                    v-model="selectedProvince"
                    :options="provinceOptions"
                    label="Province"
                    dense
                    outlined
                    :disable="!selectedRegion"
                    class="flex-wrap"
                  />
                </div>
                <div class="ServiceAddresss">
                  <label>City<span>*</span></label>
                  <q-select
                    v-model="selectedCity"
                    :options="cityOptions"
                    label="City"
                    dense
                    outlined
                    :disable="!selectedProvince"
                    class="flex-wrap"
                  />
                </div>
                <div class="ServiceAddresss">
                  <label>Barangay<span class="ServiceAddresss">*</span></label>
                  <q-select
                    v-model="selectedBarangay"
                    :options="barangayOptions"
                    label="Barangay"
                    dense
                    outlined
                    :disable="!selectedCity"
                  />
                </div>
                <div class="assignment hidden">
                  {{ (ConfirmationData.Region = grEGION) }}
                  {{ (ConfirmationData.Province = gProvince) }}
                  {{ (ConfirmationData.City = selectedCity) }}
                  {{ (ConfirmationData.Barangay = brgy_g) }}
                </div>
              </div>
              <div class="inputStep"></div>
            </div>
          </div>
          <div class="BurialField" v-else-if="formData.Service == '4'">
            <div style="display: none">
              {{ (BurialData.EventScheduleID = randnum) }}
              {{ (formData.EventServiceID = randnum) }}
            </div>
            <div class="container-app q-gutter-md flex q-pa-lg justify-start">
              <div class="inputStep">
                <p class="q-mb-xs">Last Name</p>
                <q-input
                  :dense="true"
                  ref="step2Ref"
                  v-model="BurialData.Last_Name"
                  outlined
                />
              </div>
              <div class="inputStep">
                <p class="q-mb-xs">First Name</p>
                <q-input
                  :dense="true"
                  ref="step2Ref"
                  v-model="BurialData.First_Name"
                  outlined
                />
              </div>
              <div class="inputStep">
                <p class="q-mb-xs">Middle Name</p>
                <q-input
                  :dense="true"
                  ref="step2Ref"
                  v-model="BurialData.Middle_Name"
                  outlined
                />
              </div>
              <div class="inputStep">
                <p class="q-mb-xs">Suffix Name(Optional)</p>
                <q-input
                  :dense="true"
                  ref="step2Ref"
                  v-model="BurialData.Suffix"
                  outlined
                />
              </div>
              <div class="inputStep">
                <p class="q-mb-xs">Gender</p>
                <q-radio
                  size="xs"
                  v-model="BurialData.Gender"
                  val="1"
                  label="Male"
                />
                <q-radio
                  size="xs"
                  v-model="BurialData.Gender"
                  val="2"
                  label="Female"
                />
              </div>
              <div class="inputStep">
                <p class="text-grey-9 q-mb-xs text-subtitle">Birth Date</p>
                <q-input :dense="true" outlined v-model="BurialData.Birth_Date">
                  <template v-slot:append>
                    <q-icon name="event" class="cursor-pointer">
                      <q-popup-proxy
                        cover
                        transition-show="scale"
                        transition-hide="scale"
                      >
                        <q-date
                          v-model="BurialData.Birth_Date"
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

              <div class="inputStep">
                Age
                <q-input
                  :dense="true"
                  ref="step2Ref"
                  v-model="BurialData.Age"
                  outlined
                />
              </div>
              <div class="inputStep">
                <p class="text-grey-9 q-mb-xs text-subtitle">Birth Place</p>
                <q-input
                  outlined
                  v-model="BurialData.Birth_Place"
                  lazy-rules
                  :dense="true"
                />
              </div>
              <div class="inputStep">
                Status
                <div class="status">
                  <q-radio v-model="BurialData.Status" val="0" label="Widow" />
                  <q-radio v-model="BurialData.Status" val="1" label="Single" />
                </div>
                <div class="inputStep">
                  <p class="text-grey-9 q-mb-xs text-subtitle">Father's Name</p>
                  <q-input
                    outlined
                    v-model="BurialData.Father_Name"
                    lazy-rules
                    :dense="true"
                  />
                </div>
                <div class="inputStep">
                  <p class="text-grey-9 q-mb-xs text-subtitle">Mother's Name</p>
                  <q-input
                    outlined
                    v-model="BurialData.Mother_Name"
                    lazy-rules
                    :dense="true"
                  />
                </div>
                <div class="inputStep">
                  <p class="text-grey-9 q-mb-xs text-subtitle">Spouse</p>
                  <q-input
                    outlined
                    v-model="BurialData.Spouse"
                    lazy-rules
                    :dense="true"
                  />
                </div>

                <div class="inputStep">
                  <p class="text-grey-9 q-mb-xs text-subtitle">Nationality</p>
                  <q-select
                    outlined
                    v-model="BurialData.Nationality"
                    :options="NationalityList"
                    :dense="true"
                  />
                </div>
                <div class="AddressField flex row">
                  <div class="ServiceAddresss">
                    <label>Region<span>*</span></label>
                    <q-select
                      v-model="selectedRegion"
                      :options="regionOptions"
                      label="Region"
                      @input="onRegionChange()"
                      dense
                      outlined
                      class="flex-wrap"
                    />
                  </div>
                  <div class="ServiceAddresss">
                    <label>Province<span>*</span></label>
                    <q-select
                      v-model="selectedProvince"
                      :options="provinceOptions"
                      label="Province"
                      dense
                      outlined
                      :disable="!selectedRegion"
                      class="flex-wrap"
                    />
                  </div>
                  <div class="ServiceAddresss">
                    <label>City<span>*</span></label>
                    <q-select
                      v-model="selectedCity"
                      :options="cityOptions"
                      label="City"
                      dense
                      outlined
                      :disable="!selectedProvince"
                      class="flex-wrap"
                    />
                  </div>
                  <div class="ServiceAddresss">
                    <label
                      >Barangay<span class="ServiceAddresss">*</span></label
                    >
                    <q-select
                      v-model="selectedBarangay"
                      :options="barangayOptions"
                      label="Barangay"
                      dense
                      outlined
                      :disable="!selectedCity"
                    />
                  </div>
                  <div class="assignment hidden">
                    {{ (BurialData.Region = grEGION) }}
                    {{ (BurialData.Province = gProvince) }}
                    {{ (BurialData.City = selectedCity) }}
                    {{ (BurialData.Barangay = brgy_g) }}
                  </div>
                </div>
                <div class="inputStep"></div>
              </div>
            </div>
          </div>
          <div class="Others" v-else>
            <div class="DtailsField">
              <div class="servicfield">
                Event Description
                <q-input
                  outlined
                  v-model="formData.Client"
                  :dense="true"
                  :rules="[(val) => !!val || 'Field is required']"
                />
              </div>
            </div>
          </div>
        </div>
      </q-step>

      <q-step
        :name="3"
        :title="stepTitle"
        icon="add_comment"
        :done="step > 3"
        v-if="stepperWitnesspanel"
      >
        <!-- =====================================Marriage God Parents============================ -->
        <div class="d-flex col align-items-center">
          <div class="MarriageField q-pa-xs" v-if="formData.Service == '1'">
            <!-- Header -->
            <div class="header flex justify-between items-center">
              <h6 class="q-mb-md q-mt-sm text-primary">Marriage Witness</h6>
              <q-btn
                unelevated
                color="primary"
                @click="addCard"
                size="md"
                icon="add"
                label="Add more"
              />
            </div>

            <!-- Witness Cards -->
            <div class="marriageInfo column">
              <div
                v-for="(card, index) in cards"
                :key="index"
                class="cardWitness q-pa-sm"
              >
                <q-card flat bordered class="shadow-1">
                  <q-card-section class="flex items-center q-gutter-md">
                    <!-- Groom Witness -->
                    <q-input
                      v-model="card.field1"
                      label="Groom Witness"
                      dense
                      outlined
                      class="input-width"
                    />
                    <!-- Groom Address -->
                    <q-input
                      v-model="card.field2"
                      label="Address"
                      dense
                      outlined
                      class="input-width"
                    />
                    <!-- Bride Testium -->
                    <q-input
                      v-model="card.field3"
                      label="Bride Testium"
                      dense
                      outlined
                      class="input-width"
                    />
                    <!-- Bride Address -->
                    <q-input
                      v-model="card.field4"
                      label="Address"
                      dense
                      outlined
                      class="input-width"
                    />
                    <!-- Remove Button -->

                    <q-btn
                      flat
                      color="negative"
                      icon="delete"
                      @click="removeCard(index)"
                      :disable="cards.length <= 1"
                    />
                  </q-card-section>
                </q-card>
              </div>
            </div>
          </div>

          <div class="MarriageField q-pa-md" v-if="formData.Service == '2'">
            <!-- Header -->
            <div class="header flex justify-between items-center">
              <h6 class="q-mb-md q-mt-sm text-primary">
                God Parents Information
              </h6>
              <q-btn
                rounded
                unelevated
                color="primary"
                @click="addCard_B"
                size="md"
                icon="add"
                label="Add more"
              />
            </div>

            <!-- God Parents Cards -->
            <div class="marriageInfo column q-gutter-md">
              <div
                v-for="(card, index) in cards_B"
                :key="index"
                class="cardWitness q-pa-sm"
              >
                <q-card flat bordered class="q-pa-sm shadow-1">
                  <q-card-section class="flex items-center q-gutter-md">
                    <!-- Ninong -->
                    <q-input
                      v-model="card.field1"
                      label="Ninong"
                      dense
                      outlined
                      class="input-width"
                    />
                    <!-- Ninong Address -->
                    <q-input
                      v-model="card.field2"
                      label="Address"
                      dense
                      outlined
                      class="input-width"
                    />
                    <!-- Ninang -->
                    <q-input
                      v-model="card.field3"
                      label="Ninang"
                      dense
                      outlined
                      class="input-width"
                    />
                    <!-- Ninang Address -->
                    <q-input
                      v-model="card.field4"
                      label="Address"
                      dense
                      outlined
                      class="input-width"
                    />
                    <!-- Remove Button -->
                    <q-btn
                      flat
                      color="negative"
                      icon="delete"
                      @click="removeCard_B(index)"
                      :disable="cards_B.length <= 1"
                    />
                  </q-card-section>
                </q-card>
              </div>
            </div>
          </div>

          <div class="MarriageField" v-if="formData.Service == '3'">
            <div class="header flex justify-between">
              <h6 class="q-mb-md q-mt-sm">Witness Information</h6>
              <q-btn
                rounded
                unelevated
                @click="addCard_B"
                size="md"
                icon="add"
                label="Add more"
              ></q-btn>
            </div>
            <div class="marriageInfo flex justify-center q-gutter-md">
              <div>
                <div
                  class="cardwITNESS"
                  v-for="(card, index) in cards_B"
                  :key="index"
                >
                  <div class="flex q-gutter-md">
                    <div class="q-mb-sm">
                      God Parent (Ninong)
                      <q-input
                        v-model="card.field1"
                        :dense="true"
                        outlined
                      ></q-input>
                    </div>
                    <div>
                      Address
                      <q-input
                        v-model="card.field2"
                        :dense="true"
                        outlined
                      ></q-input>
                    </div>
                    <!-- bride -->
                    <div>
                      God Parent (Ninang)
                      <q-input
                        v-model="card.field3"
                        :dense="true"
                        outlined
                      ></q-input>
                    </div>
                    <div>
                      Address
                      <q-input
                        v-model="card.field4"
                        :dense="true"
                        outlined
                      ></q-input>
                    </div>
                    <q-btn
                      outlined
                      unelevated
                      @click="removeCard_B"
                      size="sm"
                      icon="delete"
                      :disable="cardValues_B.length <= 1 ? true : false"
                    ></q-btn>
                  </div>
                </div>
                <div style="display: none">
                  {{ (ConfirmationData.Witness = cardValues_B) }}
                </div>
              </div>
            </div>
          </div>
          <div class="Field" v-if="formData.Service == '4'">
            <div class="inputStep">
              <p class="text-grey-9 q-mb-xs text-subtitle">Date of Death</p>
              <q-input
                :dense="true"
                outlined
                v-model="BurialData.Date_of_Death"
              >
                <template v-slot:append>
                  <q-icon name="event" class="cursor-pointer">
                    <q-popup-proxy
                      cover
                      transition-show="scale"
                      transition-hide="scale"
                    >
                      <q-date
                        v-model="BurialData.Date_of_Death"
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
            <div class="venue" v-show="venue">
              <p>Burial Options</p>
              <q-btn-toggle
                unelevated
                v-model="BurialData.Burial_Arrangement"
                toggle-color="primary"
                :options="[
                  { label: 'Cremation', value: '1' },
                  { label: 'Traditional', value: '2' },
                ]"
                @update:model-value="
                  setBurialOptions(BurialData.Burial_Arrangement, BurialData)
                "
              />
            </div>

            <div class="inputStep">
              <p class="text-grey-9 q-mb-xs text-subtitle">Date of Burial</p>
              <q-input
                :dense="true"
                outlined
                v-model="BurialData.Date_of_Burial"
              >
                <template v-slot:append>
                  <q-icon name="event" class="cursor-pointer">
                    <q-popup-proxy
                      cover
                      transition-show="scale"
                      transition-hide="scale"
                    >
                      <q-date
                        v-model="BurialData.Date_of_Burial"
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
            <div class="inputStep">
              <p class="text-grey-9 q-mb-xs text-subtitle">Cause of Death</p>
              <q-input
                outlined
                v-model="BurialData.Cause_of_Death"
                lazy-rules
                :dense="true"
              />
            </div>
            <div class="inputStep">
              <p class="text-grey-9 q-mb-xs text-subtitle">
                Place of Interment
              </p>
              <q-select
                outlined
                v-model="BurialData.Place_of_Interment"
                :options="Interment"
              />
            </div>
          </div>
        </div>
      </q-step>
      <q-step
        :name="4"
        :title="stepTitle2"
        icon="add_comment"
        :done="step > 4"
        v-if="stepper3panel"
      >
        <!-- =====================================Marriage God Parents============================ -->
        <div class="d-flex col align-items-center q-gutter-xs">
          <div class="MarriageField" v-if="formData.Service == '1'">
            <div
              class="header justify-between"
              v-if="formData.Type == 'Special'"
            >
              <h6 class="q-mb-md q-mt-sm">
                Documentary Requirements Checklist
              </h6>
              <div class="q-gutter-sm">
                <table class="requirements-table">
                  <tbody>
                    <tr>
                      <td class="label-column">Baptismal Certificate</td>
                      <td class="checkbox-column">
                        <q-checkbox
                          v-model="requirementsList.Baptismal"
                          color="amber"
                          true-value="true"
                          false-value="false"
                          @update:model-value="
                            checkAllPropertiesTrue(requirementsList)
                          "
                        />
                      </td>
                    </tr>

                    <tr>
                      <td class="label-column">Marriage License</td>
                      <td class="checkbox-column">
                        <q-checkbox
                          v-model="requirementsList.Marriage_License"
                          color="amber"
                          true-value="true"
                          false-value="false"
                          @update:model-value="
                            checkAllPropertiesTrue(requirementsList)
                          "
                        />
                      </td>
                    </tr>

                    <tr>
                      <td class="label-column">Confirmation</td>
                      <td class="checkbox-column">
                        <q-checkbox
                          v-model="requirementsList.Confirmation"
                          color="amber"
                          true-value="true"
                          false-value="false"
                          @update:model-value="
                            checkAllPropertiesTrue(requirementsList)
                          "
                        />
                      </td>
                    </tr>

                    <tr>
                      <td class="label-column">Birth Certificate</td>
                      <td class="checkbox-column">
                        <q-checkbox
                          v-model="requirementsList.LiveBirthCert"
                          color="amber"
                          true-value="true"
                          false-value="false"
                          @update:model-value="
                            checkAllPropertiesTrue(requirementsList)
                          "
                        />
                      </td>
                    </tr>

                    <tr>
                      <td class="label-column">Cenomar</td>
                      <td class="checkbox-column">
                        <q-checkbox
                          v-model="requirementsList.Cenomar"
                          color="amber"
                          true-value="true"
                          false-value="false"
                          @update:model-value="
                            checkAllPropertiesTrue(requirementsList)
                          "
                        />
                      </td>
                    </tr>

                    <tr>
                      <td class="label-column">Interrogation</td>
                      <td class="checkbox-column">
                        <q-checkbox
                          v-model="requirementsList.Interogation"
                          color="amber"
                          true-value="true"
                          false-value="false"
                          @update:model-value="
                            checkAllPropertiesTrue(requirementsList)
                          "
                        />
                      </td>
                    </tr>

                    <tr>
                      <td class="label-column">PreCana Seminar</td>
                      <td class="checkbox-column">
                        <q-checkbox
                          v-model="requirementsList.PreCana"
                          color="amber"
                          true-value="true"
                          false-value="false"
                          @update:model-value="
                            checkAllPropertiesTrue(requirementsList)
                          "
                        />
                      </td>
                    </tr>

                    <tr>
                      <td class="label-column">Confession</td>
                      <td class="checkbox-column">
                        <q-checkbox
                          v-model="requirementsList.Confession"
                          color="amber"
                          true-value="true"
                          false-value="false"
                          @update:model-value="
                            checkAllPropertiesTrue(requirementsList)
                          "
                        />
                      </td>
                    </tr>
                  </tbody>
                </table>

                <div class="Status" style="display: block">
                  {{ requirementsList }}
                  {{ reqstats }}
                  {{ (marriageData.Requirement = requirementsList) }}

                  <div class="status" v-if="reqstats == 0">
                    {{ (marriageData.EventProgress = "Pending") }},
                    {{ (marriageData.Status = "Incomplete") }}
                  </div>
                  <div class="status" v-if="reqstats == 1">
                    {{ (marriageData.EventProgress = "Pending") }},
                    {{ (marriageData.Status = "Complete") }}
                  </div>
                </div>
              </div>
            </div>

            <div class="q-mt-lg">
              <!-- Header Section -->
              <div class="row items-center justify-between q-mb-md">
                <h6 class="text-h6">Seminars</h6>
                <q-btn
                  rounded
                  unelevated
                  @click="addScheduleCard()"
                  icon="add"
                  label="Add Schedule"
                  color="primary"
                />
              </div>

              <!-- Table Headers -->
              <div
                class="row q-px-sm bg-grey-3 text-weight-medium text-caption q-py-sm q-mb-xs"
              >
                <div class="col-12 col-sm-2">Title</div>
                <div class="col-12 col-sm-2">Date</div>
                <div class="col-6 col-sm-2">Start Time</div>
                <div class="col-6 col-sm-2">End Time</div>
                <div class="col-12 col-sm-3">Venue</div>
                <div class="col-12 col-sm-1 text-center">Action</div>
              </div>

              <!-- Table Rows (Seminar Entries) -->
              <div
                v-for="(cardSeminar, index) in Schedulecards"
                :key="index"
                class="row q-col-gutter-sm q-pa-sm items-center bg-grey-1 q-mb-xs"
              >
                <!-- Title -->
                <div class="col-12 col-sm-2">
                  <q-input
                    dense
                    outlined
                    v-model="cardSeminar.field1"
                    placeholder="Seminar Title"
                  />
                </div>

                <!-- Date -->
                <div class="col-12 col-sm-2">
                  <q-input dense outlined v-model="cardSeminar.field2">
                    <template v-slot:prepend>
                      <q-icon name="event" class="cursor-pointer">
                        <q-popup-proxy
                          cover
                          transition-show="scale"
                          transition-hide="scale"
                        >
                          <q-date
                            mask="YYYY-MM-DD"
                            v-model="cardSeminar.field2"
                            @update:model-value="durationSeminar(cardSeminar)"
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

                <!-- Start Time -->
                <div class="col-6 col-sm-2">
                  <q-input dense outlined v-model="cardSeminar.field3">
                    <template v-slot:append>
                      <q-icon name="access_time" class="cursor-pointer">
                        <q-popup-proxy
                          cover
                          transition-show="scale"
                          transition-hide="scale"
                        >
                          <q-time v-model="cardSeminar.field3">
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

                <!-- End Time -->
                <div class="col-6 col-sm-2">
                  <q-input dense outlined v-model="cardSeminar.field4">
                    <template v-slot:append>
                      <q-icon name="access_time" class="cursor-pointer">
                        <q-popup-proxy
                          cover
                          transition-show="scale"
                          transition-hide="scale"
                        >
                          <q-time
                            v-model="cardSeminar.field4"
                            @update:model-value="
                              durationInMinutesSeminar(cardSeminar)
                            "
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

                <!-- Venue -->
                <div class="col-12 col-sm-3">
                  <q-input
                    dense
                    outlined
                    v-model="cardSeminar.field7"
                    placeholder="Venue"
                  />
                </div>

                <!-- Remove Button -->
                <div class="col-12 col-sm-1 text-center">
                  <q-btn
                    icon="delete"
                    color="red"
                    size="sm"
                    unelevated
                    dense
                    @click="RemoveScheduleCard(Schedulecards)"
                    :disable="Schedulecards.length <= 1"
                  />
                </div>

                <!-- Hidden Data Fields -->
                <div class="scheduleData hidden">
                  {{ SchedulecardValues }}
                  {{ (marriageData.Witness = cardValues) }}
                  {{ SeminarDay }}
                  {{ timeDurationSeminar }}
                  <div style="display: none">
                    {{ SchedulecardValues }}
                    {{ (cardSeminar.field5 = SeminarDay) }}
                    {{ (cardSeminar.field6 = timeDurationSeminar) }}
                    {{ (marriageData.SeminarDetails = SchedulecardValues) }}
                    {{ (formData.EventServiceID = randnum) }}
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- ======================= BAPTISM DATA ====================================== -->
          <div class="MarriageField" v-if="formData.Service == '2'">
            <div
              class="header justify-between"
              v-if="formData.Type == 'Special'"
            >
              <h6 class="q-mb-md q-mt-sm">
                Documentary Requirements Checklist
              </h6>
              <div class="q-gutter-sm">
                <table
                  class="q-table"
                  style="
                    border: 1px solid black;
                    width: 100%;
                    border-collapse: collapse;
                    text-align: center;
                  "
                >
                  <thead>
                    <tr>
                      <th style="border: 1px solid black; padding: 8px">
                        Requirement
                      </th>
                      <th style="border: 1px solid black; padding: 8px">
                        Check
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td style="border: 1px solid black; padding: 8px">
                        Marriage License
                      </td>
                      <td
                        style="
                          border: 1px solid black;
                          padding: 8px;
                          text-align: center;
                        "
                      >
                        <q-checkbox
                          v-model="B_requirementsList.Marriage_License"
                          color="amber"
                          true-value="true"
                          false-value="false"
                          @update:model-value="
                            B_checkAllPropertiesTrue(B_requirementsList)
                          "
                        />
                      </td>
                    </tr>
                    <tr>
                      <td style="border: 1px solid black; padding: 8px">
                        Confirmation
                      </td>
                      <td
                        style="
                          border: 1px solid black;
                          padding: 8px;
                          text-align: center;
                        "
                      >
                        <q-checkbox
                          v-model="B_requirementsList.Confirmation"
                          color="amber"
                          true-value="true"
                          false-value="false"
                          @update:model-value="
                            B_checkAllPropertiesTrue(B_requirementsList)
                          "
                        />
                      </td>
                    </tr>
                    <tr>
                      <td style="border: 1px solid black; padding: 8px">
                        Birth Certificate
                      </td>
                      <td
                        style="
                          border: 1px solid black;
                          padding: 8px;
                          text-align: center;
                        "
                      >
                        <q-checkbox
                          v-model="B_requirementsList.LiveBirthCert"
                          color="amber"
                          true-value="true"
                          false-value="false"
                          @update:model-value="
                            B_checkAllPropertiesTrue(B_requirementsList)
                          "
                        />
                      </td>
                    </tr>
                    <tr>
                      <td style="border: 1px solid black">Confession</td>
                      <td
                        style="
                          border: 1px solid black;
                          padding: 8px;
                          text-align: center;
                        "
                      >
                        <q-checkbox
                          v-model="B_requirementsList.Confession"
                          color="amber"
                          true-value="true"
                          false-value="false"
                          @update:model-value="
                            B_checkAllPropertiesTrue(B_requirementsList)
                          "
                        />
                      </td>
                    </tr>
                  </tbody>
                </table>

                <div class="Status" style="display: none">
                  {{ B_requirementsList }}
                  {{ B_reqstats }}
                  {{ (BaptismFormData.Requirement = B_requirementsList) }}

                  <div class="status" v-if="B_reqstats == 0">
                    {{ (BaptismFormData.EventProgress = "Pending") }},
                    {{ (BaptismFormData.Status = "Incomplete") }}
                  </div>
                  <div class="status" v-if="B_reqstats == 1">
                    {{ (BaptismFormData.EventProgress = "Pending") }},
                    {{ (BaptismFormData.Status = "Complete") }}
                  </div>
                </div>
              </div>
            </div>

            <div class="header q-mt-lg justify-between">
              <div class="header flex justify-between">
                <h6 class="q-mb-md q-mt-sm">Seminars</h6>
                <q-btn
                  rounded
                  unelevated
                  @click="addScheduleCard()"
                  size="md"
                  icon="add"
                  label="add Schedule"
                ></q-btn>
              </div>
              <div class="q-gutter-sm">
                <div class="Requirements q-pa-sm q-mb-sm">
                  <div class="marriageInfo">
                    <div
                      class="cardwITNESS"
                      v-for="(cardSeminar, index) in Schedulecards"
                      :key="index"
                    >
                      <div class="flex q-gutter-md items-center">
                        <div>
                          Title
                          <q-input
                            ref="step4Ref"
                            v-model="cardSeminar.field1"
                            dense
                            outlined
                            style="width: 700px"
                          ></q-input>
                        </div>
                        <div>
                          Date
                          <q-input dense outlined v-model="cardSeminar.field2">
                            <template v-slot:prepend>
                              <q-icon name="event" class="cursor-pointer">
                                <q-popup-proxy
                                  cover
                                  transition-show="scale"
                                  transition-hide="scale"
                                >
                                  <q-date
                                    @update:model-value="
                                      durationSeminar(cardSeminar)
                                    "
                                    v-model="cardSeminar.field2"
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
                        <div>
                          Time Start
                          <q-input
                            outlined
                            v-model="cardSeminar.field3"
                            mask="time"
                            dense
                          >
                            <template v-slot:append>
                              <q-icon name="access_time" class="cursor-pointer">
                                <q-popup-proxy
                                  cover
                                  transition-show="scale"
                                  transition-hide="scale"
                                >
                                  <q-time v-model="cardSeminar.field3">
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
                        <div>
                          Time End
                          <q-input
                            outlined
                            v-model="cardSeminar.field4"
                            mask="time"
                            dense
                          >
                            <template v-slot:append>
                              <q-icon name="access_time" class="cursor-pointer">
                                <q-popup-proxy
                                  cover
                                  transition-show="scale"
                                  transition-hide="scale"
                                >
                                  <q-time
                                    v-model="cardSeminar.field4"
                                    @update:model-value="
                                      durationInMinutesSeminar(cardSeminar)
                                    "
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
                        <div>
                          Venue
                          <q-input
                            ref="step4Ref"
                            v-model="cardSeminar.field7"
                            dense
                            outlined
                            style="width: 700px"
                          ></q-input>
                        </div>
                        <div class="scheduleData" style="display: none">
                          {{ SchedulecardValues }}
                          {{ (BaptismFormData.BaptismWitness = cardValues_B) }}
                          {{ SeminarDay }}
                          {{ timeDurationSeminar }}
                          <div>
                            {{ SchedulecardValues }}
                            {{ (cardSeminar.field5 = SeminarDay) }}
                            {{ (cardSeminar.field6 = timeDurationSeminar) }}
                            {{
                              (BaptismFormData.SeminarDetails =
                                SchedulecardValues)
                            }}
                            <!-- {{ (BaptismFormData.EventServiceID = randnum) }} -->
                          </div>
                        </div>
                        <q-btn
                          rounded
                          unelevated
                          @click="RemoveScheduleCard(Schedulecards)"
                          size="md"
                          icon="delete"
                          :disable="Schedulecards.length <= 1"
                          class="q-ml-auto"
                        />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- =========================Confirmation Data ===================================== -->
          <div class="MarriageField" v-if="formData.Service == '3'">
            <div class="header justify-between">
              <h6 class="q-mb-md q-mt-sm">
                Documentary Requirements Checklist
              </h6>
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
                          label="Baptismal Certificate"
                          color="amber"
                          true-value="true"
                          false-value="false"
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
                          label="Marriage License"
                          color="amber"
                          true-value="true"
                          false-value="false"
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
                          label="Confirmation"
                          color="amber"
                          true-value="true"
                          false-value="false"
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
                          label="Birth Certificate"
                          color="amber"
                          true-value="true"
                          false-value="false"
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
                          label="Cenomar"
                          color="amber"
                          true-value="true"
                          false-value="false"
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
                          label="Confession"
                          color="amber"
                          true-value="true"
                          false-value="false"
                          @update:model-value="
                            C_checkAllPropertiesTrue(C_requirementsList)
                          "
                        />
                      </td>
                    </tr>
                  </tbody>
                </table>

                <div class="Status" style="display: none">
                  {{ C_requirementsList }}
                  {{ C_reqstats }}
                  {{ (ConfirmationData.Requirement = C_requirementsList) }}

                  <div class="status" v-if="C_reqstats == 0">
                    {{ (ConfirmationData.EventProgress = "Pending") }},
                    {{ (ConfirmationData.Status = "Incomplete") }}
                  </div>
                  <div class="status" v-if="C_reqstats == 1">
                    {{ (ConfirmationData.EventProgress = "Pending") }},
                    {{ (ConfirmationData.Status = "Complete") }}
                  </div>
                </div>
                {{ ConfirmationData.Status }}
              </div>
            </div>

            <div class="header q-mt-lg justify-between">
              <div class="header flex justify-between">
                <h6 class="q-mb-md q-mt-sm">Seminars</h6>
                <q-btn
                  rounded
                  unelevated
                  @click="addScheduleCard()"
                  size="md"
                  icon="add"
                  label="add Schedule"
                ></q-btn>
              </div>
              <div class="q-gutter-sm">
                <div class="Requirements q-pa-sm q-mb-sm">
                  <div class="marriageInfo">
                    <div
                      class="cardwITNESS"
                      v-for="(cardSeminar, index) in Schedulecards"
                      :key="index"
                    >
                      <div class="flex q-gutter-md">
                        <div class="q-mb-sm">
                          Title
                          <q-input
                            ref="step4Ref"
                            v-model="cardSeminar.field1"
                            :dense="true"
                            outlined
                            width="50"
                          ></q-input>
                        </div>

                        <div>
                          Date
                          <q-input
                            :dense="true"
                            outlined
                            v-model="cardSeminar.field2"
                          >
                            <template v-slot:prepend>
                              <q-icon name="event" class="cursor-pointer">
                                <q-popup-proxy
                                  cover
                                  transition-show="scale"
                                  transition-hide="scale"
                                >
                                  <q-date
                                    @update:model-value="
                                      durationSeminar(cardSeminar)
                                    "
                                    v-model="cardSeminar.field2"
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
                        <!-- bride -->
                        <div>
                          Time Start
                          <q-input
                            outlined
                            v-model="cardSeminar.field3"
                            mask="time"
                            dense
                          >
                            <template v-slot:append>
                              <q-icon name="access_time" class="cursor-pointer">
                                <q-popup-proxy
                                  cover
                                  transition-show="scale"
                                  transition-hide="scale"
                                >
                                  <q-time v-model="cardSeminar.field3">
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
                        <div>
                          Time End
                          <q-input
                            outlined
                            v-model="cardSeminar.field4"
                            mask="time"
                            dense
                          >
                            <template v-slot:append>
                              <q-icon name="access_time" class="cursor-pointer">
                                <q-popup-proxy
                                  cover
                                  transition-show="scale"
                                  transition-hide="scale"
                                >
                                  <q-time
                                    v-model="cardSeminar.field4"
                                    @update:model-value="
                                      durationInMinutesSeminar(cardSeminar)
                                    "
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
                        <div class="q-mb-sm">
                          Venue
                          <q-input
                            ref="step4Ref"
                            v-model="cardSeminar.field7"
                            :dense="true"
                            outlined
                          ></q-input>
                        </div>
                        <div class="scheduleData hidden">
                          {{ SchedulecardValues }}

                          {{ SeminarDay }}
                          {{ timeDurationSeminar }}
                          <div style="display: none">
                            {{ SchedulecardValues }}
                            {{ (cardSeminar.field5 = SeminarDay) }}
                            {{ (cardSeminar.field6 = timeDurationSeminar) }}
                            {{
                              (ConfirmationData.SeminarDetails =
                                SchedulecardValues)
                            }}
                            <!-- {{ (BaptismFormData.EventServiceID = randnum) }} -->
                          </div>
                        </div>
                        <q-btn
                          rounded
                          unelevated
                          @click="RemoveScheduleCard(Schedulecards)"
                          size="md"
                          icon="remove"
                          label="Remove"
                          :disable="Schedulecards.length <= 1 ? true : false"
                        ></q-btn>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="MarriageField" v-if="formData.Service == '4'">
            Sacramental and Documentary Requirements

            <div class="inputStep" style="width: 200px">
              <div class="header justify-between">
                <h6 class="q-mb-md q-mt-sm">
                  Documentary Requirements Checklist
                </h6>
                <div class="q-gutter-sm">
                  <q-checkbox
                    v-model="BU_requirementsList.Baptismal"
                    label="Baptismal Certifiate"
                    color="amber"
                    true-value="true"
                    false-value="false"
                    @update:model-value="
                      BU_checkAllPropertiesTrue(BU_requirementsList)
                    "
                  />
                  <q-checkbox
                    v-model="BU_requirementsList.LiveBirthCert"
                    label="Live Birth Certificate"
                    color="amber"
                    true-value="true"
                    false-value="false"
                    @update:model-value="
                      BU_checkAllPropertiesTrue(BU_requirementsList)
                    "
                  />
                  <q-checkbox
                    v-model="BU_requirementsList.Death_Certificate"
                    label="Death Certificate"
                    color="amber"
                    true-value="true"
                    false-value="false"
                    @update:model-value="
                      BU_checkAllPropertiesTrue(BU_requirementsList)
                    "
                  />
                  <q-checkbox
                    v-model="BU_requirementsList.Burial_Permit"
                    label="Burial Permit"
                    color="amber"
                    true-value="true"
                    false-value="false"
                    @update:model-value="
                      BU_checkAllPropertiesTrue(BU_requirementsList)
                    "
                  />
                  <q-checkbox
                    v-model="BU_requirementsList.Family_Consent"
                    label="Family Consent"
                    color="amber"
                    true-value="true"
                    false-value="false"
                    @update:model-value="
                      BU_checkAllPropertiesTrue(BU_requirementsList)
                    "
                  />
                  <q-checkbox
                    v-model="BU_requirementsList.Cremation_Authorization"
                    label="Cremation Authorization"
                    true-value="true"
                    false-value="false"
                    @update:model-value="
                      BU_checkAllPropertiesTrue(BU_requirementsList)
                    "
                    color="amber"
                  />

                  <div class="Status" style="display: none">
                    {{ BU_requirementsList }}
                    {{ BU_reqstats }}
                    {{ (BurialData.Requirements = BU_requirementsList) }}

                    <div class="status" v-if="BU_reqstats == 0">
                      {{ (BurialData.EventProgress = "Pending") }},
                      {{ (BurialData.Status = "Incomplete") }}
                    </div>
                    <div class="status" v-if="BU_reqstats == 1">
                      {{ (BurialData.EventProgress = "Pending") }},
                      {{ (BurialData.Status = "Complete") }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </q-step>
      <q-step :name="5" title="Finish" icon="done">
        <div class="div">
          <q-card flat class="my-card q-pa-md">
            <q-card-section class="text-center">
              <q-img src="../../assets/checklist_10703250.png" class="my-img" />
              <q-space />
              <q-card-section class="text-h6"
                >Your application is now ready for submission. Click 'Finish' to
                complete the process</q-card-section
              >
            </q-card-section>
          </q-card>
        </div>
      </q-step>
      <template v-slot:navigation>
        <q-stepper-navigation v-show="Event">
          <q-btn
            @click="onContinueStep"
            color="primary"
            :label="
              step === 5 || formData.Service === 6 ? 'Finish' : 'Continue'
            "
          />
          <!-- <q-btn v-if="step > 1" flat color="positive" @click="$refs.stepper.previous()" label="Back" class="q-ml-sm" />
        -->
          <q-btn
            v-if="step > 1"
            unelevated
            nocaps
            color="grey-4"
            style="width: 100px"
            @click="onBackStep"
            label="Back"
            class="q-ml-sm text-black"
          ></q-btn>
        </q-stepper-navigation>
        <!-- Button for Certificate -->
        <q-stepper-navigation v-show="Certificate">
          <q-btn
            @click="CertButton()"
            color="primary"
            :label="step === 5 || formData.Service === 6 ? 'Finish' : 'Submit'"
          />
          <q-btn
            v-if="step == 5"
            flat
            color="positive"
            @click="$refs.stepper.previous()"
            label="Back"
            class="q-ml-sm"
          />
        </q-stepper-navigation>
      </template>
    </q-stepper>
  </div>
</template>

<script>
import {
  ref,
  defineComponent,
  watch,
  onMounted,
  onBeforeUnmount,
  computed,
  reactive,
} from "vue";
import { useQuasar } from "quasar";
import * as XLSX from "xlsx";
import philippineData from "../../AddressPH/philippine_provinces_cities_municipalities_and_barangays_2019v2.json";
import moment from "moment";
import vueFilePond from "vue-filepond";
import { baseUrl } from "src/data/menuData";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import "filepond/dist/filepond.min.css";
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";
import {
  sendConfirmationData,
  msgs,
  msgColors,
} from "../../composables/Confirmation.js";
import {
  getSerivce,
  Data,
  sendEventCeremonyData,
  msg,
  msgColor,
} from "../../composables/SeviceData.js";
import {
  send_burial_data,
  BU_msg,
  BU_msgColor,
} from "../../composables/burial.js";
import {
  send_Annointing_Data,
  OS_msg,
  OS_msgColor,
  send_blessing,
  send_mass,
} from "../../composables/OtherService.js";
import { loadScheduleTable, massrows } from "src/composables/loadMassSchedule";
import { getAvailablePriest, availablePriest } from "src/composables/getPriest";

import { api } from "src/boot/axios";
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

export default defineComponent({
  name: "StepperForm",

  setup() {
    const $q = useQuasar();
    let stepHeader2 = ref("ADMIN");
    let stepsub1 = ref(true);
    let stepsub12 = ref(false);
    let regionOptions = ref([]);
    let provinceOptions = ref([]);
    let cityOptions = ref([]);
    let barangayOptions = ref([]);
    const FilePond = vueFilePond(FilePondPluginImagePreview);

    let massOptions = [
      "Mass Intentions",
      "Special Occasion Masses",
      "Thanksgiving",
      "First Communion",
      "For the Dead",
    ];
    let model = ref({
      label: null,
      value: null,
      category: null,
    });
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
            v.event_name.toLowerCase().includes(needle)
          );
        }
      });
    };
    //  ======================GET CURRENT DATE =======================
    const formDataDate = ref({ Date: "" });
    const formattedDate = ref("");
    const currentDatePlaceholder = ref("");
    const updateFormattedDate = (date) => {
      if (date) {
        const formatted = formatDate(date);
        formattedDate.value = formatted;
        formDataDate.value.Date = formatted;
      }
    };

    const formatDate = (date) => {
      const d = new Date(date);
      const year = d.getFullYear();
      const month = String(d.getMonth() + 1).padStart(2, "0");
      const day = String(d.getDate()).padStart(2, "0");
      return `${year}-${month}-${day}`;
    };
    const setCurrentDatePlaceholder = () => {
      currentDatePlaceholder.value = formatDate(new Date());
    };

    onMounted(setCurrentDatePlaceholder);

    watch(
      () => formDataDate.value.Date,
      (newDate) => {
        updateFormattedDate(newDate);
      }
    );

    //data

    // Age Computation
    let Computedage = ref(null);

    const computeAge = (DOM, DOB) => {
      if (!DOM || !DOB) return (Computedage.value = null);

      let dom = new Date(DOM),
        dob = new Date(DOB);
      let age = dom.getFullYear() - dob.getFullYear();

      if (dom < new Date(dob.setFullYear(dom.getFullYear()))) age--;

      Computedage.value = age;
      console.log("Age:", Computedage.value);
    };
    // bride Computed Age

    let ComputedageBride = ref();
    let computeAgeBride = (DOM, DOB) => {
      let dom = new Date(DOM);
      let dob = new Date(DOB);
      const diffTime = Math.abs(dom - dob);
      ComputedageBride.value = Math.floor(
        diffTime / (1000 * 60 * 60 * 24 * 365)
      );
    };
    //modify stepper content
    let stepperV2 = () => {
      stepHeader2.value = "Secretary";
      stepsub12.value = true;
      stepsub1.value = false;
    };
    //custom field
    let field1 = ref(false);
    let field2 = ref(false);
    let field3 = ref(false);
    let dateField = ref(false);
    let MassUpload = ref(false);
    let VenueTypeAddress = ref(false);
    let Event = ref(true);
    let Certificate = ref(false);
    let venue = ref(false);
    let Preffered_Priest = ref(true);
    //  custom stepper
    let stepper2panel = ref(false);
    let stepper3panel = ref(false);
    let stepperWitnesspanel = ref(false);
    let stepTitle = ref();
    let stepTitle2 = ref();
    let Address_A = ref(false);
    let Description_Field = ref(false);
    let openPriestList = ref(false);
    let customField = (res) => {
      console.log(res);
      // change this according to selected services
      if (res === "1") {
        field1.value = true;
        field3.value = false;
        stepper2panel.value = true;
        Preffered_Priest.value = true;
        stepperWitnesspanel.value = true;
        stepTitle.value = "Witness and God Parents";
        stepTitle2.value = "Seminar and Requirements";
        Address_A.value = false;
      } else if (res === "2") {
        field1.value = true;
        field3.value = false;
        stepper2panel.value = true;
        Preffered_Priest.value = true;
        stepper3panel.value = true;
        stepperWitnesspanel.value = true;
        stepTitle.value = "Witness and God Parents";
        stepTitle2.value = "Seminar and Requirements";
        Address_A.value = false;
      } else if (res === "3") {
        field1.value = true;
        field3.value = false;
        stepper2panel.value = true;
        Preffered_Priest.value = true;
        stepper3panel.value = true;
        stepperWitnesspanel.value = true;
        stepTitle.value = "Witness and God Parents";
        stepTitle2.value = "Seminar and Requirements";
        Address_A.value = false;
      } else if (res === "4") {
        field1.value = true;
        field3.value = false;
        stepper2panel.value = true;
        Preffered_Priest.value = true;
        stepper3panel.value = true;
        stepperWitnesspanel.value = true;
        stepTitle.value = "Additional Info";
        stepTitle2.value = "Requirements";
        Address_A.value = false;
      } else if (res === "5") {
        field1.value = false;
        field3.value = false;
        field2.value = false;
        dateField.value = true;
        Cfor.value = false;
        stepperWitnesspanel.value = false;
        stepper2panel.value = false;
        Preffered_Priest.value = true;
        Address_A.value = true;
      } else if (res === "6") {
        field1.value = false;
        field3.value = false;
        field2.value = false;
        dateField.value = true;
        Cfor.value = false;
        Description_Field.value = true;
        stepperWitnesspanel.value = false;
        stepper2panel.value = false;
        Preffered_Priest.value = true;
        Address_A.value = true;
      } else if (res === "7") {
        field1.value = false;
        field2.value = true;
        field3.value = false;
        dateField.value = true;
        MassUpload.value = false;
        stepper2panel.value = false;
        stepperWitnesspanel.value = false;
        Cfor.value = false;
        openPriestList.value = true;
        Description_Field.value = true;
        Preffered_Priest.value = true;
        venue.value = true;
      } else if (res === "8") {
        field1.value = false;
        field2.value = false;
        field3.value = true;
        dateField.value = true;
        Cfor.value = false;
        stepper2panel.value = false;
        stepperWitnesspanel.value = false;
        Preffered_Priest.value = true;
        Address_A.value = true;
      } else {
        field1.value = false;
        field2.value = false;
        field3.value = false;
        stepperWitnesspanel.value = false;
        Cfor.value = false;
        stepper2panel.value = false;
      }
    };

    //=========address for annointing of the sick==============

    let selectedARegion = ref(null);
    let selectedAProvince = ref(null);
    let selectedACity = ref(null);
    let selectedABarangay = ref(null);

    onMounted(() => {
      getAvailablePriest();
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
    watch(selectedARegion, (newValue, oldValue) => {
      if (newValue !== oldValue) {
        onRegionChangeA();
      }
    });

    watch(selectedAProvince, (newValue, oldValue) => {
      if (newValue !== oldValue) {
        onProvinceChangeA();
      }
    });

    watch(selectedACity, (newValue, oldValue) => {
      if (newValue !== oldValue) {
        onCityChangeA();
      }
    });

    watch(selectedABarangay, (newValue, oldValue) => {
      if (newValue !== oldValue) {
        onBarangayChangeA();
      }
    });

    let regionA = ref();
    let ProvinceA = ref();
    let CityA = ref();
    let BrgyA = ref();
    const onRegionChangeA = () => {
      selectedAProvince.value = null;
      selectedACity.value = null;
      selectedABarangay.value = null;

      let selectedR = selectedARegion.value.value;
      const regionData = philippineData[selectedR]?.province_list;

      provinceOptions.value = Object.keys(regionData).map((provinceName) => ({
        label: provinceName,
        value: provinceName,
      }));
      regionA.value = selectedARegion.value.value;
      console.log(regionA.value);
    };

    const onProvinceChangeA = () => {
      if (selectedAProvince.value !== null) {
        selectedACity.value = null;
        selectedABarangay.value = null;
        let selectedR = selectedARegion.value.value;
        let selectedP = selectedAProvince.value.value;
        const selectedAProvinceData =
          philippineData[selectedR]?.province_list[selectedP];
        if (selectedAProvinceData) {
          const municipalityData = selectedAProvinceData.municipality_list;
          cityOptions.value = Object.keys(municipalityData).map(
            (municipalityName) => ({
              label: municipalityName,
              value: municipalityName,
            })
          );
          ProvinceA.value = selectedAProvince.value.value;
        }
      }
    };

    const onCityChangeA = () => {
      if (selectedACity.value !== null) {
        selectedABarangay.value = null;
        let selectedR = selectedARegion.value.value;
        let selectedP = selectedAProvince.value.value;
        let selectedC = selectedACity.value.value;
        const selectedAMunicipalityData =
          philippineData[selectedR]?.province_list[selectedP]
            ?.municipality_list[selectedC];
        if (selectedAMunicipalityData) {
          const barangayList = selectedAMunicipalityData.barangay_list;
          barangayOptions.value = barangayList.map((barangayName) => ({
            label: barangayName,
            value: barangayName,
          }));
          CityA.value = selectedACity.value.value;
          // updateSuppAddress();
        }
      }
    };

    const onBarangayChangeA = () => {
      let brgy = ref();
      if (selectedABarangay.value !== null) {
        brgy.value = selectedABarangay.value;
        BrgyA.value = brgy.value.value;
      }
    };

    //============================================================
    //  ===========certificate no date=================
    let ServiceWatch = ref();
    let Cfor = ref(false);
    let btnname = ref();

    let dateShow = (type) => {
      if (type === "Certificate") {
        dateField.value = false;
        VenueTypeAddress.value = false;
        field2.value = false;
        field3.value = false;
        MassUpload.value = false;
        stepper3panel.value = false;
        stepper2panel.value = false;
        stepperWitnesspanel.value = false;
        venue.value = false;
        Preffered_Priest.value = false;
        if (
          ServiceWatch.value === "Baptism" ||
          ServiceWatch.value === "Confirmation" ||
          ServiceWatch.value === "Burial"
        ) {
          Cfor.value = true;
          btnname.value = "Submit";
        } else {
          Cfor.value = false;
        }
        Certificate.value = true;
        Event.value = false;
      } else if (type === "Mass") {
        dateField.value = true;
        MassUpload.value = true;
        venue.value = true;
        Cfor.value = false;
        Certificate.value = false;
        Event.value = true;
        Preffered_Priest.value = true;
        stepper3panel.value = true;
        stepperWitnesspanel.value = false;
        stepper2panel.value = false;
      } else if (type === "Special") {
        dateField.value = true;
        MassUpload.value = false;
        venue.value = true;
        Cfor.value = false;
        Certificate.value = false;
        Event.value = true;
        stepper3panel.value = true;
        stepper2panel.value = true;
        stepperWitnesspanel.value = true;
      }
    };

    let setAddress = (venueType, formData) => {
      if (venueType == "1") {
        formData.Venue = "St Raphael Church";
        Address_A.value = false;
      } else if (venueType == "2") {
        formData.Venue = "Pastoral Center";
        Address_A.value = false;
      } else if (venueType === "3") {
        formData.Venue = null;
        Address_A.value = true;
      } else {
        VenueTypeAddress.value = false;
      }
    };
    let setBurialOptions = (venueType, BurialData) => {
      if (venueType == "Cremation") {
        BurialData.Venue = "Crematory";
      } else if (venueType == "Traditional") {
        BurialData.Venue = "Traditional";
      } else {
        BurialData.value = false;
      }
    };

    let CertButton = () => {
      step.value = 4;
    };
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

    //============================== Get Address Bride=================================

    let selectedRegionbride = ref(null);
    let SelectedProvinceBride = ref(null);
    let selectedCityBride = ref(null);
    let selectedBarangayBride = ref(null);

    onMounted(() => {
      getAvailablePriest();
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
    watch(selectedRegionbride, (newValue, oldValue) => {
      if (newValue !== oldValue) {
        onRegionChangeBride();
      }
    });

    watch(SelectedProvinceBride, (newValue, oldValue) => {
      if (newValue !== oldValue) {
        onProvinceChangeBride();
      }
    });

    watch(selectedCityBride, (newValue, oldValue) => {
      if (newValue !== oldValue) {
        onCityChangeBride();
      }
    });

    watch(selectedBarangayBride, (newValue, oldValue) => {
      if (newValue !== oldValue) {
        onBarangayChangeBride();
      }
    });

    let regionB = ref();
    let ProvinceB = ref();
    let CityB = ref();
    let BrgyB = ref();
    const onRegionChangeBride = () => {
      SelectedProvinceBride.value = null;
      selectedCityBride.value = null;
      selectedBarangayBride.value = null;

      let selectedR = selectedRegionbride.value.value;
      const regionData = philippineData[selectedR]?.province_list;

      provinceOptions.value = Object.keys(regionData).map((provinceName) => ({
        label: provinceName,
        value: provinceName,
      }));
      regionB.value = selectedRegionbride.value.value;
      // updateSuppAddress();
      // console.log(regionData)
    };

    const onProvinceChangeBride = () => {
      if (SelectedProvinceBride.value !== null) {
        selectedCityBride.value = null;
        selectedBarangayBride.value = null;
        let selectedR = selectedRegionbride.value.value;
        let selectedP = SelectedProvinceBride.value.value;
        const SelectedProvinceBrideData =
          philippineData[selectedR]?.province_list[selectedP];
        if (SelectedProvinceBrideData) {
          const municipalityData = SelectedProvinceBrideData.municipality_list;
          cityOptions.value = Object.keys(municipalityData).map(
            (municipalityName) => ({
              label: municipalityName,
              value: municipalityName,
            })
          );
          ProvinceB.value = SelectedProvinceBride.value.value;
        }
      }
    };

    const onCityChangeBride = () => {
      if (selectedCityBride.value !== null) {
        selectedBarangayBride.value = null;
        let selectedR = selectedRegionbride.value.value;
        let selectedP = SelectedProvinceBride.value.value;
        let selectedC = selectedCityBride.value.value;
        const selectedMunicipalityData =
          philippineData[selectedR]?.province_list[selectedP]
            ?.municipality_list[selectedC];
        if (selectedMunicipalityData) {
          const barangayList = selectedMunicipalityData.barangay_list;
          barangayOptions.value = barangayList.map((barangayName) => ({
            label: barangayName,
            value: barangayName,
          }));
          CityB.value = selectedCityBride.value.value;
          // updateSuppAddress();
        }
      }
    };

    const onBarangayChangeBride = () => {
      let brgy = ref();
      if (selectedBarangayBride.value !== null) {
        brgy.value = selectedBarangayBride.value;
        BrgyB.value = brgy.value.value;
      }
    };
    // ================================================================================

    //========================= Get Address Event ============================

    let selectedRegionEvent = ref(null);
    let SelectedProvinceEvent = ref(null);
    let selectedCityEvent = ref(null);
    let selectedBarangayEvent = ref(null);

    onMounted(() => {
      loadScheduleTable();
      getAvailablePriest();
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
    watch(selectedRegionEvent, (newValue, oldValue) => {
      if (newValue !== oldValue) {
        onRegionChangeEvent();
      }
    });

    watch(SelectedProvinceEvent, (newValue, oldValue) => {
      if (newValue !== oldValue) {
        onProvinceChangeEvent();
      }
    });

    watch(selectedCityEvent, (newValue, oldValue) => {
      if (newValue !== oldValue) {
        onCityChangeEvent();
      }
    });

    watch(selectedBarangayEvent, (newValue, oldValue) => {
      if (newValue !== oldValue) {
        onBarangayChangeEvent();
      }
    });

    let regionEvent = ref();
    let ProvinceEvent = ref();
    let CityEvent = ref();
    let BrgyEvent = ref();
    const onRegionChangeEvent = () => {
      SelectedProvinceEvent.value = null;
      selectedCityEvent.value = null;
      selectedBarangayEvent.value = null;

      let selectedR = selectedRegionEvent.value.value;
      const regionData = philippineData[selectedR]?.province_list;

      provinceOptions.value = Object.keys(regionData).map((provinceName) => ({
        label: provinceName,
        value: provinceName,
      }));
      regionEvent.value = selectedRegionEvent.value.value;
      console.log(regionEvent);
      // updateSuppAddress();
      // console.log(regionData)
    };

    const onProvinceChangeEvent = () => {
      if (SelectedProvinceEvent.value !== null) {
        selectedCityEvent.value = null;
        selectedBarangayEvent.value = null;
        let selectedR = selectedRegionEvent.value.value;
        let selectedP = SelectedProvinceEvent.value.value;
        const SelectedProvinceEventData =
          philippineData[selectedR]?.province_list[selectedP];
        if (SelectedProvinceEventData) {
          const municipalityData = SelectedProvinceEventData.municipality_list;
          cityOptions.value = Object.keys(municipalityData).map(
            (municipalityName) => ({
              label: municipalityName,
              value: municipalityName,
            })
          );
          ProvinceEvent.value = SelectedProvinceEvent.value.value;
        }
      }
    };

    const onCityChangeEvent = () => {
      if (selectedCityEvent.value !== null) {
        selectedBarangayEvent.value = null;
        let selectedR = selectedRegionEvent.value.value;
        let selectedP = SelectedProvinceEvent.value.value;
        let selectedC = selectedCityEvent.value.value;
        const selectedMunicipalityData =
          philippineData[selectedR]?.province_list[selectedP]
            ?.municipality_list[selectedC];
        if (selectedMunicipalityData) {
          const barangayList = selectedMunicipalityData.barangay_list;
          barangayOptions.value = barangayList.map((barangayName) => ({
            label: barangayName,
            value: barangayName,
          }));
          CityEvent.value = selectedCityEvent.value.value;
          // updateSuppAddress();
        }
      }
    };

    const onBarangayChangeEvent = () => {
      let brgy = ref();
      if (selectedBarangayEvent.value !== null) {
        brgy.value = selectedBarangayEvent.value;
        BrgyEvent.value = brgy.value.value;
      }
    };
    //========================================================================

    const showInputValue = (Event_Details, Marriage_Details) => {
      console.log(Event_Details);
      console.log(Marriage_Details);
    };

    // for marriage WITNESS
    const cards = ref([
      { Groom_Testium: "", G_Address: "", Bride_Testium: "", B_Address: "" },
    ]);

    const addCard = () => {
      cards.value.push({
        Groom_Testium: "",
        G_Address: "",
        Bride_Testium: "",
        B_Address: "",
      });
    };
    const removeCard = () => {
      cards.value.pop({
        Groom_Testium: "",
        G_Address: "",
        Bride_Testium: "",
        B_Address: "",
      });
    };
    const cardValues = computed(() => {
      return cards.value.map((card) => ({
        Groom_Testium: card.field1,
        G_Address: card.field2,
        Bride_Testium: card.field3,
        B_Address: card.field4,
      }));
    });
    //for Baptism Card
    const cards_B = ref([
      { Ninong: "", Ninong_Address: "", Ninang: "", Ninang_Address: "" },
    ]);

    const addCard_B = () => {
      cards_B.value.push({
        Ninong: "",
        Ninong_Address: "",
        Ninang: "",
        Ninang_Address: "",
      });
    };
    const removeCard_B = () => {
      cards_B.value.pop({
        Ninong: "",
        Ninong_Address: "",
        Ninang: "",
        Ninang_Address: "",
      });
    };
    const cardValues_B = computed(() => {
      return cards_B.value.map((card) => ({
        Ninong: card.field1,
        Ninong_Address: card.field2,
        Ninang_Testium: card.field3,
        Ninang_Address: card.field4,
      }));
    });

    //===============================REQUIREMENTS SECTION====================================
    let requirementsList = ref({
      RID: null,
      Baptismal: "no",
      Marriage_License: "no",
      Confirmation: "no",
      LiveBirthCert: "no",
      Cenomar: "no",
      Interogation: "no",
      PreCana: "no",
      Confession: "no",
      RequirementID: null,
      PermissionLetter: "no",
    });

    let reqstats = ref(0);
    let checkAllPropertiesTrue = (obj) => {
      if (
        obj.Baptismal == "true" &&
        obj.Marriage_License == "true" &&
        obj.Confirmation == "true" &&
        obj.LiveBirthCert == "true" &&
        obj.Cenomar == "true" &&
        obj.Interogation == "true" &&
        obj.PreCana == "true" &&
        obj.Confession == "true" &&
        obj.PermissionLetter == "no"
      ) {
        reqstats.value = 1;
      } else {
        reqstats.value = 0;
      }
    };
    // baptism REQUIREMENTS

    let B_requirementsList = ref({
      RID: null,
      Marriage_License: "no",
      Confirmation: "no",
      LiveBirthCert: "no",
      Confession: "no",
      RequirementID: null,
    });

    let B_reqstats = ref(0);
    let B_checkAllPropertiesTrue = (obj) => {
      if (
        obj.Marriage_License == "true" &&
        obj.Confirmation == "true" &&
        obj.LiveBirthCert == "true" &&
        obj.Confession == "true"
      ) {
        B_reqstats.value = 1;
      } else {
        B_reqstats.value = 0;
      }
    };
    // =====================================Confirmation Requirements==============================================
    let C_requirementsList = ref({
      RID: null,
      Baptismal: "no",
      Marriage_License: "no",
      Confirmation: "no",
      LiveBirthCert: "no",
      Cenomar: "no",
      Interogation: "no",
      PreCana: "no",
      Confession: "no",
      RequirementID: null,
      PermissionLetter: "no",
    });

    let C_reqstats = ref(0);
    let C_checkAllPropertiesTrue = (obj) => {
      if (
        obj.Baptismal == "true" &&
        obj.Marriage_License == "true" &&
        obj.LiveBirthCert == "true" &&
        obj.Cenomar == "true" &&
        obj.Confession == "true"
      ) {
        C_reqstats.value = 1;
      } else {
        C_reqstats.value = 0;
      }
    };
    // =====================================Burial Requirements==============================================
    let BU_requirementsList = ref({
      RID: null,
      Baptismal: "no",
      Marriage_License: "no",
      Confirmation: "no",
      LiveBirthCert: "no",
      Cenomar: "no",
      Interogation: "no",
      PreCana: "no",
      Confession: "no",
      RequirementID: null,
      PermissionLetter: "no",
      Burial_Permit: "no",
      Death_Certificate: "no",
      Family_Consent: "no",
      Cremation_Authorization: "no",
    });

    let BU_reqstats = ref(0);
    let BU_checkAllPropertiesTrue = (obj) => {
      if (
        obj.Baptismal == "true" &&
        obj.LiveBirthCert == "true" &&
        obj.Burial_Permit == "true" &&
        obj.Death_Certificate == "true"
      ) {
        BU_reqstats.value = 1;
      } else {
        BU_reqstats.value = 0;
      }
    };

    // ================================Seminar Scheduled =================================

    const Schedulecards = ref([
      {
        SeminarTitle: "",
        Date: "",
        timeStart: "",
        timeEnd: "",
        days: "",
        duration: "",
        SeminarVenue: "",
      },
    ]);

    const addScheduleCard = () => {
      Schedulecards.value.push({
        SeminarTitle: "",
        Date: "",
        timeStart: "",
        timeEnd: "",
        days: "",
        duration: "",
        SeminarVenue: "",
      });
    };

    const SchedulecardValues = computed(() => {
      return Schedulecards.value.map((card) => ({
        SeminarTitle: card.field1,
        Date: card.field2,
        timeStart: card.field3,
        timeEnd: card.field4,
        days: card.field5,
        duration: card.field6,
        SeminarVenue: card.field7,
      }));
    });
    let SeminarDay = ref(null);
    const durationSeminar = (payload) => {
      console.log(payload.Date);
      // compute days
      setTimeout(() => {
        if (payload.Date == null) {
          SeminarDay.value = 0;
          return;
        } else {
          let value1 = Number(payload.Date.substring(8, 10));
          let value2 = Number(payload.Date.substring(8, 10));
          SeminarDay.value = value2 - value1 + 1;
          console.log("days", SeminarDay.value);
          return;
        }
      }, 2000);
    };
    // compute calendar time SEMINAR
    const timeDurationSeminar = ref(null);
    const durationInMinutesSeminar = (payload) => {
      console.log(payload);
      setTimeout(() => {
        if (payload.field3 == null || payload.field4 == null) {
          timeDurationSeminar.value = 0;
          return;
        } else {
          const start = moment(payload.field3, "HH:mm");
          const end = moment(payload.field4, "HH:mm");
          const durationTime = moment.duration(end.diff(start));
          timeDurationSeminar.value = durationTime.asMinutes();
          console.log("days", timeDurationSeminar.value);
          return;
        }
      }, 1000);
    };

    let RemoveScheduleCard = (Schedulecards) => {
      Schedulecards.pop();
    };
    // ===================================================================================

    // ===============compute event time ===========================
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
    // =============================================================

    //===================Confirmation Data Component ===================
    let NationalityList = ref([
      "Afghan",
      "Albanian",
      "Algerian",
      "American",
      "Andorran",
      "Angolan",
      "Antiguans",
      "Argentinean",
      "Armenian",
      "Australian",
      "Austrian",
      "Azerbaijani",
      "Bahamian",
      "Bahraini",
      "Bangladeshi",
      "Barbadian",
      "Barbudans",
      "Batswana",
      "Belarusian",
      "Belgian",
      "Belizean",
      "Beninese",
      "Bhutanese",
      "Bolivian",
      "Bosnian",
      "Brazilian",
      "British",
      "Bruneian",
      "Bulgarian",
      "Burkinabe",
      "Burmese",
      "Burundian",
      "Cambodian",
      "Cameroonian",
      "Canadian",
      "Cape Verdean",
      "Central African",
      "Chadian",
      "Chilean",
      "Chinese",
      "Colombian",
      "Comoran",
      "Congolese",
      "Costa Rican",
      "Croatian",
      "Cuban",
      "Cypriot",
      "Czech",
      "Danish",
      "Djibouti",
      "Dominican",
      "Dutch",
      "East Timorese",
      "Ecuadorean",
      "Egyptian",
      "Emirian",
      "Equatorial Guinean",
      "Eritrean",
      "Estonian",
      "Ethiopian",
      "Fijian",
      "Filipino",
      "Finnish",
      "French",
      "Gabonese",
      "Gambian",
      "Georgian",
      "German",
      "Ghanaian",
      "Greek",
      "Grenadian",
      "Guatemalan",
      "Guinea-Bissauan",
      "Guinean",
      "Guyanese",
      "Haitian",
      "Herzegovinian",
      "Honduran",
      "Hungarian",
      "I-Kiribati",
      "Icelander",
      "Indian",
      "Indonesian",
      "Iranian",
      "Iraqi",
      "Irish",
      "Israeli",
      "Italian",
      "Ivorian",
      "Jamaican",
      "Japanese",
      "Jordanian",
      "Kazakhstani",
      "Kenyan",
      "Kittian and Nevisian",
      "Kuwaiti",
      "Kyrgyz",
      "Laotian",
      "Latvian",
      "Lebanese",
      "Liberian",
      "Libyan",
      "Liechtensteiner",
      "Lithuanian",
      "Luxembourger",
      "Macedonian",
      "Malagasy",
      "Malawian",
      "Malaysian",
      "Maldivan",
      "Malian",
      "Maltese",
      "Marshallese",
      "Mauritanian",
      "Mauritian",
      "Mexican",
      "Micronesian",
      "Moldovan",
      "Monacan",
      "Mongolian",
      "Moroccan",
      "Mosotho",
      "Motswana",
      "Mozambican",
      "Namibian",
      "Nauruan",
      "Nepalese",
      "New Zealander",
      "Nicaraguan",
      "Nigerian",
      "Nigerien",
      "North Korean",
      "Northern Irish",
      "Norwegian",
      "Omani",
      "Pakistani",
      "Palauan",
      "Panamanian",
      "Papua New Guinean",
      "Paraguayan",
      "Peruvian",
      "Polish",
      "Portuguese",
      "Qatari",
      "Romanian",
      "Russian",
      "Rwandan",
      "Saint Lucian",
      "Salvadoran",
      "Samoan",
      "San Marinese",
      "Sao Tomean",
      "Saudi",
      "Scottish",
      "Senegalese",
      "Serbian",
      "Seychellois",
      "Sierra Leonean",
      "Singaporean",
      "Slovakian",
      "Slovenian",
      "Solomon Islander",
      "Somali",
      "South African",
      "South Korean",
      "Spanish",
      "Sri Lankan",
      "Sudanese",
      "Surinamer",
      "Swazi",
      "Swedish",
      "Swiss",
      "Syrian",
      "Taiwanese",
      "Tajik",
      "Tanzanian",
      "Thai",
      "Togolese",
      "Tongan",
      "Trinidadian or Tobagonian",
      "Tunisian",
      "Turkish",
      "Tuvaluan",
      "Ugandan",
      "Ukrainian",
      "Uruguayan",
      "Uzbekistani",
      "Venezuelan",
      "Vietnamese",
      "Welsh",
      "Yemenite",
      "Zambian",
      "Zimbabwean",
    ]);

    // data
    //this should be the last
    let formData = ref({
      //event details
      E_ID: null,
      EventServiceID: null,
      Client: null,
      Service: null,
      Others: null,
      TypeofMass: null,
      Type: null,
      TimeTo: null,
      TimeFrom: null,
      Date: formattedDate.value,
      Venue: null,
      Duration: null,
      Days: 1,
      Venue_type: null,
      Assigned_Priest: null,
      Contact_Number: null,
      Event_Region: null,
      Event_Province: null,
      Event_City: null,
      Event_Barangay: null,
      CertificateFor: null,
      Description: null,
    });

    //Marriage Data
    let marriageData = ref({
      MID: null,
      Groom_First_Name: null,
      Groom_Middle_Name: null,
      Groom_Last_Name: null,
      Groom_Suffix: null,
      Groom_Status: null,
      Groom_BirthDate: null,
      Groom_Region: null,
      Groom_Province: null,
      Groom_City: null,
      Groom_Barangay: null,
      Grooms_Age: null,
      Groom_Father: null,
      Groom_Mother: null,
      Bride_First_Name: null,
      Bride_Middle_Name: null,
      Bride_Last_Name: null,
      Bride_BirthDate: null,
      Bride_Status: null,
      Bride_Age: null,
      Bride_Father: null,
      Bride_Mother: null,
      Bride_Region: null,
      Bride_Province: null,
      Bride_City: null,
      Bride_Barangay: null,
      Witness: null,
      witness_Address: null,
      Requirement: null,
      SeminarDetails: null,
      EventProgress: null,
      Status: null,
      ContactNumber: null,
      Contact_Person: null,
    });
    //Baptism Data
    let BaptismFormData = ref({
      BID: null,
      First_Name: null,
      Middle_Name: null,
      Last_Name: null,
      Suffix: null,
      Gender: null,
      Birth_Date: null,
      Birth_Place: null,
      Legitamacy: null,
      Father_Name: null,
      Mother_Name: null,
      Address: null,
      BaptismWitness: null,
      Requirement: null,
      SeminarDetails: null,
      EventProgress: null,
      Status: null,
      ContactNumber: null,
      Contact_Person: null,
      EventScheduleID: null,
      ServiceID: null,
      Region: null,
      Province: null,
      City: null,
      Barangay: null,
      DateEvent: null,
    });

    //Confirmation Data
    let ConfirmationData = ref({
      CID: null,
      First_Name: null,
      Middle_Name: null,
      Last_Name: null,
      Suffix: null,
      Gender: null,
      Birth_Date: null,
      Nationality: null,
      Birth_Place: null,
      Father_Name: null,
      Mother_Name: null,
      Requirement: null,
      SeminarDetails: null,
      EventProgress: null,
      Status: null,
      ContactNumber: null,
      Contact_Person: null,
      EventScheduleID: null,
      Region: null,
      Province: null,
      City: null,
      Barangay: null,
      Witness: null,
    });
    //Burial data
    let BurialData = ref({
      BU_ID: null,
      First_Name: null,
      Middle_Name: null,
      Last_Name: null,
      Suffix: null,
      Gender: null,
      Age: null,
      Status: null,
      Birth_Date: null,
      Date_of_Death: null,
      Date_of_Burial: null,
      Nationality: null,
      Father_Name: null,
      Mother_Name: null,
      Spouse: null,
      Sacrament: null,
      Cause_of_Death: null,
      Place_of_Interment: null,
      Assigned_Priest: null,
      Remarks: null,
      EventScheduleID: null,
      Region: null,
      Province: null,
      City: null,
      Barangay: null,
      Requirements: null,
    });

    let Interment = ref([
      "Legazpi Cemetery",
      "Church Cemetery",
      "Private Tomb",
    ]);
    let sacramentSelection = ref([]);

    // stepper Section
    const stepperRef = ref(null);
    const step1Ref = ref(null);
    const step2Ref = ref(null);
    const step3Ref = ref(null);
    const step4Ref = ref(null);
    const step5Ref = ref(null);
    const step = ref(1);
    const step1 = ref("");
    const step2 = ref("");
    const step3 = ref("");
    const step4 = ref("");
    const step5 = ref("");
    const massData = ref({
      Date: "",
      TimeFrom: "",
      TimeTo: "",
      // ... other fields
    });
    let excelData = ref();
    let excelVar = ref({});
    const excelExport = (files) => {
      // files is the Q-File v-model array
      const input = files[0];
      if (!input) {
        console.warn("No file selected");
        return;
      }

      const reader = new FileReader();
      reader.onload = (e) => {
        // e.target.result is an ArrayBuffer
        const data = new Uint8Array(e.target.result);
        const wb = XLSX.read(data, { type: "array" });
        console.log("Workbook loaded successfully:", wb);

        const result = {};

        wb.SheetNames.forEach((sheetName) => {
          const ws = wb.Sheets[sheetName];

          // 1) as an array of arrays (first row is header)
          const asArrays = XLSX.utils.sheet_to_json(ws, {
            header: 1,
            defval: "",
          });
          console.log(`> ${sheetName} as array of arrays:`, asArrays);

          // 2) as an array of objects (first row → keys)
          const asObjects = XLSX.utils.sheet_to_json(ws, {
            defval: "",
          });
          console.log(`> ${sheetName} as array of objects:`, asObjects);

          result[sheetName] = {
            rows: asArrays,
            objects: asObjects,
          };
        });

        // store it in your ref so your UI/reactivity sees it
        excelVar.value = result;
        console.log("Final JSON stored in excelVar:", excelVar.value);
      };

      reader.readAsArrayBuffer(input);
    };
    let randnum = ref();
    function onContinueStep() {
      switch (step.value) {
        case 1:
          step1Ref.value.validate();

          if (!step1Ref.value.hasError) {
            stepperRef.value.next();
            randnum.value = Math.floor(1000 + Math.random() * 90000);
            console.log(randnum.value);
          } else {
            $q.notify({
              message: "please fill up the form",
              color: "red-5",
              position: "bottom-right",
            });
          }

          break;
        case 2:
          step2Ref.value.validate();

          if (!step2Ref.value.hasError) {
            stepperRef.value.next();

            // randnum.value = Math.floor(1000 + Math.random() * 90000);
            // console.log(randnum.value);
          } else {
            $q.notify({
              message: "please fill up the form",
              color: "red-5",
              position: "bottom-right",
            });
          }
          break;
        case 3:
          stepperRef.value.next();

          break;
        case 4:
          stepperRef.value.next();

          break;
        default:
          // Marriage x special
          if (
            formData.value.Service == "1" &&
            formData.value.Type == "Special"
          ) {
            api
              .post("MarriageAPI.php", {
                eventData: formData.value,
                MarriageData: marriageData.value,
              })
              .then((response) => {
                if (response.data.Status == "Success") {
                  $q.notify({
                    message:
                      "Hallelujah! Your info is officially in the Book of Records! ",
                    color: "green-6",
                    position: "bottom-right",
                  });
                  getSerivce(0);
                } else {
                  msg.value =
                    "Hmm... looks like we hit a small snag. Refresh and retry!";
                  msgColor.value = "red-5";
                }

                console.log(msg.value, msgColor.value);
              })
              .catch((error) => {
                msg.value = "an error occured";
                msgColor.value = "red-5";
                reject(error);
              });
          }

          if (
            formData.value.Service == "2" &&
            formData.value.Type == "Special"
          ) {
            api
              .post("BaptismApi.php", {
                eventData: formData.value,
                BaptismData: BaptismFormData.value,
              })
              .then((response) => {
                if (response.data.Status == "Success") {
                  $q.notify({
                    message:
                      "Hallelujah! Your info is officially in the Book of Records! ",
                    color: "green-6",
                    position: "bottom-right",
                  });
                  getSerivce(0);
                } else {
                  msg.value =
                    "Hmm... looks like we hit a small snag. Refresh and retry!";
                  msgColor.value = "red-5";
                }

                console.log(msg.value, msgColor.value);
              })
              .catch((error) => {
                msg.value = "an error occured";
                msgColor.value = "red-5";
                reject(error);
              });
          }
          if (
            formData.value.Service == "3" &&
            formData.value.Type == "Special"
          ) {
            // sendEventCeremonyData(
            //   formData.value.Service,
            //   formData.value,
            //   ConfirmationData.value
            // );
            api
              .post("Confirmation.php", {
                eventData: formData.value,
                ConfirmationData: ConfirmationData.value,
              })
              .then((response) => {
                if (response.data.Status == "Success") {
                  $q.notify({
                    message:
                      "Hallelujah! Your info is officially in the Book of Records! ",
                    color: "green-6",
                    position: "bottom-right",
                  });
                  getSerivce(0);
                } else {
                  msg.value =
                    "Hmm... looks like we hit a small snag. Refresh and retry!";
                  msgColor.value = "red-5";
                }

                console.log(msg.value, msgColor.value);
              })
              .catch((error) => {
                msg.value = "an error occured";
                msgColor.value = "red-5";
                reject(error);
              });
          }
          if (
            formData.value.Service == "4" &&
            formData.value.Type == "Special"
          ) {
            api
              .post("burial.php", {
                eventData: formData.value,
                BurialData: BurialData.value,
              })
              .then((response) => {
                if (response.data.Status == "Success") {
                  $q.notify({
                    message: "Information saved Successfully",
                    color: "green-6",
                    position: "bottom-right",
                  });
                  getSerivce(0);
                } else {
                  $q.notify({
                    message: "Information saved Successfully",
                    color: "green-6",
                    position: "bottom-right",
                  });
                }

                console.log(msg.value, msgColor.value);
              })
              .catch((error) => {
                msg.value = "an error occured";
                msgColor.value = "red-5";
                reject(error);
              });
          }
          if (formData.value.Service == "5") {
            formData.value.EventScheduleID = randnum.value;
            api
              .post("Service.php", {
                eventData: formData.value,
              })
              .then((response) => {
                if (response.data.Status == "Success") {
                  $q.notify({
                    message: "Information saved Successfully",
                    color: "green-6",
                    position: "bottom-right",
                  });
                  getSerivce(0);
                } else {
                  $q.notify({
                    message:
                      "This schedule is already taken! Please choose another date or time.",
                    color: "red-6",
                    position: "bottom-right",
                  });
                }

                console.log(msg.value, msgColor.value);
              })
              .catch((error) => {
                $q.notify({
                  message: "Server Error",
                  color: "red-6",
                  position: "bottom-right",
                });
                reject(error);
              });
          }
          if (formData.value.Service == "7") {
            console.log(formData.value);
            api
              .post("massIntention.php", {
                eventData: formData.value,
              })
              .then((response) => {
                if (response.data.Status == "Success") {
                  $q.notify({
                    message: "Information saved Successfully",
                    color: "green-6",
                    position: "bottom-right",
                  });
                  getSerivce(0);
                } else {
                  $q.notify({
                    message:
                      "This schedule is already taken! Please choose another date or time.",
                    color: "red-6",
                    position: "bottom-right",
                  });
                }
              })
              .catch((error) => {
                $q.notify({
                  message: "Server Error",
                  color: "red-6",
                  position: "bottom-right",
                });
                reject(error);
              });
          }
          // sendclose(false);

          if (formData.value.Service == "6") {
            api
              .post("BlessingAPI.php", {
                eventData: formData.value,
              })
              .then((response) => {
                if (response.data.Status == "Success") {
                  $q.notify({
                    message: "Information saved Successfully",
                    color: "green-6",
                    position: "bottom-right",
                  });
                  getSerivce(0);
                } else {
                  $q.notify({
                    message:
                      "This schedule is already taken! Please choose another date or time.",
                    color: "red-6",
                    position: "bottom-right",
                  });
                }
              })
              .catch((error) => {
                $q.notify({
                  message: "Server Error",
                  color: "red-6",
                  position: "bottom-right",
                });
                reject(error);
              });
          }

          if (formData.value.Service == "Misa") {
            formData.value.EventScheduleID = randnum.value;
            send_mass(formData.value);
          }
          if (formData.value.Service == "1" && formData.value.Type == "Mass") {
            api
              .post("MassWedding.php", {
                eventData: formData.value,
                excel: excelVar.value.Sheet1.objects,
                Seminar: SchedulecardValues.value,
              })
              .then((response) => {
                if (response.data.Status == "Success") {
                  $q.notify({
                    message: "Information saved Successfully",
                    color: "green-6",
                    position: "bottom-right",
                  });
                  getSerivce(0);
                } else {
                  $q.notify({
                    message:
                      "This schedule is already taken! Please choose another date or time.",
                    color: "red-6",
                    position: "bottom-right",
                  });
                }
              })
              .catch((error) => {
                $q.notify({
                  message: "Server Error",
                  color: "red-6",
                  position: "bottom-right",
                });
                reject(error);
              });
            console.log(formData.value);
            console.log(excelVar.value.Sheet1.objects);
            console.log(SchedulecardValues.value);
          }

          if (formData.value.Service == "2" && formData.value.Type == "Mass") {
            api
              .post("massBaptism.php", {
                eventData: formData.value,
                excel: excelVar.value.Sheet1.objects,
                Seminar: SchedulecardValues.value,
              })
              .then((response) => {
                if (response.data.Status == "Success") {
                  $q.notify({
                    message: "Information saved Successfully",
                    color: "green-6",
                    position: "bottom-right",
                  });
                  getSerivce(0);
                } else {
                  $q.notify({
                    message:
                      "This schedule is already taken! Please choose another date or time.",
                    color: "red-6",
                    position: "bottom-right",
                  });
                }
              })
              .catch((error) => {
                $q.notify({
                  message: "Server Error",
                  color: "red-6",
                  position: "bottom-right",
                });
                reject(error);
              });
            console.log(formData.value);
            console.log(excelVar.value.Sheet1.objects);
            console.log(SchedulecardValues.value);
          }
          break;
      }
    }
    function onBackStep() {
      stepperRef.value.previous();
    }
    //=======get avialable priest based on the date
    const eventDetails = computed(() => ({
      date: formData.value.Date,
      timeFrom: formData.value.TimeFrom,
      timeTo: formData.value.TimeTo,
      venuetype: formData.value.Venue_type,
    }));
    watch(
      () => [
        formData.value.Date,
        formData.value.TimeFrom,
        formData.value.TimeTo,
        formData.value.Venue_type,
      ],
      ([newDate, newTimeFrom, newTimeTo, newVenue]) => {
        if (newDate && newTimeFrom && newTimeTo && newVenue) {
          formData.value.Assigned_Priest = null;
          getAvailablePriest(eventDetails.value);
          // Pass event details to function
        }
      }
    );

    const handleFileUpload = (error, file) => {
      if (!error) {
        console.log("File uploaded:", file.file.name);
      }
    };

    const uploader = ref(null);

    const uploadFiles = () => {
      if (uploader.value) {
        uploader.value.upload(); // Manually start upload
        console.log();
      }
    };

    const onRejected = (files) => {
      console.warn("Rejected files:", files);
    };

    const massColumns = [
      { name: "date", label: "Date", align: "left", field: "date" },
      { name: "timeFrom", label: "From", align: "center", field: "time_from" },
      { name: "timeTo", label: "To", align: "center", field: "time_to" },
      {
        name: "priest",
        label: "Officiating Priest",
        align: "left",
        field: "priest_id",
      },
      {
        name: "language",
        label: "Language",
        align: "center",
        field: "language",
      },
    ];
    const massDialog = ref(false);
    const selectMassSchedule = (evt, rowData, index) => {
      formData.value.EventServiceID = rowData.priest_id; // Update priest_id

      formData.value.Venue_type = rowData.VenueType;

      formData.value.E_ID = rowData.mass_event_id;
      formData.value.Date = rowData.date;
      formData.value.TimeTo = rowData.time_to;
      formData.value.TimeFrom = rowData.time_from;
      console.log("mass", rowData);
      massDialog.value = false;
    };

    let click = () => {
      const csvContent =
        "Groom_First_Name,Groom_Middle_Name,Groom_Last_Name,Groom_Suffix,Groom_Status,Groom_Birthdate,Groom_Address,Bride_First_Name,Bride_Middle_Name,Bride_Last_Name,Grooms_Age,Bride_Age,Bride_Address,Groom_Father,Groom_Mother,Bride_Suffix,Bride_Birthdate,Bride_Status,Bride_Father,Bride_Mother,Priest_Name,Assigned_Priest,Observanda,Stipendium,Status,EventProgress,ContactNumber,Contact_Person,Groom_Witness,GroomWitness_Address,Bride_Witness,BrideWitness_Address,Baptismal,Marriage_License,Confirmation,LiveBirthCert,Cenomar,Interogation,PreCana,Confession";

      const blob = new Blob([csvContent], { type: "text/csv" });
      const url = URL.createObjectURL(blob);

      const link = document.createElement("a");
      link.href = url;
      link.setAttribute("download", "MassWedding-Template.csv"); // Set the desired file name
      link.style.display = "none";

      document.body.appendChild(link);
      link.click();

      document.body.removeChild(link);
      URL.revokeObjectURL(url);
    };

    return {
      excelExport,
      excelData,
      click,
      excelVar,
      massrows,
      massData,
      massDialog,
      selectMassSchedule,
      massColumns,

      uploadFiles,
      onRejected,
      // Stepper

      Description_Field,
      stepperRef,
      onContinueStep,
      randnum,
      step,
      stepperV2,
      stepHeader2,
      stepsub1,
      stepsub12,
      onBackStep,
      formData,
      step1,
      step2,
      step3,
      step4,
      step5,
      step1Ref,
      step2Ref,
      step3Ref,
      step4Ref,
      step5Ref,
      dialog: ref(false),
      cancelEnabled: ref(false),
      // search
      filterFn,
      filterOptions,
      customField,
      //custom field
      field1,
      field2,
      field3,
      createValue,
      MassUpload,
      Preffered_Priest,
      // date format
      formDataDate,
      formattedDate,
      updateFormattedDate,
      currentDatePlaceholder,
      dateField,
      dateShow,
      //venue dependent fields
      setAddress,
      VenueTypeAddress,
      venue,
      ServiceWatch,
      Cfor,
      //button stepper modification
      btnname,
      Certificate,
      Event,
      CertButton,
      stepper2panel,
      stepper3panel,
      stepperWitnesspanel,
      // Auto Compute Age
      Computedage,
      computeAge,
      ComputedageBride,
      computeAgeBride,
      //Marriage Event
      marriageData,
      // adress Custom Groom
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
      // adress Custo Bride
      selectedRegionbride,
      SelectedProvinceBride,
      selectedCityBride,
      selectedBarangayBride,
      onRegionChangeBride,
      regionB,
      ProvinceB,
      CityB,
      BrgyB,
      // collect input for all fields
      showInputValue,
      //mARRIAGE Testium/Witness
      cards,
      addCard,
      cardValues,
      removeCard,
      //REQUIREMTS
      requirementsList,
      reqstats,
      checkAllPropertiesTrue,
      // Seminar Schedule
      Schedulecards,
      addScheduleCard,
      SchedulecardValues,
      durationSeminar,
      SeminarDay,
      timeDurationSeminar,
      durationInMinutesSeminar,
      RemoveScheduleCard,
      // Event Details Section
      timeDurationEvent,
      durationInMinutesEvent,
      //Preffered Priest
      availablePriest,
      // ==========================BAPTISM DATA=============================== //
      BaptismFormData,
      // Ninong Ninang Section
      cards_B,
      addCard_B,
      removeCard_B,
      cardValues_B,
      //Requirememnts Section
      B_requirementsList,
      B_reqstats,
      B_checkAllPropertiesTrue,
      openPriestList,
      // =========================Confirmation Data=========================//
      ConfirmationData,
      NationalityList,
      C_requirementsList,
      C_reqstats,
      C_checkAllPropertiesTrue,
      // Burial Form Data
      BurialData,
      stepTitle,
      stepTitle2,
      Interment,
      sacramentSelection,
      BU_requirementsList,
      BU_reqstats,
      BU_checkAllPropertiesTrue,
      setBurialOptions,
      // annointing address
      selectedARegion,
      selectedAProvince,
      selectedACity,
      selectedABarangay,
      onRegionChangeA,
      regionA,
      ProvinceA,
      CityA,
      BrgyA,
      Address_A,
      // mass
      massOptions,
      //filepond
      handleFileUpload,
    };
  },
});
</script>
<style scoped>
.row {
  justify-content: space-between;
}
.time {
  max-width: 100%;
}
.e_inputs {
  width: 270px;
}
.ev_1 {
  width: 350px;
}
.servicfield {
  width: auto;
}
.serviceField {
  width: 250px;
}
.ScheduleData {
  min-height: 470px;
  min-width: 600px;
}
.my-card {
  max-width: 300px;
  margin: 0 auto;
}
.StepperForm {
  width: auto;
}

.my-img {
  max-width: 80%;
  margin: 0 auto;
}
.MarriageField {
  max-width: 850px;
}
.AddressField {
  display: flex;
}
.ServiceAddresss {
  width: 250px;
}
.GroomSection {
  background-color: rgb(253, 253, 253);
  padding: 1em;
  margin-top: -30px;
}
.input-width {
  width: 20%;
  min-width: 100px;
}
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
