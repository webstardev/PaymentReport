<template>
  <b-container fluid class="root-container">
    <top-navbar></top-navbar>
    <b-container fluid="xl" class="main-container ml-auto mr-auto py-4">
      <income-report-date-range-selector
        :date="dateRange"
        @onChange="$event => (dateRange = { ...dateRange, ...$event })"
      ></income-report-date-range-selector>
      <income-report-data-filter
        :filter="filter"
        @onChange="
          $event => {
            filter[$event.keyName] = $event.value;
          }
        "
      ></income-report-data-filter>
    </b-container>
  </b-container>
</template>

<script>
import { getDateRange } from '@/utils/date';
import TopNavbar from '@/sharedComponents/top-navbar.vue';
import IncomeReportDateRangeSelector from './date-range-selector.vue';
import IncomeReportDataFilter from './data-filter.vue';
export default {
  name: 'income-report',
  components: {
    TopNavbar,
    IncomeReportDateRangeSelector,
    IncomeReportDataFilter
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
        brand_id: 'all',
        supermaster: 'all',
        master: 'all',
        agent: 'all',
        api: 'all',
        whitelabel: 'all',
        other: 'all',
        payment_method: 'all',
        status: 'all'
      },
      incomeReportList: []
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
  watch: {
    filter: {
      immediate: true,
      deep: true,
      handler(newValue, oldValue) {
        this.filterIncomeReport();
      }
    },
    dateRange: {
      immediate: true,
      deep: true,
      handler(newValue, oldValue) {
        this.filterIncomeReport();
      }
    }
  },
  methods: {
    async filterIncomeReport() {
      const loader = this.$loading.show();
      try {
        let resList = await axios.post(
          '/api/key-in',
          {
            daterage: {
              startDate: this.dateRange.startDate,
              endDate: this.dateRange.endDate
            },
            filter: { ...filter }
          },
          {
            headers: {
              'Content-Type': 'application/json'
            }
          }
        );
      } catch (err) {
        this.incomeReportList = [];
      }
      loader.hide();
    }
  }
};
</script>

<style scoped lang="scss"></style>
