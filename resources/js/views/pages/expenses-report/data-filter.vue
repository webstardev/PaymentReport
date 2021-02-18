<template>
  <b-row class="expenses-data-filter-container">
    <b-col class="expenses-data-filter-col mt-3" md="12">
      <b-form-group
        class="dropdown-container mr-3"
        label="Brand:"
        labe-for="filter-brand"
      >
        <b-form-select
          id="filter-brand"
          :value="filter.brand_id"
          @change="$event => changeFilter('brand_id', $event)"
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
        label="Expenses Type:"
        labe-for="filter-expenses-type"
      >
        <b-form-select
          id="filter-expenses-type"
          :value="filter.expenses_type"
          @change="$event => changeFilter('expenses_type', $event)"
        >
          <option value="all" key="all">All</option>
          <option
            v-for="option in expensesList"
            :key="option.id"
            :value="option.id"
          >
            {{ option.name }}
          </option>
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
            v-for="payment_method in paymentMethodList"
            :key="payment_method.id"
            :value="payment_method.id"
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
          <option value="Yes">Approved</option>
          <option value="No">Pending</option>
        </b-form-select>
      </b-form-group>
    </b-col>
  </b-row>
</template>

<script>
import { getBrand, getPaymentMethod, getExpensesType } from '@/services/apis';

export default {
  name: 'expenses-report-data-filter',
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
      brandList: [],
      paymentMethodList: [],
      expensesList: []
    };
  },
  methods: {
    changeFilter(keyName, value) {
      this.$emit('onChange', { keyName, value });
    }
  },
  async created() {
    this.brandList = await getBrand();
    this.paymentMethodList = await getPaymentMethod();
    this.expensesList = await getExpensesType();
  }
};
</script>

<style scoped lang="scss">
.expenses-data-filter-col {
  display: flex;
  justify-content: flex-start;
}
</style>
