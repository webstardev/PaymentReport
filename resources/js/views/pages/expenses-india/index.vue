<template>
  <b-container fluid class="root-container">
    <top-navbar></top-navbar>
    <b-container fluid class="main-container ml-auto mr-auto py-4">
      <date-ranger-selector
        :date="dateRange"
        @onChange="changeDateRange"
      ></date-ranger-selector>
      <b-row class="mt-4">
        <b-col md="12">
          <table class="report-table">
            <thead>
              <tr>
                <th>Date/Time</th>
                <th>User</th>
                <th>Description</th>
                <th>Currency</th>
                <th>In Euro</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="expenses in reportList" :key="expenses.id">
                <td>{{ expenses.date | moment('MM-DD-YYYY') }}</td>
                <td>{{ expenses.user.username }}</td>
                <td>{{ expenses.comments }}</td>
                <td>{{ `${expenses.currency} ${expenses.sum}` }}</td>
                <td>{{ Number(expenses.sum_euro).toFixed(2) }}</td>
              </tr>
              <tr v-if="reportList.length > 0">
                <td class="border-none"></td>
                <td class="border-none"></td>
                <td class="border-none"></td>
                <td>
                  Total:
                </td>
                <td>
                  {{
                    Number(
                      reportList.reduce((sum_euro, current) => {
                        return sum_euro + current.sum_euro;
                      }, 0)
                    ).toFixed(2)
                  }}
                </td>
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
import { CATEGORY, KEY_IN_TYPE, USER_TYPE } from '@/constants';
import { getBrand } from '@/services/apis';
import { getDateRange } from '@/utils/date';
import { calculateCurrency } from '@/utils/currency';
import TopNavbar from '@/sharedComponents/top-navbar.vue';
import DateRangerSelector from '@/sharedComponents/date-range-selector.vue';
export default {
  name: 'expenses-india',
  components: {
    TopNavbar,
    DateRangerSelector
  },
  computed: {
    ...mapGetters(['currentUser', 'check'])
  },
  data() {
    return {
      currencyData: {},
      category: CATEGORY,
      dateRange: {
        type: 'custom',
        value: 'Today',
        startDate: getDateRange('today').startDate,
        endDate: getDateRange('today').endDate
      },
      reportList: []
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

    // get brand
    try {
      this.brandList = await getBrand();
    } catch (err) {
      this.brandList = [];
      loader.hide();
    }
    loader.hide();
    this.filterReport();
  },

  methods: {
    changeDateRange(newValue) {
      this.dateRange = { ...this.dateRange, ...newValue };
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
              country: 'India'
            }
          },
          {
            headers: {
              'Content-Type': 'application/json'
            }
          }
        );
        if (resList && resList.status === 200) {
          this.reportList = [
            ...resList.data.map(item => {
              return {
                ...item,
                payment_method: JSON.parse(item.payment_method),
                sum_euro: calculateCurrency(
                  { sum: item.sum, currency: item.currency },
                  this.currencyData
                )
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
  td {
    border: 1px solid grey;
  }
}
</style>
