<template>
  <q-layout view="lHh lpR fFf" class="bg-accent">

    <q-header reveal elevated class="bg-info text-dark">
      <q-toolbar>
        <q-btn dense flat round icon="menu" @click="toggleLeftDrawer" />

        <q-toolbar-title>
         
         User Management
        </q-toolbar-title>
        <div class="account">
          <q-avatar>
            <img src="../../../assets/quasar-logo-vertical.svg"/>
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
                  <img src="../../../assets/quasar-logo-vertical.svg"/>
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
                    <q-item
                      clickable
                      v-ripple
                      class="it-1"
                    
                    >
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
                    <q-item
                      clickable
                      v-ripple
                      class="it-1"
                     
                    >
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

    <q-drawer  class="bg-white text-dark q-pa-md" show-if-above v-model="leftDrawerOpen" side="left" behavior="desktop" bordered  >
      <div class="LOGO q-mb-xl"></div>
    <q-item to="/SecretaryDashboard" :active="active" active-class="text-orange" exact @click="activeclick()">
      <q-item-section avatar>
          <q-icon color="primary" name="home" />
      </q-item-section>

      <q-item-section>
          Home
      </q-item-section>
      </q-item>
      
      <q-expansion-item
        expand-separator
        icon="event"
        label="Application"
        header-class="text-dark"
      >
        
          <q-item to="/SecretaryDashboard/Services" :active="active" active-class="text-orange" exact @click="activeclick()">
          <q-item-section avatar></q-item-section>
              <q-item-section>
                Add New Application
              </q-item-section>
          </q-item>
          <q-item to="/SecretaryDashboard" :active="active" active-class="text-orange" exact @click="activeclick()">
          <q-item-section avatar></q-item-section>
              <q-item-section>
                  List
              </q-item-section>
          </q-item>
          </q-expansion-item>
      
          
      <q-expansion-item
        expand-separator
        icon="event"
        label="Event Management"
        header-class="text-dark"
      >
        
          <q-item to="/SecretaryDashboard" :active="active" active-class="text-orange" exact @click="activeclick()">
          <q-item-section avatar></q-item-section>
              <q-item-section>
                  Calendar 
              </q-item-section>
          </q-item>
          <q-item to="/SecretaryDashboard" :active="active" active-class="text-orange" exact @click="activeclick()">
          <q-item-section avatar></q-item-section>
              <q-item-section>
                  Priest Schedule
              </q-item-section>
          </q-item>
          </q-expansion-item>
      
          <q-expansion-item
        expand-separator
        icon="folder_open"
        label=" Cannonical Records"
        header-class="text-dark"
      >
        
      <q-item to="/SecretaryDashboard/Account-Management" :active="active" active-class="text-orange" exact @click="activeclick()">
      <q-item-section avatar>
        
      </q-item-section>

      <q-item-section>
         Marriage Records
      </q-item-section>
      </q-item>
      <q-item to="/SecretaryDashboard/Account-Management" :active="active" active-class="text-orange" exact @click="activeclick()">
      <q-item-section avatar>
        
      </q-item-section>

      <q-item-section>
        Baptismal Records
      </q-item-section>
      </q-item>
      <q-item to="/SecretaryDashboard/Account-Management" :active="active" active-class="text-orange" exact @click="activeclick()">
      <q-item-section avatar>
        
      </q-item-section>

      <q-item-section>
         Burial Records
      </q-item-section>
      </q-item>
          <q-item to="/SecretaryDashboard" :active="active" active-class="text-orange" exact @click="activeclick()">
          <q-item-section avatar></q-item-section>
              <q-item-section>
                Confirmation Records
              </q-item-section>
          </q-item>
          </q-expansion-item>
      
      
      

      
      <q-item to="/SecretaryDashboard/Account-Management" :active="active" active-class="text-orange" exact @click="activeclick()">
      <q-item-section avatar>
          <q-icon color="primary" name="group" />
      </q-item-section>

      <q-item-section>
          Accounts
      </q-item-section>
      </q-item>
      <q-item to="/SecretaryDashboard/Account-Management" :active="active" active-class="text-orange" exact @click="activeclick()">
      <q-item-section avatar>
          <q-icon color="primary" name="dvr" />
      </q-item-section>

      <q-item-section>
          Logs and Audit Trails
      </q-item-section>
      </q-item>
    </q-drawer>

    <q-page-container class="bg-accent">
     
     <div class="banner q-pa-lg">
      
      <q-banner class="banner-plate bg-secondary text-dark q-pa-md">
      <p class="text-subtitle">  Current Users: {{ myObject.Fullname }}</p>
        
    </q-banner>
    <q-banner class="banner-plate bg-accent text-dark q-pa-md">
      <p class="text-subtitle"> <q-btn label="Add User" color="primary" @click="openAddUser = true" />
