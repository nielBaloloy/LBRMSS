<template>
  <div class="">
    <q-table
      flat
      bordered
      ref="tableRef"
      :title="title"
      :rows="paginatedRows"
      :columns="columns"
      row-key="id"
      v-model:pagination="pagination"
      :loading="loading"
      :filter="filter"
      binary-state-sort
      @request="onRequest"
      no-data-label="No Data"
      no-results-label="The filter didn't uncover any results"
    >
      <template v-slot:top-right>
        <q-input
          outlined
          dense
          debounce="300"
          v-model="filter"
          placeholder="Search"
        >
          <template v-slot:append>
            <q-icon name="search" />
          </template>
        </q-input>
      </template>
      <template v-slot:top-left>
        <div class="row items-center q-gutter-md">
          <!-- Filter Button -->
          <q-btn
            label="Filter"
            unelevated
            color="yellow-8"
            @click="fixed = true"
          />

          <!-- Status Filter -->
          <div class="row items-center">
            <q-item-label class="text-bold q-mr-sm">Status:</q-item-label>
            <q-select
              v-model="filters.status"
              :options="statusOptions"
              outlined
              dense
              style="min-width: 150px"
              option-value="value"
              option-label="label"
              emit-value
              map-options
              @update:modelValue="emitFilterEvent"
            />
          </div>
        </div>

        <!-- Filter Dialog -->
        <q-dialog v-model="fixed">
          <q-card style="width: 400px; max-width: 90vw; padding: 2%">
            <q-card-section>
              <div class="text-h6">Filter Data</div>
            </q-card-section>

            <q-separator />

            <q-card-section style="max-height: 70vh" class="scroll">
              <q-form @submit.prevent="emitFilterEvent" class="q-gutter-md">
                <!-- Filter Date Range -->
                <div class="text-subtitle1">Filter Date Range</div>
                <div class="row q-col-gutter-md">
                  <q-input
                    outlined
                    dense
                    v-model="filters.dateFrom"
                    label="Date From"
                    type="date"
                    class="col"
                  />
                  <q-input
                    outlined
                    dense
                    v-model="filters.dateTo"
                    label="Date To"
                    type="date"
                    class="col"
                  />
                </div>

                <!-- Filter Time Range -->
                <div class="text-subtitle1 q-mt-md">Filter Time Range</div>
                <div class="row q-col-gutter-md">
                  <q-input
                    outlined
                    v-model="filters.timeFrom"
                    mask="time"
                    class="col"
                    dense
                  >
                    <template v-slot:append>
                      <q-icon name="access_time" class="cursor-pointer">
                        <q-popup-proxy
                          cover
                          transition-show="scale"
                          transition-hide="scale"
                        >
                          <q-time v-model="filters.timeFrom" format24h>
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

                  <q-input
                    outlined
                    v-model="filters.timeTo"
                    mask="time"
                    class="col"
                    dense
                  >
                    <template v-slot:append>
                      <q-icon name="access_time" class="cursor-pointer">
                        <q-popup-proxy
                          cover
                          transition-show="scale"
                          transition-hide="scale"
                        >
                          <q-time v-model="filters.timeTo" format24h>
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
              </q-form>
            </q-card-section>

            <q-separator />

            <q-card-actions align="right">
              <q-btn flat label="Cancel" color="negative" v-close-popup />
              <q-btn
                flat
                label="Apply Filters"
                color="primary"
                @click="emitFilterEvent"
                v-close-popup
              />
            </q-card-actions>
          </q-card>
        </q-dialog>
      </template>
      <template v-slot:no-data="{ message }">
        <div
          class="full-width column flex-center text-accent q-gutter-md q-pa-lg"
        >
          <q-icon name="delete" size="10em" color="grey-4" />
          <span class="text-subtitle1" style="color: dimgray">{{
            message
          }}</span>
        </div>
      </template>

      <!-- Custom slot for Action column -->
      <template v-slot:body-cell-Action="{ row }">
        <q-td>
          <q-btn-dropdown
            dense
            flat
            icon="more_vert"
            dropdown-icon="null"
            size="sm"
          >
            <q-list>
              <q-item clickable v-close-popup @click="editRow(row)">
                <q-item-section avatar>
                  <q-icon name="edit" color="primary" />
                </q-item-section>
                <q-item-section>Edit</q-item-section>
              </q-item>
              <q-item
                v-if="row.EventProgress == 'Scheduled'"
                clickable
                v-close-popup
                @click="deleteRow(row)"
              >
                <q-item-section avatar>
                  <q-icon name="cancel" color="warning" />
                </q-item-section>
                <q-item-section>Cancel</q-item-section>
              </q-item>
              <q-item clickable v-close-popup @click="deleteRow(row)">
                <q-item-section avatar>
                  <q-icon name="delete" color="negative" />
                </q-item-section>
                <q-item-section>Delete</q-item-section>
              </q-item>
            </q-list>
          </q-btn-dropdown>
        </q-td>
      </template>

      <template v-slot:body-cell="props">
        <q-td :props="props">
          <template v-if="props.col.name === 'Status'">
            <q-badge
              :color="getStatusColor(props.row.EventProgress)"
              class="q-mr-sm"
            >
              <q-icon
                :name="getStatusIcon(props.row.EventProgress)"
                class="q-mr-xs"
              />
              {{ props.row.EventProgress }}
            </q-badge>
          </template>
          <template v-else>
            {{ props.row[props.col.field] }}
          </template>
        </q-td>
      </template>
      <template v-slot:body-cell-details="{ row }">
        <q-td>
          <div>
            <div><strong>Service:</strong> {{ row.Service }}</div>
            <div><strong>Venue:</strong> {{ row.all.venue_name }}</div>
            <div><strong>Date : </strong> {{ row.all.date }}</div>
            <div>
              <strong>Time : </strong> {{ row.all.time_from }} to
              {{ row.all.time_to }}
            </div>
            <div><strong>Event Type : </strong>{{ row.Type }}</div>
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-Requirements="{ row }">
        <q-td>
          <div>
            <center>
              <div v-if="row.RequirementStatus == 'Complete'">
                <p class="text-subtitle2 text-green-5">
                  <q-chip dense color="green-5" text-color="white">
                    {{ row.RequirementStatus }}
                  </q-chip>
                </p>
              </div>
              <div v-if="row.RequirementStatus == 'Incomplete'">
                <q-chip dense color="green-5" text-color="white">
                  {{ row.RequirementStatus }}
                </q-chip>
              </div>
            </center>
          </div>
        </q-td>
      </template>
    </q-table>

    <q-dialog v-model="Editdialog" style="width: 950px">
      <div class="q-gutter-y-md" style="max-width: 90vw">
        <editDialogPanel :editables="editObject" @close-dialog="closeDialog" />
      </div>
    </q-dialog>
    <q-dialog v-model="MrriageDialog" style="width: 950px">
      <div class="q-gutter-y-md" style="max-width: 90vw">
        <editMrriageDialog
          :editables="editObject"
          @close-dialog="closeDialog"
        />
      </div>
    </q-dialog>
    <q-dialog v-model="Baptism" style="width: 950px">
      <div class="q-gutter-y-md" style="max-width: 90vw">
        <editBaptismDialog
          :editables="editObject"
          @close-dialog="closeDialog"
        />
      </div>
    </q-dialog>
    <q-dialog v-model="Burial" style="width: 950px">
      <div class="q-gutter-y-md" style="max-width: 90vw">
        <editBurialDialog :editables="editObject" @close-dialog="closeDialog" />
      </div>
    </q-dialog>
    <q-dialog v-model="Blessing" style="width: 950px">
      <div class="q-gutter-y-md" style="max-width: 90vw">
        <editBlessingDialog
          :editables="editObject"
          @close-dialog="closeDialog"
        />
      </div>
    </q-dialog>
  </div>
