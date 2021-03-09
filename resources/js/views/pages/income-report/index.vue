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
                <!-- <th>Super Master</th>
                <th>Master</th> -->
                <!-- <th>Agent</th> -->
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
                <!-- <td>
                  {{ income.brand.category.id === 2 ? 'Super Master' : '' }}
                </td>
                <td>
                  {{ income.brand.category.id === 3 ? 'Master' : '' }}
                </td>
                <td>
                  {{ income.brand.category === 4 ? 'Agent' : '' }}
                </td> -->
                <td>
                  {{ income.brand.category.id === 5 ? 'Api' : '' }}
                </td>
                <td>
                  {{ income.brand.category.id === 6 ? 'White Label' : '' }}
                </td>
                <td>
                  {{
                    income.brand.category.id > 6
                      ? income.brand.category.name
                      : ''
                  }}
                </td>
                <td>{{ income.comments }}</td>
                <td>
                  {{ income.payment_methods.map(item => item.name).join(', ') }}
                </td>
                <td>{{ `${income.brand.currency} ${income.sum}` }}</td>
                <td>{{ Number(income.sum_euro).toFixed(2) }}</td>
                <td>
                  <template v-if="income.received === 'Yes'">
                    Approved
                  </template>
                  <template v-else>
                    <b-dropdown
                      variant="outline-primary"
                      class="m-2"
                      text="Pending"
                    >
                      <b-dropdown-item @click="changeStatus(income.id)"
                        >Approved</b-dropdown-item
                      >
                    </b-dropdown>
                  </template>
                </td>
              </tr>
              <tr v-if="incomeReportList.length > 0">
                <td class="border-none"></td>
                <td class="border-none"></td>
                <td class="border-none"></td>
                <!-- <td class="border-none"></td>
                <td class="border-none"></td>
                <td class="border-none"></td> -->
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
import { USER_TYPE } from '@/constants';
import { getDateRange } from '@/utils/date';
import { calculateCurrency } from '@/utils/currency';
import TopNavbar from '@/sharedComponents/top-navbar.vue';
import DateRangerSelector from '@/sharedComponents/date-range-selector.vue';
import IncomeReportDataFilter from './data-filter.vue';
import Swal from 'sweetalert2';
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
      dateRange: {
        type: 'custom',
        value: 'Today',
        startDate: getDateRange('today').startDate,
        endDate: getDateRange('today').endDate
      },
      filter: {
        brand_id: 'all',
        // supermaster: 'all',
        // master: 'all',
        // agent: 'all',
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
    if (this.currentUser.user_type === USER_TYPE.INSERT) this.$router.push('/');
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
    changeStatus(incomeId) {
      Swal.fire({
        title: 'Are you sure to change approved?',
        showCancelButton: true,
        confirmButtonText: 'Yes'
      }).then(result => {
        if (result.isConfirmed) {
          const loader = this.$loading.show();
          axios
            .post(
              '/api/income-key-in/changestatus',
              { id: incomeId, received: 'Yes' },
              {
                headers: {
                  'Content-Type': 'application/json'
                }
              }
            )
            .then(res => {
              if (res && res.data) {
                Swal.fire({
                  icon: 'success',
                  title: 'Change Status Success'
                });
                this.incomeReportList = this.incomeReportList.map(item => {
                  if (item.id === incomeId) return { ...item, received: 'Yes' };
                  return item;
                });
              } else {
                Swal.fire({
                  icon: 'error',
                  title: 'Change Status Failed'
                });
              }
              loader.hide();
            })
            .catch(err => {
              loader.hide();
              Swal.fire({
                icon: 'error',
                title: 'Change Status Failed'
              });
            });
        }
      });
    },
    async filterIncomeReport() {
      const loader = this.$loading.show();
      try {
        let resList = await axios.post(
          '/api/income-key-in/filter',
          {
            date_range: {
              startDate: new Date(this.dateRange.startDate),
              endDate: new Date(this.dateRange.endDate)
            },
            filter: {
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
          debugger;
          this.incomeReportList = [
            ...resList.data.map(item => {
              return {
                ...item,
                sum_euro: calculateCurrency(
                  { sum: item.sum, currency: item.brand.currency },
                  this.currencyData
                )
              };
            })
          ];
          debugger;
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
