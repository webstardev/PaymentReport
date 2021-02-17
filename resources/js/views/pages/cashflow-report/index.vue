<template>
  <b-container fluid class="root-container">
    <top-navbar></top-navbar>
    <b-container fluid class="main-container ml-auto mr-auto py-4">
      <date-ranger-selector
        :date="dateRange"
        @onChange="changeDateRange"
      ></date-ranger-selector>
      <b-row class="mt-3">
        <b-col md="2" sm="3" xs="6">
          <b-form-group
            class="dropdown-container"
            label="Status:"
            labe-for="filter-status"
          >
            <b-form-select
              id="filter-status"
              :value="filter.status"
              @change="$event => (filter.status = $event)"
            >
              <option value="all" key="all">All</option>
              <option value="Yes">Approved</option>
              <option value="No">Pending</option>
            </b-form-select>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row class="mt-4">
        <b-col md="12">
          <h5 class="mb-3">Cashflow Report</h5>
          <h6 class="in-euro-title">In Euro</h6>
          <table class="cashflow-table">
            <thead>
              <tr>
                <th>Total Income</th>
                <th>Total Expenses</th>
                <th>Total Balance</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
            </tbody>
          </table>
        </b-col>
      </b-row>
    </b-container>
  </b-container>
</template>

<script>
import { getDateRange } from '@/utils/date';
import TopNavbar from '@/sharedComponents/top-navbar.vue';
import DateRangerSelector from '@/sharedComponents/date-range-selector.vue';

export default {
  name: 'cashflow-report',
  components: {
    TopNavbar,
    DateRangerSelector
  },
  data() {
    return {
      dateRange: {
        type: 'custom',
        value: 'Today',
        startDate: getDateRange('today').startDate,
        endDate: getDateRange('today').endDate
      },
      filter: {
        status: 'all'
      }
    };
  },
  methods: {
    changeDateRange(newValue) {
      this.dateRange = { ...this.dateRange, ...newValue };
    }
  }
};
</script>

<style scoped lang="scss">
.in-euro-title {
  text-decoration: underline;
}

.cashflow-table {
  max-width: 480px;
  width: 100%;
  td {
    border: 1px solid grey;
  }
}
</style>
