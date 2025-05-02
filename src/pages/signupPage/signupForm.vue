<template>
  <q-layout view="lHh lpR fFf" class="bg-accent">
    <q-header reveal elevated class="bg-info text-dark">
      <q-toolbar>
        <q-btn dense flat round icon="menu" @click="toggleLeftDrawer" />

        <q-toolbar-title> LBRMSS-Dashboard </q-toolbar-title>
        <div class="account">
          <q-avatar>
            <img src="src/assets/quasar-logo-vertical.svg" />
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
                    <img src="src/assets/quasar-logo-vertical.svg" />
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

    <q-drawer
      class="bg-white text-dark q-pa-md"
      show-if-above
      v-model="leftDrawerOpen"
      side="left"
      behavior="desktop"
      bordered
    >
      <div class="LOGO q-mb-xl"></div>
      <SidebarMenu :menuItems="menuData" />
    </q-drawer>

    <q-page-container>
      <q-page class="q-pa-lg">
        <q-banner inline-actions class="text-black bg-white q-pa-lg">
          Account Setup
          <template v-slot:action>
            <q-btn
              flat
              icon="add"
              color="white"
              label="Create Account"
              class="bg-amber-6"
              no-caps
              @click="accountdialog = true"
            />
          </template>
        </q-banner>
        <div class="container q-pt-md">
          <accountTable :columns="columns" :rowsData="account" />
        </div>
      </q-page>
      <q-dialog v-model="accountdialog">
        <q-card style="width: 800px" class="q-pa-lg rounded-borders shadow-2">
          <!-- Header -->
          <q-card-section class="row items-center q-pb-none">
            <div class="text-h6">Create Accounts</div>
            <q-space />
            <q-btn icon="close" flat round dense v-close-popup />
          </q-card-section>

          <!-- Form -->
          <q-card-section>
            <q-form class="q-gutter-md">
              <!-- Section Title -->
              <div class="text-subtitle1 text-primary q-mb-sm">
                User Information
              </div>

              <!-- 3 Columns per Row -->
              <div class="row q-col-gutter-md">
                <q-input
                  v-model="accountForm.fname"
                  label="First Name"
                  outlined
                  dense
                  class="col-4"
                />
                <q-input
                  v-model="accountForm.mname"
                  label="Middle Name"
                  outlined
                  dense
                  class="col-4"
                />
                <q-input
                  v-model="accountForm.lnamme"
                  label="Last Name"
                  outlined
                  dense
                  class="col-4"
                />

                <q-input
                  v-model="accountForm.suffix_name"
                  label="Suffix"
                  outlined
                  dense
                  class="col-4"
                />
                <q-input
                  v-model="accountForm.contact"
                  label="Contact"
                  outlined
                  dense
                  class="col-4"
                />
                <q-input
                  v-model="accountForm.username"
                  label="Username"
                  outlined
                  dense
                  class="col-4"
                />
              </div>

              <!-- Section Title -->
              <div class="text-subtitle1 text-primary q-mt-lg q-mb-sm">
                Credentials
              </div>

              <!-- Password Fields (2 columns) -->
              <div class="row q-col-gutter-md">
                <q-input
                  v-model="accountForm.password"
                  label="Password"
                  type="password"
                  outlined
                  dense
                  class="col-6"
                  @update:model-value="validatePasswordRules"
                />
                <q-input
                  v-model="confirmPassword"
                  label="Confirm Password"
                  type="password"
                  outlined
                  dense
                  class="col-6"
                  :error="passwordMismatch"
                  error-message="Passwords do not match"
                  @update:model-value="validatePasswords"
                />
              </div>
              <ul class="q-mt-sm">
                <li :style="{ color: rules.uppercase ? 'green' : 'black' }">
                  Contains uppercase letter
                </li>
                <li :style="{ color: rules.lowercase ? 'green' : 'black' }">
                  Contains lowercase letter
                </li>
                <li :style="{ color: rules.special ? 'green' : 'black' }">
                  Contains special character
                </li>
              </ul>
              <!-- Section Title -->
              <div class="text-subtitle1 text-primary q-mt-lg q-mb-sm">
                Position & Status
              </div>

              <!-- Position and Status -->
              <div class="row q-col-gutter-md">
                <q-select
                  v-model="accountForm.posid"
                  :options="positions"
                  label="Position"
                  outlined
                  dense
                  emit-value
                  map-options
                  class="col-4"
                />
                <q-input
                  v-model="accountForm.posprefix"
                  label="Prefix"
                  outlined
                  dense
                  class="col-4"
                />
                <div class="col-8">
                  <q-option-group
                    v-model="accountForm.status"
                    :options="[
                      { label: 'Active', value: 1 },
                      { label: 'Inactive', value: 0 },
                    ]"
                    type="radio"
                    inline
                  />
                </div>
              </div>
            </q-form>
          </q-card-section>

          <!-- Actions -->
          <q-card-actions align="right">
            <q-btn
              label="Save"
              color="primary"
              class="q-mr-sm"
              @click="saveAccount"
              :disable="passwordMismatch"
            />
            <q-btn label="Cancel" flat v-close-popup />
          </q-card-actions>
        </q-card>
      </q-dialog>
    </q-page-container>
  </q-layout>
