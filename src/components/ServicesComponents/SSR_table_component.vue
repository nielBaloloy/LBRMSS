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
    </q-table>
  </div>
</template>

<script>
import { ref, computed, watch } from "vue";

export default {
  props: {
    title: { type: String, default: "Table" },
    columns: { type: Array, required: true },
    rowsData: { type: Array, required: true }, // ✅ Pass an array of objects
  },
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

    return { tableRef, filter, loading, pagination, paginatedRows, onRequest };
  },
};
</script>
