<template>
  <q-layout view="lHh lpR fFf" class="bg-accent">
    <q-header reveal elevated class="bg-info text-dark">
      <q-toolbar>
        <q-btn dense flat round icon="menu" @click="toggleLeftDrawer" />

        <q-toolbar-title> Account </q-toolbar-title>
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
        <q-banner inline-actions class="text-black bg-grey-2 q-pa-lg">
          <label class="text-h6">Account Setup</label>
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
          <accountTable
            :columns="columns"
            :rowsData="account"
            @edit="edit"
            @delete="deleteAccount"
          />
        </div>
      </q-page>
      <q-dialog v-model="accountdialog">
        <q-card style="width: 900px" class="q-pa-lg rounded-borders shadow-2">
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

              <div class="text-subtitle1 text-primary q-mt-lg q-mb-sm">
                Profile Image & Basic Info
              </div>

              <div class="row q-col-gutter-md q-pa-md">
                <!-- Left Side: Image -->
                <div class="col-4">
                  <!-- Image Preview -->
                  <q-img
                    v-if="accountForm.imageUrl"
                    :src="accountForm.imageUrl"
                    :ratio="1"
                    class="rounded-borders"
                    style="max-width: 150px; border: 1px solid #ccc"
                  />
                  <div
                    v-else
                    style="
                      max-width: 150px;
                      height: 150px;
                      border: 1px dashed #ccc;
                      display: flex;
                      align-items: center;
                      justify-content: center;
                    "
                  >
                    <span class="text-caption text-grey">No preview</span>
                  </div>

                  <!-- File Uploader Below Image -->
                  <q-file
                    v-model="accountForm.image"
                    label="Upload Image"
                    accept="image/*"
                    outlined
                    clearable
                    dense
                    @update:model-value="previewImage"
                    class="q-mt-sm"
                  />
                </div>

                <!-- Right Side: Inputs -->
                <div class="col-8 row q-col-gutter-md">
                  <q-input
                    v-model="accountForm.fname"
                    label="First Name"
                    outlined
                    dense
                    class="col-6"
                  />
                  <q-input
                    v-model="accountForm.mname"
                    label="Middle Name"
                    outlined
                    dense
                    class="col-6"
                  />
                  <q-input
                    v-model="accountForm.lnamme"
                    label="Last Name"
                    outlined
                    dense
                    class="col-6"
                  />
                  <q-input
                    v-model="accountForm.suffix_name"
                    label="Suffix"
                    outlined
                    dense
                    class="col-6"
                  />
                  <q-input
                    v-model="accountForm.contact"
                    label="Contact"
                    outlined
                    dense
                    class="col-12"
                  />
                </div>
              </div>

              <!-- Section Title -->
              <div class="text-subtitle1 text-primary q-mt-lg q-mb-sm">
                Credentials
              </div>
              <q-input
                v-model="accountForm.username"
                label="Username"
                outlined
                dense
                class="col-4"
              />
              <!-- Password Fields (2 columns) -->
              <div class="row q-col-gutter-md q-pa-md">
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
              <div class="text-subtitle1 text-primary">User Information</div>

              <ul class="">
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
              <div class="row q-col-gutter-md q-pa-md">
                <q-select
                  v-model="accountForm.posid"
                  :options="positions"
                  label="Position"
                  outlined
                  dense
                  option-label="label"
                  option-value="value"
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
import { baseUrl, imageBase } from "src/data/menuData";
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
      mode: 0,
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
      $q.dialog({
        title: "Account Confirmation",
        message: "Information will be saved to database",
        cancel: true,
        persistent: true,
      })
        .onOk(() => {
          if (accountForm.value.mode == "0") {
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
            const formData = new FormData();
            formData.append("fname", accountForm.value.fname);
            formData.append("mname", accountForm.value.mname);
            formData.append("lname", accountForm.value.lnamme);
            formData.append("suffix_name", accountForm.value.suffix_name);
            formData.append("contact", accountForm.value.contact);
            formData.append("username", accountForm.value.username);
            formData.append("password", accountForm.value.password);
            formData.append("posid", accountForm.value.posid);
            formData.append("posprefix", accountForm.value.posprefix);
            formData.append("status", accountForm.value.status);

            if (accountForm.value.image) {
              formData.append("image", accountForm.value.image);
            }

            // If both are valid, proceed
            console.log("Form data:", accountForm.value);

            api
              .post("Signup.php", formData, {
                headers: {
                  "Content-Type": "multipart/form-data",
                },
              })
              .then((response) => {
                console.log(response.data);
                $q.notify({
                  type: "success",
                  message: "Account Created Successfully",
                });
                loadAccount();
                accountdialog.value = false;
              })
              .catch((error) => {
                console.error(error);
                $q.notify({
                  type: "negative",
                  message: "Network Error",
                });
              });
          } else {
            api
              .put("Signup.php", { account: accountForm.value })
              .then((response) => {
                console.log(response);
                loadAccount();
                accountdialog.value = false;
                $q.notify({
                  type: "success",
                  message: "Account has Updated",
                });
              })
              .catch((error) => {
                reject(error);
                $q.notify({
                  type: "negative",
                  message: "Network Error",
                });
              });
          }
        })

        .onCancel(() => {
          console.log(">>>> Cancel");
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
        align: "center",
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
    function edit(row) {
      accountForm.value = {
        pid: row.all.pid,
        fname: row.all.fname,
        mname: row.all.mname,
        lnamme: row.all.lname, // typo retained if intentional
        suffix_name: row.all.suffix,
        contact: row.all.contact,
        status: row.all.isActive,
        username: row.all.Username,
        password: row.all.Password,
        posid: row.all.pos_id.toString(), // ✅ correctly bind the position ID
        position: "",
        posprefix: "",
        mode: 1,
        image: null, // <-- holds uploaded image File
        imageUrl: imageBase + row.all.photo, // <-- holds preview URL
      };
      accountdialog.value = true;
      console.log(row.all);
    }
    function deleteAccount(row) {
      api
        .delete("Signup.php", { data: { delete: row.pid } })
        .then((response) => {
          console.log(response);
          loadAccount();
          accountdialog.value = false;
          $q.notify({
            type: "negative",
            message: "Network Error",
          });
        })
        .catch((error) => {
          reject(error);
          $q.notify({
            type: "negative",
            message: "Network Error",
          });
        });
    }
    function previewImage(file) {
      if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
          accountForm.value.imageUrl = e.target.result;
        };
        reader.readAsDataURL(file);
      } else {
        accountForm.value.imageUrl = "";
      }
    }

    return {
      previewImage,
      deleteAccount,
      edit,
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
