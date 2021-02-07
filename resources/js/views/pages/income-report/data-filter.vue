<template>
  <b-row class="income-data-filter-container">
    <b-col class="income-data-filter-col mt-3" md="12">
      <b-form-group
        class="dropdown-container mr-3"
        label="Brand:"
        labe-for="filter-brand"
      >
        <b-form-select
          id="filter-brand"
          :value="filter.brand_id"
          @change="$event => changeFilter('brand', $event)"
        >
          <option value="all" key="all">All</option>
          <option v-for="brand in brandList" :key="brand.id" :value="brand.id">
            {{ brand.name }}
          </option>
        </b-form-select>
      </b-form-group>
      <b-form-group
        class="dropdown-container mr-3"
        label="Super Master:"
        labe-for="filter-supermaster"
      >
        <b-form-select
          id="filter-supermaster"
          :value="filter.supermaster"
          @change="$event => changeFilter('supermaster', $event)"
        >
          <option value="all" key="all">All</option>
        </b-form-select>
      </b-form-group>
      <b-form-group
        class="dropdown-container mr-3"
        label="Master:"
        labe-for="filter-master"
      >
        <b-form-select
          id="filter-master"
          :value="filter.master"
          @change="$event => changeFilter('master', $event)"
        >
          <option value="all" key="all">All</option>
        </b-form-select>
      </b-form-group>
      <b-form-group
        class="dropdown-container mr-3"
        label="Agent:"
        labe-for="filter-agent"
      >
        <b-form-select
          id="filter-agent"
          :value="filter.agent"
          @change="$event => changeFilter('agent', $event)"
        >
          <option value="all" key="all">All</option>
        </b-form-select>
      </b-form-group>
      <b-form-group
        class="dropdown-container mr-3"
        label="Api:"
        labe-for="filter-api"
      >
        <b-form-select
          id="filter-api"
          :value="filter.api"
          @change="$event => changeFilter('api', $event)"
        >
          <option value="all" key="all">All</option>
        </b-form-select>
      </b-form-group>
      <b-form-group
        class="dropdown-container mr-3"
        label="White Label:"
        labe-for="filter-whitelabel"
      >
        <b-form-select
          id="filter-whitelabel"
          :value="filter.whitelabel"
          @change="$event => changeFilter('whitelabel', $event)"
        >
          <option value="all" key="all">All</option>
        </b-form-select>
      </b-form-group>
      <b-form-group
        class="dropdown-container mr-3"
        label="Other:"
        labe-for="filter-other"
      >
        <b-form-select
          id="filter-other"
          :value="filter.other"
          @change="$event => changeFilter('other', $event)"
        >
          <option value="all" key="all">All</option>
        </b-form-select>
      </b-form-group>
      <b-form-group
        class="dropdown-container mr-3"
        label="Payment Method:"
        labe-for="filter-payment-method"
      >
        <b-form-select
          id="filter-payment-method"
          :value="filter.payment_method"
          @change="$event => changeFilter('payment_method', $event)"
        >
          <option value="all" key="all">All</option>
          <option
            v-for="payment_method in paymentOptions"
            :key="payment_method.code"
            :value="payment_method.code"
          >
            {{ payment_method.name }}
          </option>
        </b-form-select>
      </b-form-group>
      <b-form-group
        class="dropdown-container"
        label="Status:"
        labe-for="filter-status"
      >
        <b-form-select
          id="filter-status"
          :value="filter.status"
          @change="$event => changeFilter('status', $event)"
        >
          <option value="all" key="all">All</option>
          <option value="approved">Approved</option>
          <option value="pending">Pending</option>
        </b-form-select>
      </b-form-group>
    </b-col>
  </b-row>
</template>

<script>
import {
  KEY_IN_TYPE,
  PAYMENT_METHOD,
  RECEIVED_STATUS,
  CURRENCIES,
  COUNTRIES,
  EXPENSES_TYPE
} from '@/constants';

export default {
  name: 'income-report-data-filter',
  props: {
    filter: {
      type: Object,
      default: function() {
        return {
          brand_id: 'all',
          supermaster: 'all',
          master: 'all',
          agent: 'all',
          api: 'all',
          whitelabel: 'all',
          other: 'all',
          payment_method: 'all',
          status: 'all'
        };
      }
    }
  },
  data() {
    return {
      paymentOptions: PAYMENT_METHOD.map(item => {
        return { name: item, code: item };
      }),
      brandList: []
    };
  },
  methods: {
    changeFilter(keyName, value) {
      this.$emit('onChange', { keyName, value });
    }
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
  }
};
</script>

<style scoped lang="scss">
.income-data-filter-col {
  display: flex;
  justify-content: space-between;
}
</style>
