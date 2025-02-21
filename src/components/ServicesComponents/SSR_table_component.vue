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
    >
      <template v-slot:top-right>
        <q-input
          borderless
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
        <dialog1
          title="Filter"
          :message="filterContent"
          buttonLabel="Filter"
          buttonColor="black"
          icon="sort"
        />
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
    </q-table>
  </div>
</template>

<script>
import { ref, computed, watch } from "vue";
import dialog1 from "src/components/DashboardComponents/quasarDialog1.vue";
export default {
  props: {
    title: { type: String, default: "Table" },
    columns: { type: Array, required: true },
    rowsData: { type: Array, required: true }, // ✅ Pass an array of objects
  },
  components: { dialog1 },
  setup(props) {
    const tableRef = ref();
    const filter = ref("");
    const loading = ref(false);
    const pagination = ref({
      sortBy: "Client", // Default sorting field
      descending: false,
      page: 1,
      rowsPerPage: 5,
      rowsNumber: props.rowsData.length, // Set initially
    });
    function editRow(row) {
      console.log("Edit row:", row);
      // Add logic to edit the row (e.g., open a dialog with a form)
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
    // ✅ Computed property to filter and sort data
    const filteredRows = computed(() => {
      return props.rowsData
        .filter((row) =>
          row.Client.toLowerCase().includes(filter.value.toLowerCase())
        )
        .sort((a, b) =>
          pagination.value.descending
            ? b[pagination.value.sortBy] > a[pagination.value.sortBy]
              ? 1
              : -1
            : a[pagination.value.sortBy] > b[pagination.value.sortBy]
            ? 1
            : -1
        );
    });

    // ✅ Paginate the data separately
    const paginatedRows = computed(() => {
      const start = (pagination.value.page - 1) * pagination.value.rowsPerPage;
      const end = start + pagination.value.rowsPerPage;
      return filteredRows.value.slice(start, end);
    });

    // ✅ Watch the filtered data to update the total row count (avoids side effect in computed)
    watch(filteredRows, (newFilteredRows) => {
      pagination.value.rowsNumber = newFilteredRows.length;
    });

    function onRequest() {
      loading.value = true;
      setTimeout(() => {
        loading.value = false;
      }, 500); // Simulate a delay
    }

    watch(filter, onRequest);
    let filterContent = `
        <div class="servicfield" v-show="field2">
            Type of Mass
            <select
              outlined
              v-model="formData.TypeofMass"
              :options="massOptions"
              :dense="true"
            />
          </div>
`;

    return {
      editRow,
      deleteRow,
      tableRef,
      filter,
      loading,
      pagination,
      paginatedRows,
      onRequest,
      filterContent,
    };
  },
};
</script>
