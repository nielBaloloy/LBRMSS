<template>
    <div class="q-pa-sm row items-start q-gutter-md">
      <q-card class="my-card bg-white" flat bordered>
        <q-card-section>
        <div class="CONTENT row justify-between">
            <p class="text-h6">  Scheduled Events</p>
        <span class="count text-h2 text-warning">56</span>
        </div>
        </q-card-section>
      </q-card>
  
      <q-card class="my-card bg-white" flat bordered>
        <q-card-section>
            <div class="CONTENT row justify-between">
            <p class="text-h6">  Pending Events</p>
        <span class="count text-h2 text-warning">56</span>
        </div>
        </q-card-section>
      </q-card>

      <q-card class="my-card bg-white" flat bordered>
        <q-card-section>
            <div class="CONTENT row justify-between">
            <p class="text-h6">  </p>
        <span class="count text-h2 text-warning">56</span>
        </div>
        </q-card-section>
      </q-card>
    </div>
  </template>
  <script>
  import { defineComponent } from 'vue'
  export default defineComponent({
    name: 'EventCard',
    props: {},
    setup () {
      return {
        lorem: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.'
      }
    }
  })
  </script>
  
  <style scoped>
.my-card{
    margin-top: 2em;
    width:31%;
    min-width: 20%;
    height: 140px;
   
}
.count{
    font-weight: bold;
}
  </style>