</p>
        
    </q-banner>
     </div> 
            <!-- Add User Dialog -->
            <q-dialog v-model="openAddUser" persistent>
              <q-card>
                <q-card-section class="row items-center">
                  <div class="form">
                    <q-form
                  class="flex q-gutter-lg"
                  @submit.prevent="
                    submit_Account_data(AccountDataPayload)
                  "
                >
                  <div class="input q-mb-md">
                    <p class="input-user q-mb-xs">Profile</p>

                    <div class="title-desc q-pl-sm">
                      <div
                        class="Attachments bg-grey-2 q-pa-sm q-mb-sm"
                        style="width: 100%"
                      >
                        <div class="q-mb-sm q-pa-sm flex justify-center">
                          <div class="q-gutter-sm justify-center">
                            <div>
                              <q-avatar size="140px">
                                <img :src="images" />
                              </q-avatar>
                            </div>
                            <q-btn
                              unelevated
                              class="bg-white"
                              @click="pickFile"
                              label="Upload Photo"
                            ></q-btn>
                            <input
                              ref="fileInput"
                              type="file"
                              accept=".jpg, image/*"
                              style="display: none"
                              @change="handleFileUpload"
                            />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div>
                    <div style="display: none">
                      {{ (AccountDataPayload.Attachment = images) }}
                    </div>

                    <div class="input q-mb-md">
                      <p class="input-user q-mb-xs">Name *</p>
                      <q-input
                        outlined
                        :dense="true"
                        v-model="AccountDataPayload.name"
                      ></q-input>
                    </div>
                    <div class="input q-mb-md">
                      <p class="input-user q-mb-xs">Username *</p>
                      <q-input
                        outlined
                        :dense="true"
                        v-model="AccountDataPayload.username"
                        :rules="[(val) => val.length > 2 || 'Too short']"
                      ></q-input>
                    </div>
                    <div class="input q-mb-md">
                      <p class="input-user q-mb-xs">Position *</p>
                      <q-select
                        :dense="true"
                        outlined
                        v-model="AccountDataPayload.position"
                        :options="options"
                      />
                    </div>
                    <div
                      class="passwordForm q-mb-md flex q-gutter-sm"
                      style="width: 100%"
                    >
                      <div class="input-user" style="width: 48%">
                        <p class="q-mb-xs">Password *</p>
                        <q-input
                          :dense="true"
                          v-model="AccountDataPayload.password"
                          outlined
                          :type="isPwd ? 'password' : 'text'"
                          @update:model-value="
                            passwrodVerifiy(AccountDataPayload.password)
                          "
                          @focus="showValList"
                          @blur="defaultVal"
                        >
                          <template v-slot:append>
                            <q-icon
                              :name="isPwd ? 'visibility_off' : 'visibility'"
                              class="cursor-pointer"
                              @click="isPwd = !isPwd"
                            />
                          </template>
                        </q-input>
                        <div
                          v-if="ShowValidation == true"
                          class="validatePass flex column q-gutter-xs"
                        >
                          <div v-if="length == true">
                            <p class="text-red q-mb-xs">
                              Password is too short
                            </p>
                          </div>
                          <div v-if="isLower == true">
                            <p class="text-red q-mb-xs">
                              Password must contain any lowercase
                            </p>
                          </div>
                          <div v-if="isUpper == true">
                            <p class="text-red q-mb-xs">
                              Password must contain any uppercase letter
                            </p>
                          </div>
                          <div v-if="isSpecial == true">
                            <p class="text-red q-mb-xs">
                              Password must contain any special character
                            </p>
                          </div>
                          <div v-if="isNumber == true">
                            <p class="text-red q-mb-xs">
                              Password must contain Number
                            </p>
                          </div>
                        </div>
                      </div>
                      <!-- <div style="display: none">
                        {{ (logsData.AccountID = myObjects.UID) }}
                        {{ (logsData.Username = myObjects.Username) }}
                        {{ logsData.Own_By }}
                        {{ (logsData.Module = "Manage-Accounts") }}
                        {{ (logsData.Action_Taken = "Add New User") }}
                        {{ logsData.Old_Data }}
                        {{ logsData.New_Data }}
                      </div> -->
                      <div class="input-user" style="width: 48%">
                        <p class="q-mb-xs">Confirm Password *</p>
                        <q-input
                          :dense="true"
                          v-model="AccountDataPayload.confirm_password"
                          outlined
                          :type="isPwd ? 'password' : 'text'"
                          @blur="defaultVal"
                        >
                          <template v-slot:append>
                            <q-icon
                              :name="isPwd ? 'visibility_off' : 'visibility'"
                              class="cursor-pointer"
                              @click="isPwd = !isPwd"
                            />
                          </template>
                        </q-input>
                      </div>
                    </div>
                    <p>{{ warning }}</p>
                  </div>

                  <div
                    class="button-group q-gutter-sm flex justify-end"
                    style="width: 100%"
                  >
                    <q-btn
                      unelevated
                      label="Cancel"
                      class="bg-grey-2"
                      style="width: 100px"
                      @click="clear(AccountDataPayload)"
                    >
                    </q-btn>
                    <q-btn
                      unelevated
                      type="submit"
                      label="Create"
                      class="bg-amber-6 text-white"
                      style="width: 100px"
                    >
                    </q-btn>
                  </div>
                </q-form>
                  </div>
                </q-card-section>

            
                <!-- Notice v-close-popup -->
                <q-card-actions align="right">
             
                  <q-btn flat label="Cancel " color="primary" v-close-popup />
                </q-card-actions>
              </q-card>
            </q-dialog>
              
    </q-page-container>

  </q-layout>