</template>

<script>
import { ref, computed, watch, defineEmits, defineComponent } from "vue";
import { useQuasar } from "quasar";
import { FilterRange } from "src/composables/SeviceData";
import editDialogPanel from "src/components/ServicesComponents/PopupDialog_Confirmation_edit.vue";
import editMrriageDialog from "src/components/ServicesComponents/popUpDialogMariageEdit.vue";
import editBaptismDialog from "src/components/ServicesComponents/popUpDialogBaptismEdit.vue";
import editBurialDialog from "src/components/ServicesComponents/popUpDialog_Burial_edit.vue";
import editBlessingDialog from "src/components/ServicesComponents/popUpDialog_Blessing_edit.vue";
import { getSerivce, Data } from "src/composables/SeviceData.js";
import { api } from "src/boot/axios";
export default defineComponent({
  props: {
    title: { type: String, default: "Table" },
    columns: { type: Array, required: true },
    rowsData: { type: Array, required: true },
    getStatusColor: Function,
    getStatusIcon: Function, // ✅ Pass an array of objects
  },
  emits: ["filterData", "FilterRanges"],
  components: {
    editDialogPanel,
    editMrriageDialog,
    editBaptismDialog,
    editBurialDialog,
    editBlessingDialog,
  },
  setup(props, { emit }) {
    const tableRef = ref();
    const filter = ref("");
    const loading = ref(false);
    let Editdialog = ref(false);
    let MrriageDialog = ref(false);
    let Baptism = ref(false);
    let Burial = ref(false);
    let Blessing = ref(false);
    let $q = useQuasar();
    const pagination = ref({
      sortBy: "Client",
      descending: false,
      page: 1,
      rowsPerPage: 10,
      rowsNumber: props.rowsData.length,
    });
    const closeDialog = () => {
      Editdialog.value = false; // Close the dialog
    };
    const editObject = ref([]);

    function editRow(row) {
      console.log("Edit row:", row);

      var service = row.all.mainid;
      switch (service) {
        case 1:
          editObject.value = [];
          editObject.value.push(row);
          MrriageDialog.value = true;
          break;
        case 2:
          editObject.value = [];
          editObject.value.push(row);
          Baptism.value = true;
          break;
        case 3:
          editObject.value = [];
          editObject.value.push(row);
          Editdialog.value = true;
          break;
        case 4:
          editObject.value = [];
          editObject.value.push(row);
          Burial.value = true;
          break;
        case 6:
          editObject.value = [];
          editObject.value.push(row);
          Blessing.value = true;
          break;
        default:
          break;
      }
    }

    function deleteRow(row) {
      let deletedId = row.all.event_id ?? row.all.maineventid;
      $q.dialog({
        title: "Delete this Information",
        message: "Are you sure you want to delete this row?",
        cancel: true,
        persistent: true,
      }).onOk(() => {
        console.log("Deleted row:", deletedId);
        api
          .delete("Service.php", { data: { deletedId: deletedId } })
          .then((response) => {
            if (response.data.Status == "Success") {
              $q.notify({
                type: "positive",
                message: "Row deleted successfully!",
              });
              getSerivce(0);
            } else {
              $q.notify({
                type: "Success",
                message: "Deleted Successfully!",
              });
              getSerivce(0);
            }
          })
          .catch((error) =>
            $q.notify({
              type: "negative",
              message: "Error!",
              error,
            })
          );
      });
    }
    // ✅ Computed property to filter and paginate
    const filteredRows = computed(() => {
      if (!filter.value) return props.rowsData;
      const lowerFilter = filter.value.toLowerCase();
      return props.rowsData.filter((row) =>
        Object.values(row).some(
          (value) =>
            value && value.toString().toLowerCase().includes(lowerFilter)
        )
      );
    });

    const paginatedRows = computed(() => {
      const start = (pagination.value.page - 1) * pagination.value.rowsPerPage;
      const end = start + pagination.value.rowsPerPage;
      return filteredRows.value.slice(start, end);
    });

    watch(filteredRows, (newFilteredRows) => {
      pagination.value.rowsNumber = newFilteredRows.length;
    });

    function onRequest(props) {
      console.log("Received props:", props);

      if (!props.pagination) {
        console.warn("Pagination data is undefined.");
        return;
      }

      const { page, rowsPerPage } = props.pagination;
      pagination.value.page = page;
      pagination.value.rowsPerPage = rowsPerPage;
    }
    // ✅ Ensure pagination updates properly
    watch(
      pagination,
      () => {
        pagination.value.rowsNumber = filteredRows.value.length;
      },
      { deep: true }
    );

    // ✅ Ensure `rowsNumber` updates if `rowsData` changes dynamically
    watch(
      () => props.rowsData,
      (newRows) => {
        pagination.value.rowsNumber = newRows.length;
      },
      { deep: true, immediate: true }
    );

    watch(filter, onRequest);
    //========= Filter ===========================
    const filters = ref({
      dateFrom: "",
      dateTo: "",
      timeFrom: "",
      timeTo: "",
      status: "0", // Initial status is empty
    });

    // Define the emit function
    const statusOptions = [
      { label: "Pending", value: "0" },
      { label: "Scheduled", value: "1" },
      { label: "Done", value: "2" },
    ];
    const emitFilterEvent = () => {
      emit("filterData", filters.value);
      console.log(filters.value);
    };

    let FilterData = (filterpayload) => {
      emit("FilterRanges", filterpayload.value);
    };

    return {
      Burial,
      closeDialog,
      FilterData,
      statusOptions,
      emitFilterEvent,
      editRow,
      deleteRow,
      tableRef,
      filter,
      loading,
      pagination,
      paginatedRows,
      onRequest,
      filteredRows,
      filters,
      fixed: ref(false),
      editObject,
      Editdialog,
      MrriageDialog,
      Baptism,
      Blessing,
    };
  },
});
</script>
