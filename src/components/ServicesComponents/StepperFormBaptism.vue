   <div class="container-app q-gutter-md flex q-pa-lg justify-center">
            <div style="display: none">
              {{ (BaptismFormData.EventScheduleID = randnum) }}
            </div>
            <div class="inputStep">
              <p class="q-mb-xs">Last Name</p>
              <q-input
                :dense="true"
                ref="step2Ref"
                v-model="BaptismFormData.Last_Name"
                outlined
              />
            </div>
            <div class="inputStep">
              <p class="q-mb-xs">First Name</p>
              <q-input
                :dense="true"
                ref="step2Ref"
                v-model="BaptismFormData.First_Name"
                outlined
              />
            </div>
            <div class="inputStep">
              <p class="q-mb-xs">Middle Name</p>
              <q-input
                :dense="true"
                ref="step2Ref"
                v-model="BaptismFormData.Middle_Name"
                outlined
              />
            </div>
            <div class="inputStep">
              <p class="q-mb-xs">Suffix Name(Optional)</p>
              <q-input
                :dense="true"
                ref="step2Ref"
                v-model="BaptismFormData.Suffix"
                outlined
              />
            </div>
            <div class="inputStep">
              <p class="q-mb-xs">Gender</p>
              <q-radio
                size="xs"
                v-model="BaptismFormData.Gender"
                val="1"
                label="Male"
              />
              <q-radio
                size="xs"
                v-model="BaptismFormData.Gender"
                val="2"
                label="Female"
              />
            </div>
            <div class="inputStep">
              <p class="text-grey-9 q-mb-xs text-subtitle">Birth Date</p>
              <q-input
                :dense="true"
                outlined
                v-model="BaptismFormData.Birth_Date"
              >
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
            <div class="inputStep">
              <p class="text-grey-9 q-mb-xs text-subtitle">Birth Place</p>
              <q-input
                outlined
                v-model="BaptismFormData.Birth_Place"
                lazy-rules
                :dense="true"
              />
            </div>
            <div class="inputStep">
              <p class="text-grey-9 q-mb-xs text-subtitle">Father's Name</p>
              <q-input
                outlined
                v-model="BaptismFormData.Father_Name"
                lazy-rules
                :dense="true"
              />
            </div>
            <div class="inputStep">
              <p class="text-grey-9 q-mb-xs text-subtitle">Mother's Name</p>
              <q-input
                outlined
                v-model="BaptismFormData.Mother_Name"
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
                  v-model="BaptismFormData.Legitamacy"
                  val="0"
                  label="illegal"
                />
                <q-radio
                  name="shape"
                  color="amber"
                  v-model="BaptismFormData.Legitamacy"
                  val="1"
                  label="legal"
                />
              </div>
            </div>
            <div class="inputStep" style="width: 69%">
              <div class="text-grey-9 q-mb-xs flex">
                <div class="address text-weight-regular">Address *</div>
                <div class="Description text-grey-6">
                  Enter full address include City, Province
                </div>
              </div>

              <q-input
                outlined
                v-model="BaptismFormData.Address"
                lazy-rules
                :dense="true"
              />
            </div>
            <div class="inputStep"></div>
          </div>