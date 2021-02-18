<template>
  <b-container fluid class="root-container">
    <top-navbar></top-navbar>
    <b-container fluid class="main-container ml-auto mr-auto py-4">
      <date-ranger-selector
        :date="dateRange"
        @onChange="changeDateRange"
      ></date-ranger-selector>
      <income-report-data-filter
        :filter="filter"
        @onChange="changeFilter"
      ></income-report-data-filter>
      <b-row class="mt-4">
        <b-col md="12">
          <table class="income-report-table">
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
                <th>Description</th>
                <th>Payment method</th>
                <th>Sum(in Original currency)</th>
                <th>In Euro</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="income in incomeReportList" :key="income.id">
                <td>{{ income.date | moment('MM-DD-YYYY') }}</td>
                <td>{{ income.user.username }}</td>
                <td>{{ income.brand.name }}</td>
                <td>
                  {{
                    income.brand.category.type ===
                      category.AGENT_SYSTEM.value &&
                    income.brand.category.agent_system ===
                      agentySystem.SUPERMASTER.value
                      ? income.brand.category.name
                      : ''
                  }}
                </td>
                <td>
                  {{
                    income.brand.category.type ===
                      category.AGENT_SYSTEM.value &&
                    income.brand.category.agent_system ===
                      agentySystem.MASTER.value
                      ? income.brand.category.name
                      : ''
                  }}
                </td>
                <td>
                  {{
                    income.brand.category.type ===
                      category.AGENT_SYSTEM.value &&
                    income.brand.category.agent_system ===
                      agentySystem.AGENT.value
                      ? income.brand.category.name
                      : ''
                  }}
                </td>
                <td>
                  {{
                    income.brand.category.type === category.API.value
                      ? income.brand.category.name
                      : ''
                  }}
                </td>
                <td>
                  {{
                    income.brand.category.type === category.WHITE_LABEL.value
                      ? income.brand.category.name
                      : ''
                  }}
                </td>
                <td>
                  {{
                    income.brand.category.type === category.OTHER.value
                      ? income.brand.category.name
                      : ''
                  }}
                </td>
                <td>{{ income.comments }}</td>
                <td>
                  {{ income.payment_method.map(item => item.name).join(', ') }}
                </td>
                <td>{{ `${income.brand.currency} ${income.sum}` }}</td>
                <td>{{ Number(income.sum_euro).toFixed(2) }}</td>
                <td>
                  {{ income.received === 'Yes' ? 'Approved' : 'Pending' }}
                </td>
              </tr>
              <tr v-if="incomeReportList.length > 0">
                <td class="border-none"></td>
                <td class="border-none"></td>
                <td class="border-none"></td>
                <td class="border-none"></td>
                <td class="border-none"></td>
                <td class="border-none"></td>
                <td class="border-none"></td>
                <td class="border-none"></td>
                <td class="border-none"></td>
                <td class="border-none"></td>
                <td class="border-none"></td>
                <td align="right">
                  Total:
                </td>
                <td>
                  {{
                    Number(
                      incomeReportList.reduce((sum_euro, current) => {
                        return sum_euro + current.sum_euro;
                      }, 0)
                    ).toFixed(2)
                  }}
                </td>
                <td class="border-none"></td>
              </tr>
            </tbody>
          </table>
        </b-col>
      </b-row>
    </b-container>
  </b-container>
</template>

<script>
import { mapGetters } from 'vuex';
import { CATEGORY, KEY_IN_TYPE, USER_TYPE, AGENT_SYSTEM } from '@/constants';
import { getDateRange } from '@/utils/date';
import { calculateCurrency } from '@/utils/currency';
import TopNavbar from '@/sharedComponents/top-navbar.vue';
import DateRangerSelector from '@/sharedComponents/date-range-selector.vue';
import IncomeReportDataFilter from './data-filter.vue';
export default {
  name: 'income-report',
  components: {
    TopNavbar,
    DateRangerSelector,
    IncomeReportDataFilter
  },
  computed: {
    ...mapGetters(['currentUser', 'check'])
  },

  data() {
    return {
      currencyData: {},
      category: CATEGORY,
      agentySystem: AGENT_SYSTEM,
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
    if (this.currentUser.user_type !== USER_TYPE.VIEW) this.$router.push('/');
    const loader = this.$loading.show();

    // get currency
    try {
      const resCurrency = await axios.get('/api/currency');
      if (resCurrency) {
        this.currencyData = JSON.parse(resCurrency.data.currency);
      }
    } catch (err) {
      this.currencyData = {};
    }

    //   get brand
    try {
      let resBrand = await axios.get('/api/brand/all');
      if (resBrand && resBrand.status === 200 && resBrand.data) {
        this.brandList = resBrand.data;
      }
    } catch (err) {
      this.brandList = [];
    }
    this.filterIncomeReport();

    loader.hide();
  },
  methods: {
    changeDateRange(newValue) {
      this.dateRange = { ...this.dateRange, ...newValue };
      this.filterIncomeReport();
    },
    changeFilter(newValue) {
      this.filter[newValue.keyName] = newValue.value;
      this.filterIncomeReport();
    },
    async filterIncomeReport() {
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
              type: KEY_IN_TYPE.INCOME,
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
          this.incomeReportList = [
            ...resList.data.map(item => {
              return {
                ...item,
                payment_method: JSON.parse(item.payment_method),
                sum_euro: calculateCurrency(
                  { sum: item.sum, currency: item.brand.currency },
                  this.currencyData
                )
              };
            })
          ];
        }
      } catch (err) {
        this.incomeReportList = [];
      }
      loader.hide();
    }
  }
};
</script>

<style scoped lang="scss">
.income-report-table {
  width: 100%;
  td {
    border: 1px solid grey;
  }
}
</style>
