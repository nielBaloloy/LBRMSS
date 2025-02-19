<template>
  <div class="q-mt-5 Clients">
    <!-- tabs -->
    <q-card flat bordered class="bg-white">
      <q-tabs
        v-model="tab"
        class="text-grey"
        active-color="primary"
        indicator-color="primary"
        align="justify"
        narrow-indicator
      >
        <q-tab name="Pending" label="Pending" />
        <q-tab name="Schedule" label="Scheduled" @click="GetScheduledData" />
        <q-tab name="Completed" label="Done" @click="GetDoneData" />
      </q-tabs>

      <q-separator />

      <q-tab-panels v-model="tab" animated>
        <q-tab-panel name="Pending">
          <!-- Pending -->
          <q-table flat :rows="PendingData" :columns="columns" row-key="EID">
            <template v-slot:body="props">
              <q-tr :props="props" @click="onRowClick(props.row)">
                <q-td key="name" :props="props">
                  {{ props.row.Client }}
                </q-td>
                <q-td key="Service" :props="props">
                  <q-badge color="green">
                    {{ props.row.Service }}
                  </q-badge>
                </q-td>
                <q-td key="Type" :props="props">
                  <q-badge color="purple">
                    {{ props.row.Type }}
                  </q-badge>
                </q-td>
                <q-td key="Date" :props="props">
                  <q-badge color="orange">
                    {{ props.row.Date }}
                  </q-badge>
                </q-td>
                <q-td key="Venue" :props="props">
                  <q-badge color="primary">
                    {{ props.row.Venue }}
                  </q-badge>
                </q-td>
                y<q-td key="Assigned Priest" :props="props">
                  <q-badge color="teal">
                    {{ props.row.Assigned_Priest }}
                  </q-badge>
                </q-td>
                <q-td key="Action" :props="props" class="q-gutter-sm">
                  <q-btn unelevated color="primary" label="Modify" />
                  <q-btn
                    unelevated
                    color="secondary"
                    label="Cancel"
                    text-color="red"
                  />
                </q-td>
              </q-tr>
            </template>
          </q-table>
        </q-tab-panel>

        <q-tab-panel name="Schedule">
          <q-table flat :rows="ScheduledData" :columns="columns" row-key="EID">
            <template v-slot:body="props">
              <q-tr :props="props" @click="onRowClick(props.row)">
                <q-td key="name" :props="props">
                  {{ props.row.Client }}
                </q-td>
                <q-td key="Service" :props="props">
                  <q-badge color="green">
                    {{ props.row.Service }}
                  </q-badge>
                </q-td>
                <q-td key="Type" :props="props">
                  <q-badge color="purple">
                    {{ props.row.Type }}
                  </q-badge>
                </q-td>
                <q-td key="Date" :props="props">
                  <q-badge color="orange">
                    {{ props.row.Date }}
                  </q-badge>
                </q-td>
                <q-td key="Venue" :props="props">
                  <q-badge color="primary">
                    {{ props.row.Venue }}
                  </q-badge>
                </q-td>
                <q-td key="Assigned Priest" :props="props">
                  <q-badge color="teal">
                    {{ props.row.Assigned_Priest }}
                  </q-badge>
                </q-td>
                <q-td key="Action" :props="props" class="q-gutter-md">
                  <q-btn unelevated color="primary" label="Edit" />
                  <q-btn
                    unelevated
                    color="secondary"
                    label="Delete"
                    text-color="red"
                  />
                </q-td>
              </q-tr>
            </template>
          </q-table>
        </q-tab-panel>

        <q-tab-panel name="Completed">
          <q-table flat :rows="DoneData" :columns="columns" row-key="EID">
            <template v-slot:body="props">
              <q-tr :props="props" @click="onRowClick(props.row)">
                <q-td key="name" :props="props">
                  {{ props.row.Client }}
                </q-td>
                <q-td key="Service" :props="props">
                  <q-badge color="green">
                    {{ props.row.Service }}
                  </q-badge>
                </q-td>
                <q-td key="Type" :props="props">
                  <q-badge color="purple">
                    {{ props.row.Type }}
                  </q-badge>
                </q-td>
                <q-td key="Date" :props="props">
                  <q-badge color="orange">
                    {{ props.row.Date }}
                  </q-badge>
                </q-td>
                <q-td key="Venue" :props="props">
                  <q-badge color="primary">
                    {{ props.row.Venue }}
                  </q-badge>
                </q-td>
                <q-td key="Assigned Priest" :props="props">
                  <q-badge color="teal">
                    {{ props.row.Assigned_Priest }}
                  </q-badge>
                </q-td>
                <q-td key="Action" :props="props" class="q-gutter-md">
                  <q-btn unelevated color="primary" label="Edit" />
                  <q-btn
                    unelevated
                    color="secondary"
                    label="Delete"
                    text-color="red"
                  />
                </q-td>
              </q-tr>
            </template>
          </q-table>
        </q-tab-panel>
      </q-tab-panels>
    </q-card>
  </div>
</template>

<script>
import { ref, defineComponent, onMounted } from "vue";
import {
  getSerivce,
  PendingData,
  ScheduledData,
  DoneData,
} from "../../composables/SeviceData.js";
export default defineComponent({
  name: "ClientsDashboard",
  setup() {
    const columns = [
      {
        name: "name",
        required: true,
        label: "Client",
        align: "left",
        field: (row) => row.name,
        format: (val) => `${val}`,
        sortable: true,
      },
      {
        name: "Service",
        align: "center",
        label: "Service",
        field: "Service",
        sortable: true,
      },
      {
        name: "Type",
        label: "Type ",
        field: "Type",
        sortable: true,
        align: "center",
      },
      { name: "Date", label: "Date ", field: "Date", align: "center" },
      { name: "Venue", label: "Venue ", field: "Venue", align: "center" },
      {
        name: "Assigned Priest",
        label: "Assigned Priest ",
        field: "Assigned Priest",
        align: "center",
      },
      { name: "Action", label: "Action", field: "Action", align: "center" },
    ];
    onMounted(() => {
      let loads = "pending";
      getSerivce(loads);
    });
    let GetScheduledData = () => {
      let loads = "Scheduled";
      getSerivce(loads);
    };
    let GetDoneData = () => {
      let loads = "Done";
      getSerivce(loads);
    };

    return {
      GetScheduledData,
      GetDoneData,
      onRowClick: (row) => alert(`${row.name} clicked`),
      columns,
      PendingData,
      ScheduledData,
      DoneData,
      tab: ref("Pending"),
    };
  },
});
</script>
<style scoped></style>
