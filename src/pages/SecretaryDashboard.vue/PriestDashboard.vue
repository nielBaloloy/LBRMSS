<template>
  <div class="">
    <q-layout
      view="lHh lpr lFf"
      container
      style="height: 100vh"
      class="bg-grey-3 rounded-borders"
    >
      <q-header bordered class="bg-white text-primary">
        <q-toolbar>
          <q-toolbar-title> LBRMSS </q-toolbar-title>
          <div class="account">
            <q-avatar>
              <img src="../../assets/quasar-logo-vertical.svg" />
            </q-avatar>
            <q-btn-dropdown flat class="dropdown-btn">
              <div class="row no-wrap flex">
                <div class="column">
                  <div class="text-h6 q-mb-md bg-amber-5 flex flex-center">
                    <!-- Account content Here -->

                    <q-avatar
                      class="flex q-pt-lg"
                      style="width: 1.6em; height: 1.6em"
                    >
                      <img src="../../assets/quasar-logo-vertical.svg" />
                    </q-avatar>
                  </div>
                  <div class="profile-info q-pa-sm">
                    <p
                      class="User-Account text-center q-mt-sm"
                      style="font-size: small; font-weight: 600"
                    >
                      <!-- username -->

                      {{ myObject.Username }}
                      <br />
                      <span class="text-amber-6">
                        <!-- Position -->
                        {{ myObject.AccessLvl }}</span
                      >
                    </p>
                    <q-list>
                      <q-item clickable v-ripple class="it-1">
                        <div class="item q-pa-xs">
                          <q-icon name="settings"></q-icon>
                        </div>
                        <div class="item q-pa-xs" style="width: 200px">
                          Account Settings
                        </div>
                      </q-item>
                      <q-item
                        clickable
                        v-ripple
                        class="it-1"
                        to="/SecretaryDashboard/AccountLog"
                      >
                        <div class="item q-pa-xs">
                          <q-icon name="settings"></q-icon>
                        </div>
                        <div class="item q-pa-xs" style="width: 200px">
                          Account Logs
                        </div>
                      </q-item>
                      <q-item clickable v-ripple class="it-1">
                        <div class="item q-pa-xs">
                          <q-icon name="settings"></q-icon>
                        </div>
                        <div class="item q-pa-xs" style="width: 200px">
                          Switch Account
                        </div>
                      </q-item>
                      <!-- dialog for switch Account -->

                      <q-item
                        clickable
                        v-ripple
                        class="text-red"
                        @click="Logout()"
                      >
                        <div class="item q-pa-xs">
                          <q-icon name="logout"></q-icon>
                        </div>
                        <div class="item q-pa-xs" style="width: 200px">
                          Logout
                        </div>
                      </q-item>
                    </q-list>
                  </div>
                </div>
              </div>
            </q-btn-dropdown>
          </div>
        </q-toolbar>
      </q-header>

      <q-footer bordered class="bg-grey-3 text-primary">
        <q-tabs
          no-caps
          active-color="primary"
          indicator-color="transparent"
          class="text-grey-8"
          v-model="tab"
        >
          <q-tab name="images" icon="event" label="My Schedule" />
          <q-tab
            name="videos"
            icon="event"
            label="Events"
            @click="refreshEventList"
          />
          <q-tab name="articles" icon="person" label="My Profile" />
        </q-tabs>
      </q-footer>

      <q-page-container>
        <q-page class="q-pa-md">
          <q-tab-panels v-model="tab" animated>
            <q-tab-panel name="images" class="bg-grey-3 q-pa-sm">
              <priestModule
                :schedValue="scheduleOnePriest"
                v-if="myObject.AccessLvl === 'priest'"
              />
            </q-tab-panel>

            <q-tab-panel name="videos" class="bg-grey-3 q-pa-sm">
              <priestModule_event_list
                :schedValue="nopriestEvent"
                v-if="myObject.AccessLvl === 'priest'"
              />
            </q-tab-panel>

            <q-tab-panel name="articles" class="bg-grey-3">
              <div class="text-h5 q-mb-md text-primary">My Account</div>

              <q-card flat bordered class="q-mb-md">
                <q-card-section>
                  <q-item>
                    <q-item-section avatar>
                      <q-avatar size="60px">
                        <img
                          src="https://cdn.quasar.dev/img/avatar.png"
                          alt="User"
                        />
                      </q-avatar>
                    </q-item-section>

                    <q-item-section>
                      <q-item-label class="text-h6">
                        {{ myObject.pos_prefix }},{{ myObject.fname }}
                        {{ myObject.lname }}</q-item-label
                      >
                      <q-item-label caption class="text-grey">
                        {{ myObject.pos_name }}</q-item-label
                      >
                    </q-item-section>
                  </q-item>
                </q-card-section>
              </q-card>

              <q-card flat bordered class="q-mb-md">
                <q-list bordered separator>
                  <q-item clickable v-ripple @click="editProfile">
                    <q-item-section avatar>
                      <q-icon name="edit" color="primary" />
                    </q-item-section>
                    <q-item-section>Edit Profile</q-item-section>
                    <q-item-section side>
                      <q-icon name="chevron_right" />
                    </q-item-section>
                  </q-item>

                  <q-item clickable v-ripple @click="changePassword">
                    <q-item-section avatar>
                      <q-icon name="vpn_key" color="primary" />
                    </q-item-section>
                    <q-item-section>Change Password</q-item-section>
                    <q-item-section side>
                      <q-icon name="chevron_right" />
                    </q-item-section>
                  </q-item>
                </q-list>
              </q-card>

              <!-- Spacer to push logout at the bottom -->
              <div class="q-space"></div>

              <q-btn
                label="Logout"
                color="negative"
                class="q-mt-md"
                unelevated
                rounded
                size="md"
                @click="logout"
                style="width: 100%"
              />
              <q-dialog v-model="editProfileDialog" persistent>
                <q-card style="min-width: 350px">
                  <q-card-section
                    class="row items-center q-pa-md bg-primary text-white"
                  >
                    <div class="text-h6">Edit Profile</div>
                    <q-space />
                    <q-btn icon="close" flat round dense v-close-popup />
                  </q-card-section>

                  <q-card-section class="q-gutter-md">
                    <div class="text-center q-mb-md">
                      <q-avatar size="80px">
                        <img
                          src="https://cdn.quasar.dev/img/avatar.png"
                          alt="User Photo"
                        />
                      </q-avatar>
                    </div>

                    <q-input
                      v-model="myObject.fname"
                      label="First Name"
                      filled
                    />
                    <q-input
                      v-model="myObject.mname"
                      label="Middle Name"
                      filled
                    />
                    <q-input
                      v-model="myObject.lname"
                      label="Last Name"
                      filled
                    />
                    <q-input
                      v-model="myObject.contact"
                      label="Contact Number"
                      filled
                    />
                    <q-input
                      v-model="myObject.Username"
                      label="Username"
                      filled
                    />
                  </q-card-section>

                  <q-card-actions align="right">
                    <q-btn flat label="Cancel" color="primary" v-close-popup />
                    <q-btn
                      flat
                      label="Save"
                      color="primary"
                      @click="saveProfile"
                    />
                  </q-card-actions>
                </q-card>
              </q-dialog>
              <!-- Change Password Dialog -->
              <q-dialog v-model="changePasswordDialog" persistent>
                <q-card style="min-width: 350px">
                  <q-card-section
                    class="row items-center q-pa-md bg-primary text-white"
                  >
                    <div class="text-h6">Change Password</div>
                    <q-space />
                    <q-btn icon="close" flat round dense v-close-popup />
                  </q-card-section>

                  <q-card-section class="q-gutter-md">
                    <q-input
                      v-model="oldPassword"
                      label="Old Password"
                      type="password"
                      filled
                    />
                    <q-input
                      v-model="newPassword"
                      label="New Password"
                      type="password"
                      filled
                    />
                    <q-input
                      v-model="confirmNewPassword"
                      label="Confirm New Password"
                      type="password"
                      filled
                    />
                  </q-card-section>

                  <q-card-actions align="right">
                    <q-btn flat label="Cancel" color="primary" v-close-popup />
                    <q-btn
                      flat
                      label="Save"
                      color="primary"
                      @click="saveNewPassword"
                    />
                  </q-card-actions>
                </q-card>
              </q-dialog>
            </q-tab-panel>
          </q-tab-panels>
        </q-page>
      </q-page-container>
    </q-layout>
  </div>