</template>

<script>
import { ref ,defineComponent } from 'vue';
import { useQuasar, SessionStorage } from "quasar";
import { useRouter, useRoute } from "vue-router";

export default defineComponent( {
  components : {},
  setup () {
    const leftDrawerOpen = ref(false);
    const $q = useQuasar();
    let datenow = new Date().toLocaleString();
    const router = useRouter();
    let openAddUser = ref(false);
    let myObject = ref();
    let sessionkey = SessionStorage.getItem("log");
    if (sessionkey === null || "") {
      router.push({ path: "/" });
    } else {
      myObject.value = JSON.parse(sessionkey);
      console.log(myObject.value);
    }
   
    let active=ref(false);
      let activeclick=()=>{
          active
          .value=true;
      }
      //logout
      const Logout=()=>{
       
      $q.dialog({
        title: 'Logout',
        message: 'Are you sure you want to logout?',
        cancel: true,
        persistent: true
      }).onOk(() => {
        SessionStorage.clear();
        router.push({ path: "/" });
      }).onOk(() => {
        // console.log('>>>> second OK catcher')
      }).onCancel(() => {
        // console.log('>>>> Cancel')
      }).onDismiss(() => {
        // console.log('I am triggered on both OK and Cancel')
      })
    
      }
    //form data
         // image upload
    let images = ref("https://cdn.quasar.dev/logo-v2/svg/logo.svg");

let logsData = ref({
  AccountID: null,
  Username: null,
  Module: null,
  Own_By: null,
  Action_Taken: null,
  Old_Data: null,
  New_Data: null,
  Date_and_Time: datenow,
});
let filename = ref("profile");
const handleFileUpload = (event) => {
  const files = event.target.files;

  const file = files[0];
  console.log(file.name);
  const reader = new FileReader();
  reader.readAsDataURL(file);
  reader.onload = () => {
    images.value = reader.result;
  };

  console.log(images.value);
  console.log(filename.value);
};

// filepicker
const fileInput = ref(null);
const pickFile = () => {
  fileInput.value.click();
};
// create_account Details

let AccountDataPayload = ref({
  name: null,
  username: null,
  position: null,
  password: null,
  confirm_password: null,
  status: "active",
  Attachment: images.value,
});
let submit_Account_data = (payload) => {
  console.log(payload);
}

//password 
let rules = ref([
      "Password is too short",
      "Password must contain any lowercase",
      " password must contain any uppercase letter",
    ]);

    //form data end
    return {
      openAddUser,
      Logout,
     myObject,
      activeclick,
      menu: ref(false),
      active,
      leftDrawerOpen,
      toggleLeftDrawer () {
        leftDrawerOpen.value = !leftDrawerOpen.value
      },
      //form
      logsData,handleFileUpload,pickFile,AccountDataPayload,submit_Account_data,rules,fileInput,images
    }
  },
  
})
</script>
<style scoped>
@font-face {
font-family: opensans;
src: url(../../../css/Montserrat,Open_Sans/Open_Sans/static/OpenSans-Medium.woff);
}

       
.titlebar{
  font-family: opensans;
  text-decoration: none;
  color:rgb(52, 52, 52)
};

</style>