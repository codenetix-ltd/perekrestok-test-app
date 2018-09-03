<template>
  <div>
    <vue-good-table
      :columns="columns"
      :rows="rows"
      mode="remote"
      @on-page-change="onPageChange"
      @on-sort-change="onSortChange"
      @on-column-filter="onColumnFilter"
      @on-per-page-change="onPerPageChange"
      :pagination-options="{
        enabled: true,
        mode: 'records',
        perPageDropdown: [5, 10, 15, 50, 100],
      }"
    >
      <div slot="table-actions">
        <form class="form-inline">
          <div class="form-group">
            <label>From</label>
            <input type="datetime-local" class="form-control mx-sm-3" v-on:change="changeFromFilter"/>
          </div>
          <div class="form-group">
            <label>To</label>
            <input type="datetime-local" class="form-control mx-sm-3" v-on:change="changeToFilter"/>
          </div>
          <div class="form-check mx-sm-3">
            <input class="form-check-input" type="checkbox" v-on:change="changeHiddenFilter">
            <label class="form-check-label">
              Show hidden
            </label>
          </div>
        </form>
      </div>
      <template slot="table-row" slot-scope="props">
        <span v-if="props.column.field == 'actions'" class="text-nowrap">
          <!--<button class="btn btn-sm btn-success" v-on:click="showEvent(props.row.id)">-->
            <!--<i class="fa fa-folder-open"></i>-->
          <!--</button>&nbsp;-->
          <button class="btn btn-sm btn-primary" v-on:click="markAsHidden(props.row.id)">
            <i class="fa fa-eye-slash"></i>
          </button>&nbsp;
          <button class="btn btn-sm btn-warning" v-on:click="deleteEvent(props.row.id)">
            <i class="fa fa-trash-o"></i>
          </button>
        </span>
        <span v-else>
          {{props.formattedRow[props.column.field]}}
        </span>
      </template>
    </vue-good-table>
  </div>
</template>

<script>
  import {VueGoodTable} from 'vue-good-table';
  import 'vue-good-table/dist/vue-good-table.css'
  import Vue from 'vue';

  export default {
    name: 'Table',
    components: {
      VueGoodTable,
    },
    data() {
      return {
        columns: [
          {
            label: 'ID',
            field: 'id',
            filterOptions: {
              enabled: true,
              placeholder: 'Id',
              trigger: 'enter',
            }
          },
          {
            label: 'User',
            field: 'user',
            filterOptions: {
              enabled: true,
              placeholder: 'Filter user',
              trigger: 'enter',
            }
          },
          {
            label: 'Message',
            field: 'message',
            filterOptions: {
              enabled: true,
              placeholder: 'Filter message',
              trigger: 'enter',
            },
            sortable: false,
          },
          {
            label: 'Link',
            field: 'link',
            sortable: false,
          },
          {
            label: 'Fired at',
            field: 'fired_at',
            sortable: false,
          },
          {
            label: 'Actions',
            field: 'actions',
            sortable: false,
          }
        ],
        rows: [],
        totalRecords: 0,
        isLoading: false,
        serverParams: {
          columnFilters: {
            'hidden': 0
          },
          sort: {
            field: 'id',
            type: 'desc',
          },
          page: 1,
          perPage: 10
        }
      };
    },
    created: function () {
      this.loadItems();
    },
    methods: {
      changeHiddenFilter(event) {
        this.$set(this.serverParams.columnFilters, 'hidden', event.target.checked ? 1 : 0);
        this.loadItems();
      },
      changeFromFilter(event) {
        this.$set(this.serverParams.columnFilters, 'from', event.target.value);
        this.loadItems();
      },
      changeToFilter(event) {
        this.$set(this.serverParams.columnFilters, 'to', event.target.value);
        this.loadItems();
      },
      showEvent() {
      },
      markAsHidden(id) {
        this.isLoading = true;
        Vue.$http
          .patch('http://37.139.0.86/api/events/' + id, {is_hidden: true})
          .then((response) => {
            this.isLoading = false;
            this.loadItems();
          })
      },
      deleteEvent(id) {
        this.isLoading = true;
        Vue.$http
          .delete('http://37.139.0.86/api/events/' + id)
          .then((response) => {
            this.isLoading = false;
            this.loadItems();
          })
      },
      updateParams(newProps) {
        this.serverParams = Object.assign({}, this.serverParams, newProps);
      },

      onPageChange(params) {
        this.updateParams({page: params.currentPage});
        this.loadItems();
      },

      onPerPageChange(params) {
        this.updateParams({perPage: params.currentPerPage});
        this.loadItems();
      },

      onSortChange(params) {
        this.updateParams({
          sort: {
            type: params.sortType,
            field: this.columns[params.columnIndex].field,
          },
        });
        this.loadItems();
      },

      onColumnFilter(params) {
        this.updateParams(params);
        this.loadItems();
      },

      // load items is what brings back the rows from server
      loadItems() {

        let url = 'http://37.139.0.86/api/events?pagination[perPage]=' + this.serverParams.perPage + '&pagination[page]=' + this.serverParams.page;

        Object.keys(this.serverParams.columnFilters).forEach(key => {
          url += '&filter[' + key + ']=' + this.serverParams.columnFilters[key];
        });

        if (this.serverParams.sort['field']) {
          url += '&sort[' + this.serverParams.sort['field'] + ']=' + this.serverParams.sort.type;
        }

        Vue.$http
          .get(url)
          .then((response) => {
            this.totalRecords = response.data.meta.total;
            this.rows = response.data.data.map((row) => {
              row.user = row.user.name + ' (' + row.user.id + ')';
              return row;
            });
          });
      }
    }
  };
</script>
