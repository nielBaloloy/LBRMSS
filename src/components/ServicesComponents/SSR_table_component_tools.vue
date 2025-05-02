<template>
  <div class="q-pa-md">
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
              <q-item clickable v-close-popup @click="print(row)">
                <q-item-section avatar>
                  <q-icon name="print" color="print" />
                </q-item-section>
                <q-item-section>Request Print</q-item-section>
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
      <template v-slot:body-cell-donate="{ row }">
        <q-btn flat @click="donateForm(row)" label="edit" />
      </template>
    </q-table>
    <q-dialog v-model="formdonate">
      <q-card> </q-card>
    </q-dialog>
  </div>
</template>

<script>
import { ref, computed, watch, defineEmits } from "vue";
import { FilterRange } from "src/composables/SeviceData";
import { api } from "src/boot/axios";
export default {
  props: {
    title: { type: String, default: "Payables" },
    columns: { type: Array, required: true },
    rowsData: { type: Array, required: true },
  },
  emits: ["edit", "FilterRanges", "filterData"],
  setup(props, { emit }) {
    const tableRef = ref();
    const filter = ref("");
    const loading = ref(false);
    const pagination = ref({
      sortBy: "name", // Default sorting field
      descending: false,
      page: 1,
      rowsPerPage: 10,
      rowsNumber: props.rowsData.length, // Set initially
    });

    console.log(props.rowsData);
    function editRow(row) {
      emit("edit", row);
    }
    function print(x) {
      const eventId = x["all"].event_id;
    }
    function deleteRow(row) {
      // $q.dialog({
      //   title: "Confirm",
      //   message: "Are you sure you want to delete this row?",
      //   cancel: true,
      //   persistent: true,
      // }).onOk(() => {
      console.log("Deleted row:", row);
      // Add logic to remove the row from your data
      // });
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
        console.log("Received props:", newRows);
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
    let formdonate = ref(false);
    function donateForm(row) {
      var eventid = row.event_id;
      console.log(row);
      formdonate.value = true;
    }
    return {
      formdonate,
      donateForm,
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
      print,
      filters,
      fixed: ref(false),
    };
  },
};
</script>