</template>
<style scoped>
@font-face {
  font-family: opensans;
  src: url("../../../css/Montserrat,Open_Sans/Open_Sans/static/OpenSans-Medium.woff");
}

.titlebar {
  font-family: opensans;
  text-decoration: none;
  color: rgb(52, 52, 52);
}
.my-card {
  width: 300px;
}
.popUpService {
  max-width: 100%;
  height: 80em;
}
</style>
<script>
import {
  ref,
  defineComponent,
  nextTick,
  onMounted,
  watchEffect,
  reactive,
} from "vue";
import { useQuasar, SessionStorage } from "quasar";
import { useRouter } from "vue-router";
import SidebarMenu from "src/components/DashboardComponents/navigation_left.vue";
import { menuData, menuData2 } from "src/data/menuData";
import accountTable from "src/components/ServicesComponents/SSR_table_component_v2.vue";
import { loadAccount, account } from "src/composables/account";
import { api } from "src/boot/axios";
// ✅ Async function defined before setup()

export default defineComponent({
  components: { SidebarMenu, accountTable },

  setup() {
    const dataLoaded = ref(false); // ✅ Track data loading state
    onMounted(async () => {
      await nextTick();
      await loadAccount(); // Ensure fetch completes before proceeding
      dataLoaded.value = true; // ✅ Mark as loaded
    });
    let accountdialog = ref(false);
    let accountForm = ref({
      pid: null,
      fname: "",
      mname: "",
      lnamme: "",
      suffix_name: "",
      contact: "",
      status: "",
      username: "",
      password: "",
      posid: "",
      position: "",
      posprefix: "",
    });
    const confirmPassword = ref("");
    const passwordMismatch = ref(false);

    const validatePasswords = () => {
      passwordMismatch.value =
        accountForm.value.password !== confirmPassword.value;
    };

    // Simulate available positions for selection
    const positions = [
      { label: "Secretary", value: "2" },
      { label: "Priest", value: "1" },
      { label: "Cashier", value: "3" },
      { label: "Bookkeeper", value: "4" },
    ];
    const saveAccount = () => {
      const allRulesSatisfied =
        rules.uppercase && rules.lowercase && rules.special;
      if (!allRulesSatisfied) {
        $q.notify({
          type: "negative",
          message: "Password does not meet all requirements.",
        });
        return; // prevent submission
      }

      // Check if passwords match
      if (passwordMismatch.value) {
        $q.notify({
          type: "negative",
          message: "Passwords do not match.",
        });
        return; // prevent submission
      }

      // If both are valid, proceed
      console.log("Form data:", accountForm.value);
      api
        .post("Signup.php", { account: accountForm.value })
        .then((response) => {
          console.log(response);
          loadAccount();
          accountdialog.value = false;
        })
        .catch((error) => {
          reject(error);
          $q.notify({
            type: "negative",
            message: "Network Error",
          });
        });
    };
    const leftDrawerOpen = ref(false);
    const $q = useQuasar();
    const router = useRouter();

    let myObject = ref();
    let sessionkey = SessionStorage.getItem("log");
    if (!sessionkey) {
      router.push({ path: "/" });
    } else {
      myObject.value = JSON.parse(sessionkey);
      console.log(myObject.value);
    }

    // Logout function
    const Logout = () => {
      $q.dialog({
        title: "Logout",
        message: "Are you sure you want to logout?",
        cancel: true,
        persistent: true,
      }).onOk(() => {
        SessionStorage.clear();
        router.push({ path: "/" });
      });
    };

    const columns = [
      {
        name: "pid",
        required: true,
        label: "#",
        align: "left",
        field: (row) => row.pid,
        format: (val) => `${val}`,
        sortable: true,
      },
      {
        name: "fullname",
        align: "center",
        label: "Name",
        field: "fullname",
        sortable: true,
      },
      {
        name: "position",
        label: "Position",
        field: "position",
        align: "center",
        sortable: true,
      },

      {
        name: "username",
        label: "Username",
        field: "username",
        sortable: true,
        sort: (a, b) => parseInt(a, 10) - parseInt(b, 10),
      },
      {
        name: "Status",
        label: "Status",
        field: "status",
        sortable: true,
        sort: (a, b) => parseInt(a, 10) - parseInt(b, 10),
      },
      {
        name: "accountAction",
        label: "Action",
        field: "",
        align: "center",
        sortable: true,
      },
    ];

    const createAccount = (account) => {
      console.log(account);
    };
    const rules = reactive({
      uppercase: false,
      lowercase: false,
      special: false,
    });

    function validatePasswordRules() {
      const pwd = accountForm.value.password;
      rules.uppercase = /[A-Z]/.test(pwd);
      rules.lowercase = /[a-z]/.test(pwd);
      rules.special = /[!@#$%^&*(),.?":{}|<>]/.test(pwd);
    }
    return {
      rules,
      validatePasswordRules,
      passwordMismatch,
      confirmPassword,
      validatePasswords,
      saveAccount,
      positions,
      accountdialog,
      createAccount,
      accountForm,
      account,
      columns,
      menuData,
      dialog2: ref(false),
      Logout,
      myObject,
      active: ref(false),
      leftDrawerOpen,
      tab: ref("events"),
      toggleLeftDrawer() {
        leftDrawerOpen.value = !leftDrawerOpen.value;
      },
    };
  },
});
</script>
