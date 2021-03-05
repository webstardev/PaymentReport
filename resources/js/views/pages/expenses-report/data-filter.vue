<template>
  <b-row class="expenses-data-filter-container">
    <b-col class="expenses-data-filter-col mt-3" md="12">
      <b-form-group
        class="dropdown-container mr-3"
        label="Supplier:"
        labe-for="filter-supplier"
      >
        <b-form-select
          id="filter-supplier"
          :value="filter.supplier_id"
          @change="$event => changeFilter('supplier_id', $event)"
        >
          <option value="all" key="all">All</option>
          <option
            v-for="supplier in supplierList"
            :key="supplier.id"
            :value="supplier.id"
          >
            {{ supplier.name }}
          </option>
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
    </b-col>
  </b-row>
</template>

<script>
import {
  getSupplier,
  getPaymentMethod,
  getExpensesType
} from '@/services/apis';

export default {
  name: 'expenses-report-data-filter',
  props: {
    filter: {
      type: Object,
      default: function() {
        return {
          supplier_id: 'all',
          payment_method: 'all'
        };
      }
    }
  },
  data() {
    return {
      supplierList: [],
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
    const loader = this.$loading.show();
    try {
      this.supplierList = await getSupplier();
      this.paymentMethodList = await getPaymentMethod();
      this.expensesList = await getExpensesType();
    } catch (err) {
      loader.hide();
    }
    loader.hide();
  }
};
</script>

<style scoped lang="scss">
.expenses-data-filter-col {
  display: flex;
  justify-content: flex-start;
}
</style>
