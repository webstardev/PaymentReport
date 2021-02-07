<template>
  <b-container fluid class="root-container">
    <top-navbar></top-navbar>
    <b-container fluid class="main-container ml-auto mr-auto py-4">
      <expenses-report-date-range-selector
        :date="dateRange"
        @onChange="changeDateRange"
      ></expenses-report-date-range-selector>
      <expenses-report-data-filter
        :filter="filter"
        @onChange="changeFilter"
      ></expenses-report-data-filter>
      <b-col md="12">
        <table class="report-table">
          <thead>
            <tr>
              <th>Date/Time</th>
              <th>User</th>
              <th>Brand</th>
              <th>Super Master</th>
              <th>Master</th>
              <th>Agent</th>
              <th>API</th>
              <th>White Label</th>
              <th>Other</th>
              <th>Expenses Type</th>
              <th>Payment method</th>
              <th>Sum(in Original currency)</th>
              <th>In Europe</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="expenses in expensesReportList" :key="expenses.id">
              <td>{{ expenses.date | moment('MM-DD-YYYY') }}</td>
              <td>{{ expenses.user.username }}</td>
              <td>{{ expenses.brand.name }}</td>
              <td>
                {{
                  expenses.brand.category.type === category.AGENT_SYSTEM
                    ? expenses.brand.category.content.supermaster
                    : ''
                }}
              </td>
              <td>
                {{
                  expenses.brand.category.type === category.AGENT_SYSTEM
                    ? expenses.brand.category.content.master
                    : ''
                }}
              </td>
              <td>
                {{
                  expenses.brand.category.type === category.AGENT_SYSTEM
                    ? expenses.brand.category.content.agent
                    : ''
                }}
              </td>
              <td>
                {{
                  expenses.brand.category.type === category.API
                    ? expenses.brand.category.content.name
                    : ''
                }}
              </td>
              <td>
                {{
                  expenses.brand.category.type === category.WHITELABEL
                    ? expenses.brand.category.content.name
                    : ''
                }}
              </td>
              <td>
                {{
                  expenses.brand.category.type === category.OTHER
                    ? expenses.brand.category.content.name
                    : ''
                }}
              </td>
              <td>{{ expenses.expenses_type }}</td>
              <td>
                {{ expenses.payment_method.map(item => item.name).join(', ') }}
              </td>
              <td>{{ expenses.sum }}</td>
              <td>{{ expenses.sum }}</td>
              <td>
                {{ expenses.received === 'Yes' ? 'Approved' : 'Pending' }}
              </td>
            </tr>
          </tbody>
        </table>
      </b-col>
    </b-container>
  </b-container>
</template>

<script>
import { CATEGORY, KEY_IN_TYPE } from '@/constants';
import { getDateRange } from '@/utils/date';
import TopNavbar from '@/sharedComponents/top-navbar.vue';
import ExpensesReportDateRangeSelector from './date-range-selector.vue';
import ExpensesReportDataFilter from './data-filter.vue';
export default {
  name: 'Expenses-report',
  components: {
    TopNavbar,
    ExpensesReportDateRangeSelector,
    ExpensesReportDataFilter
  },
  data() {
    return {
      category: CATEGORY,
      dateRange: {
        type: 'custom',
        value: 'Today',
        startDate: getDateRange('today').startDate,
        endDate: getDateRange('today').endDate
      },
      filter: {
        brand_id: 'all',
        supermaster: 'all',
        master: 'all',
        agent: 'all',
        api: 'all',
        whitelabel: 'all',
        other: 'all',
        expenses_type: 'all',
        payment_method: 'all',
        status: 'all'
      },
      expensesReportList: []
    };
  },
  async created() {
    //   get brand
    const loader = this.$loading.show();
    try {
      let resBrand = await axios.get('/api/brand/all');
      if (resBrand && resBrand.status === 200 && resBrand.data) {
        this.brandList = resBrand.data;
      }
    } catch (err) {
      this.brandList = [];
    }
    loader.hide();
  },
  created() {
    this.filterReport();
  },
  methods: {
    changeDateRange(newValue) {
      this.dateRange = { ...this.dateRange, ...newValue };
      this.filterReport();
    },
    changeFilter(newValue) {
      debugger;
      this.filter[newValue.keyName] = newValue.value;
      this.filterReport();
    },
    async filterReport() {
      const loader = this.$loading.show();
      try {
        let resList = await axios.post(
          '/api/keyin/filter',
          {
            date_range: {
              startDate: new Date(this.dateRange.startDate),
              endDate: new Date(this.dateRange.endDate)
            },
            filter: {
              type: KEY_IN_TYPE.EXPENSES,
              ...this.filter
            }
          },
          {
            headers: {
              'Content-Type': 'application/json'
            }
          }
        );
        if (resList && resList.status === 200) {
          this.expensesReportList = [
            ...resList.data.map(item => {
              return {
                ...item,
                payment_method: JSON.parse(item.payment_method)
              };
            })
          ];
        }
      } catch (err) {
        this.expensesReportList = [];
      }
      loader.hide();
    }
  }
};
</script>

<style scoped lang="scss">
.report-table {
  width: 100%;
}
</style>
