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

        <q-btn
          v-if="titles == 'Financial Record'"
          color="green-5"
          icon-right="archive"
          label="Export to csv"
          no-caps
          @click="exportTable"
        />
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
      <template v-slot:body-cell-faction="{ row }">
        <q-td>
          <q-btn
            class="bg-primary text-white"
            label="view"
            @click="row"
          ></q-btn>
          <q-btn label="print"></q-btn>
        </q-td>
      </template>
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
                <q-item-section>Print Certificate</q-item-section>
              </q-item>
              <!-- <q-item clickable v-close-popup @click="changeRecord(row)">
                <q-item-section avatar>
                  <q-icon name="restore_page" color="print" />
                </q-item-section>
                <q-item-section>Change of Record</q-item-section>
              </q-item> -->
              <!-- <q-item clickable v-close-popup @click="goodMoral(row)">
                <q-item-section avatar>
                  <q-icon name="description" color="print" />
                </q-item-section>
                <q-item-section>Print Good Moral</q-item-section>
              </q-item> -->
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
      <template v-slot:body-cell-Status="{ row }">
        <center>
          <q-td>
            <q-chip
              v-if="row.status == 'Active'"
              color="green"
              text-color="white"
              size="sm"
            >
              Active
            </q-chip>
            <q-chip v-else color="red" text-color="white" size="sm">
              Inactive
            </q-chip>
          </q-td>
        </center>
      </template>
      <template v-slot:body-cell-accountAction="{ row }">
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
                <q-item-section>Remove</q-item-section>
              </q-item>
            </q-list>
          </q-btn-dropdown>
        </q-td>
      </template>
    </q-table>
    <q-dialog v-model="showPdf">
      <q-card style="width: 90vw; max-width: 900px; height: 90vh">
        <q-bar>
          <div class="text-h6">PDF Preview</div>
          <q-space />
          <q-btn dense flat icon="close" @click="showPdf = false" />
        </q-bar>
        <q-separator />
        <q-card-section class="q-pa-none" style="height: 100%">
          <iframe
            :src="pdfUrl"
            width="100%"
            height="100%"
            style="border: none"
          ></iframe>
        </q-card-section>
      </q-card>
    </q-dialog>
    <q-dialog v-model="showChangeRecordPdf">
      <q-card style="width: 90vw; max-width: 900px; height: 90vh">
        <q-bar>
          <div class="text-h6">Change of Record Preview</div>
          <q-space />
          <q-btn dense flat icon="close" @click="showChangeRecordPdf = false" />
        </q-bar>
        <q-separator />
        <q-card-section class="q-pa-none" style="height: 100%">
          <iframe
            :src="pdfUrl"
            width="100%"
            height="100%"
            style="border: none"
          ></iframe>
        </q-card-section>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
import { ref, computed, watch, defineEmits } from "vue";
import { useQuasar, exportFile } from "quasar";
import { FilterRange } from "src/composables/SeviceData";
import { baseUrl } from "src/data/menuData.js";
import { api } from "src/boot/axios";
function wrapCsvValue(val, formatFn, row) {
  let formatted = formatFn !== void 0 ? formatFn(val, row) : val;

  formatted =
    formatted === void 0 || formatted === null ? "" : String(formatted);

  formatted = formatted.split('"').join('""');
  /**
   * Excel accepts \n and \r in strings, but some other CSV parsers do not
   * Uncomment the next two lines to escape new lines
   */
  // .split('\n').join('\\n')
  // .split('\r').join('\\r')

  return `"${formatted}"`;
}

