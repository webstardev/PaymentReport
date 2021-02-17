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
                <td>{{ expenses.sum }}</td>
                <td>{{ expenses.sum }}</td>
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
                    reportList.reduce((sum, current) => {
                      return sum + current.sum;
                    }, 0)
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
import { getDateRange } from '@/utils/date';
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
    this.filterReport();

    loader.hide();
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
  td {
    border: 1px solid grey;
  }
}
</style>