</template>

<script>
import { ref, defineComponent, onMounted } from "vue";
import { useQuasar, SessionStorage } from "quasar";
import { useRouter, useRoute } from "vue-router";
import priestModule from "src/components/DashboardComponents/iosPage.vue";
import priestModule_event_list from "src/components/DashboardComponents/iosPage_event_list.vue";
import {
  getScheduledPriest,
  schedulePriest,
  getScheduledIndividualPriest,
  scheduleOnePriest,
  getNopriestEvent,
  nopriestEvent,
} from "src/composables/getPriestSched";
export default defineComponent({
  components: {
    priestModule,
    priestModule_event_list,
  },
  setup() {
    const $q = useQuasar();
    const router = useRouter();
    const changePasswordDialog = ref(false);
    onMounted(() => {
      getScheduledIndividualPriest(myObject.value.account_id);
    });
    let myObject = ref();
    let sessionkey = SessionStorage.getItem("log");
    if (sessionkey === null || "") {
      router.push({ path: "/" });
    } else {
      myObject.value = JSON.parse(sessionkey);
      console.log(myObject.value);
    }

    let active = ref(false);
    let activeclick = () => {
      active.value = true;
    };
    //logout
    const logout = () => {
      $q.dialog({
        title: "Logout",
        message: "Are you sure you want to logout?",
        cancel: true,
        persistent: true,
      })
        .onOk(() => {
          SessionStorage.clear();
          router.push({ path: "/" });
        })
        .onOk(() => {
          // console.log('>>>> second OK catcher')
        })
        .onCancel(() => {
          // console.log('>>>> Cancel')
        })
        .onDismiss(() => {
          // console.log('I am triggered on both OK and Cancel')
        });
    };
    let refreshEventList = () => {
      console.log("refresh eventy list");
      getNopriestEvent();
    };
    function editProfile() {
      console.log("Edit Profile clicked");
      // Navigate or open dialog
    }

    function changePassword() {
      changePasswordDialog.value = true;
      // Open change password dialog
    }

    function Logout() {
      console.log("Logout clicked");
      // Add your logout logic here
    }
    const editProfileDialog = ref(false);

    function editProfile() {
      editProfileDialog.value = true;
    }
    function saveProfile() {
      console.log("Saving...", myObject.value);
      // you can also update sessionStorage here if needed
      editProfileDialog.value = false;
    }

    const oldPassword = ref("");
    const newPassword = ref("");
    const confirmNewPassword = ref("");
    function saveNewPassword() {
      if (
        !oldPassword.value ||
        !newPassword.value ||
        !confirmNewPassword.value
      ) {
        $q.notify({
          type: "warning",
          message: "Please fill in all password fields.",
        });
        return;
      }

      if (newPassword.value !== confirmNewPassword.value) {
        $q.notify({
          type: "negative",
          message: "New Password and Confirm Password do not match!",
        });
        return;
      }

      console.log("Changing password...");
      console.log("Old Password:", oldPassword.value);
      console.log("New Password:", newPassword.value);

      // Simulate success
      $q.notify({
        type: "positive",
        message: "Password successfully changed!",
      });

      changePasswordDialog.value = false;

      // Clear fields
      oldPassword.value = "";
      newPassword.value = "";
      confirmNewPassword.value = "";
    }

    return {
      oldPassword,
      newPassword,
      changePasswordDialog,
      confirmNewPassword,
      saveNewPassword,
      saveProfile,
      editProfileDialog,
      editProfile,
      changePassword,
      refreshEventList,
      logout,
      nopriestEvent,
      scheduleOnePriest,
      Logout,
      myObject,
      tab: ref("images"),
    };
  },
});
</script>