export default {
  props: {
    title: { type: String, default: "Table" },
    columns: { type: Array, required: true },
    rowsData: { type: Array, required: true },
  },
  emits: ["edit", "FilterRanges", "filterData", "delete"],
  setup(props, { emit }) {
    let titles = props.title;
    const tableRef = ref();
    const filter = ref("");
    const loading = ref(false);
    const showPdf = ref(false);
    const pdfUrl = ref("");
    const $q = useQuasar();
    const pagination = ref({
      sortBy: "Client", // Default sorting field
      descending: false,
      page: 1,
      rowsPerPage: 10,
      rowsNumber: props.rowsData.length, // Set initially
    });
    function editRow(row) {
      emit("edit", row);
    }

    function print(x) {
      $q.dialog({
        title: "Print Certificate",
        message: "Please proceed to the Cashier to settle the payment first",
        cancel: true,
        persistent: true,
      })
        .onOk(() => {
          var id = x.EventServiceID;
          var date = x["Date"];
          //mariiage
          var fullname =
            x["all"]["groom_fname"] +
            " " +
            x["all"]["groom_mname"] +
            " " +
            x["all"]["groom_lname"] +
            " and " +
            x["all"]["bride_fname"] +
            " " +
            x["all"]["bride_mname"] +
            " " +
            x["all"]["bride_lname"];
          //baptism
          var baptismfullname =
            x["all"]["bapt_fname"] +
            " " +
            x["all"]["bapt_mname"] +
            " " +
            x["all"]["bapt_lname"];
          //confirmation
          var confirmationfullname =
            x["all"]["con_fname"] +
            " " +
            x["all"]["con_mname"] +
            " " +
            x["all"]["con_lname"];
          //burial
          var Burialfullname =
            x["all"]["bu_fname"] +
            " " +
            x["all"]["bu_mname"] +
            " " +
            x["all"]["bu_lname"];
          var priest = x["Assigned_Priest"];
          var Service = x["Service"];
          //setup payment request
          api
            .post("certificationpayment.php", { x })
            .then((response) => {
              console.log(response);
              if (response.data.Status == "Success") {
                $q.notify({
                  type: "positive",
                  message: "Payment Request Sent!",
                });
              }
            })
            .catch((error) => {
              console.log(error);
            });

          switch (id) {
            case 1:
              console.log(baseUrl);
              pdfUrl.value =
                baseUrl +
                "marraigeprint.php?date=" +
                encodeURIComponent(date) + // Encoding the date to ensure it's correctly passed
                "&name=" +
                encodeURIComponent(fullname) +
                "&priest=" +
                encodeURIComponent(priest) +
                "&service=" +
                encodeURIComponent(Service); // Encoding the fullname
              showPdf.value = true;
              break;
            case 2:
              pdfUrl.value =
                baseUrl +
                "baptismalCertificate.php?date=" +
                encodeURIComponent(date) + // Encoding the date to ensure it's correctly passed
                "&name=" +
                encodeURIComponent(baptismfullname) +
                "&priest=" +
                encodeURIComponent(priest) +
                "&service=" +
                encodeURIComponent(Service); // Encoding the fullname
              showPdf.value = true;
              break;
            case 3:
              console.log(confirmationfullname);
              pdfUrl.value =
                baseUrl +
                "confirmationCertificate.php?date=" +
                encodeURIComponent(date) + // Encoding the date to ensure it's correctly passed
                "&name=" +
                encodeURIComponent(confirmationfullname) +
                "&priest=" +
                encodeURIComponent(priest) +
                "&service=" +
                encodeURIComponent(Service); // Encoding the fullname
              showPdf.value = true;
              break;
            case 4:
              pdfUrl.value =
                "http://localhost/LBRMSS/Server/API/BurialCert.php?date=" +
                encodeURIComponent(date) + // Encoding the date to ensure it's correctly passed
                "&name=" +
                encodeURIComponent(Burialfullname) +
                "&priest=" +
                encodeURIComponent(priest) +
                "&service=" +
                encodeURIComponent(Service); // Encoding the fullname
              showPdf.value = true;
              break;
          }
        })

        .onCancel(() => {
          // console.log('>>>> Cancel')
        });
    }

    function changeRecord(x) {
      console.log(x);
      var id = x.EventServiceID;
      var date = x["Date"];
      //mariiage
      var fullname =
        x["all"]["groom_fname"] +
        " " +
        x["all"]["groom_mname"] +
        " " +
        x["all"]["groom_lname"] +
        " and " +
        x["all"]["bride_fname"] +
        " " +
        x["all"]["bride_mname"] +
        " " +
        x["all"]["bride_lname"];
      //baptism
      var baptismfullname =
        x["all"]["bapt_fname"] +
        " " +
        x["all"]["bapt_mname"] +
        " " +
        x["all"]["bapt_lname"];
      //confirmation
      var confirmationfullname =
        x["all"]["con_fname"] +
        " " +
        x["all"]["con_mname"] +
        " " +
        x["all"]["con_lname"];
      //burial
      var Burialfullname =
        x["all"]["bu_fname"] +
        " " +
        x["all"]["bu_mname"] +
        " " +
        x["all"]["bu_lname"];
      var priest = x["Assigned_Priest"];
      var Service = x["Service"];

      switch (id) {
        case 1:
          $q.dialog({
            title: "Currently Under development",
            message: "Skip this shit for a while",
          })
            .onOk(() => {
              // console.log('OK')
            })
            .onCancel(() => {
              // console.log('Cancel')
            })
            .onDismiss(() => {
              // console.log('I am triggered on both OK and Cancel')
            });

          // pdfUrl.value =
          //   baseUrl +
          //   "ChangeofRecordPrint.php?date=" +
          //   encodeURIComponent(date) + // Encoding the date to ensure it's correctly passed
          //   "&name=" +
          //   encodeURIComponent(fullname) +
          //   "&priest=" +
          //   encodeURIComponent(priest) +
          //   "&service=" +
          //   encodeURIComponent(Service); // Encoding the fullname
          // showPdf.value = true;
          break;
        case 2:
          $q.dialog({
            title: "Currently Under development",
            message: "Skip this shit for a while",
          })
            .onOk(() => {
              // console.log('OK')
            })
            .onCancel(() => {
              // console.log('Cancel')
            })
            .onDismiss(() => {
              // console.log('I am triggered on both OK and Cancel')
            });

          // pdfUrl.value =
          //   baseUrl +
          //   "ChangeofRecordPrint.php?date=" +
          //   encodeURIComponent(date) + // Encoding the date to ensure it's correctly passed
          //   "&name=" +
          //   encodeURIComponent(baptismfullname) +
          //   "&priest=" +
          //   encodeURIComponent(priest) +
          //   "&service=" +
          //   encodeURIComponent(Service); // Encoding the fullname
          // showPdf.value = true;
          break;
        case 3:
          $q.dialog({
            title: "Currently Under development",
            message: "Skip this shit for a while",
          })
            .onOk(() => {
              // console.log('OK')
            })
            .onCancel(() => {
              // console.log('Cancel')
            })
            .onDismiss(() => {
              // console.log('I am triggered on both OK and Cancel')
            });

          // console.log(confirmationfullname);
          // pdfUrl.value =
          //   baseUrl +
          //   "ChangeofRecordPrint.php?date=" +
          //   encodeURIComponent(date) + // Encoding the date to ensure it's correctly passed
          //   "&name=" +
          //   encodeURIComponent(confirmationfullname) +
          //   "&priest=" +
          //   encodeURIComponent(priest) +
          //   "&service=" +
          //   encodeURIComponent(Service); // Encoding the fullname
          // showPdf.value = true;
          break;
        case 4:
          $q.dialog({
            title: "Currently Under development",
            message: "Skip this shit for a while",
          })
            .onOk(() => {
              // console.log('OK')
            })
            .onCancel(() => {
              // console.log('Cancel')
            })
            .onDismiss(() => {
              // console.log('I am triggered on both OK and Cancel')
            });

        // pdfUrl.value =
        //   baseUrl +
        //   "BurialCert.php?date=" +
        //   encodeURIComponent(date) + // Encoding the date to ensure it's correctly passed
        //   "&name=" +
        //   encodeURIComponent(Burialfullname) +
        //   "&priest=" +
        //   encodeURIComponent(priest) +
        //   "&service=" +
        //   encodeURIComponent(Service); // Encoding the fullname
        // showPdf.value = true;
      }
    }

    // good mmoral
    function goodMoral(x) {
      console.log(x);
      var id = x.EventServiceID;
      var date = x["Date"];
      //mariiage
      var fullname =
        x["all"]["groom_fname"] +
        " " +
        x["all"]["groom_mname"] +
        " " +
        x["all"]["groom_lname"] +
        " and " +
        x["all"]["bride_fname"] +
        " " +
        x["all"]["bride_mname"] +
        " " +
        x["all"]["bride_lname"];
      //baptism
      var baptismfullname =
        x["all"]["bapt_fname"] +
        " " +
        x["all"]["bapt_mname"] +
        " " +
        x["all"]["bapt_lname"];
      //confirmation
      var confirmationfullname =
        x["all"]["con_fname"] +
        " " +
        x["all"]["con_mname"] +
        " " +
        x["all"]["con_lname"];
      //burial
      var Burialfullname =
        x["all"]["bu_fname"] +
        " " +
        x["all"]["bu_mname"] +
        " " +
        x["all"]["bu_lname"];
      var priest = x["Assigned_Priest"];
      var Service = x["Service"];

      switch (id) {
        case 1:
          $q.dialog({
            title: "Currently Under development",
            message: "Skip this shit for a while",
          })
            .onOk(() => {
              // console.log('OK')
            })
            .onCancel(() => {
              // console.log('Cancel')
            })
            .onDismiss(() => {
              // console.log('I am triggered on both OK and Cancel')
            });
          // pdfUrl.value =
          //   baseUrl +
          //   "goodMoral.php?date=" +
          //   encodeURIComponent(date) + // Encoding the date to ensure it's correctly passed
          //   "&name=" +
          //   encodeURIComponent(fullname) +
          //   "&priest=" +
          //   encodeURIComponent(priest) +
          //   "&service=" +
          //   encodeURIComponent(Service); // Encoding the fullname
          // showPdf.value = true;
          break;
        case 2:
          pdfUrl.value =
            baseUrl +
            "goodMoral.php?date=" +
            encodeURIComponent(date) + // Encoding the date to ensure it's correctly passed
            "&name=" +
            encodeURIComponent(baptismfullname) +
            "&priest=" +
            encodeURIComponent(priest) +
            "&service=" +
            encodeURIComponent(Service); // Encoding the fullname
          showPdf.value = true;
          break;
        case 3:
          console.log(confirmationfullname);
          pdfUrl.value =
            baseUrl +
            "goodMoral.php?date=" +
            encodeURIComponent(date) + // Encoding the date to ensure it's correctly passed
            "&name=" +
            encodeURIComponent(confirmationfullname) +
            "&priest=" +
            encodeURIComponent(priest) +
            "&service=" +
            encodeURIComponent(Service); // Encoding the fullname
          showPdf.value = true;
          break;
        case 4:
          pdfUrl.value =
            baseUrl +
            "goodMoral.php?date=" +
            encodeURIComponent(date) + // Encoding the date to ensure it's correctly passed
            "&name=" +
            encodeURIComponent(Burialfullname) +
            "&priest=" +
            encodeURIComponent(priest) +
            "&service=" +
            encodeURIComponent(Service); // Encoding the fullname
          showPdf.value = true;
      }
    }
    function deleteRow(row) {
      $q.dialog({
        title: "Confirm",
        message: "Are you sure you want to delete this row?",
        cancel: true,
        persistent: true,
      }).onOk(() => {
        emit("delete", row);
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
    function setStatus(status) {
      console.log("Status:", status);
    }

    function exportTable() {
      const columns = props.columns;
      const rows = props.rowsData;

      // Sum up the amount column
      let totalAmount = 0;
      const amountCol = columns.find(
        (col) => col.name === "amount" || col.field === "amount"
      );

      if (amountCol) {
        totalAmount = rows.reduce((sum, row) => {
          const value =
            typeof amountCol.field === "function"
              ? parseFloat(amountCol.field(row))
              : parseFloat(row[amountCol.field || amountCol.name]);
          return sum + (isNaN(value) ? 0 : value);
        }, 0);
      }

      // Create CSV content
      const content = [columns.map((col) => wrapCsvValue(col.label))]
        .concat(
          rows.map((row) =>
            columns
              .map((col) =>
                wrapCsvValue(
                  typeof col.field === "function"
                    ? col.field(row)
                    : row[col.field === void 0 ? col.name : col.field],
                  col.format,
                  row
                )
              )
              .join(",")
          )
        )
        .concat([
          columns
            .map((col) =>
              col.name === "amount" || col.field === "amount"
                ? wrapCsvValue("Total: " + totalAmount.toFixed(2))
                : ""
            )
            .join(","),
        ])
        .join("\r\n");

      const status = exportFile("Financial_Record.csv", content, "text/csv");

      if (status !== true) {
        $q.notify({
          message: "Browser denied file download...",
          color: "negative",
          icon: "warning",
        });
      }
    }

    return {
      exportTable,
      titles,
      setStatus,
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
      showPdf,
      pdfUrl,
      changeRecord,
      goodMoral,
    };
  },
};
</script>
