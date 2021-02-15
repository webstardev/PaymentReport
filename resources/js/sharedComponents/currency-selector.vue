<template>
  <b-form @submit="onSubmit">
    <b-row>
      <b-col md="4">
        <a class="btn-prev" @click="gotoPrev">{{ `< Prev` }}</a>
      </b-col>
    </b-row>
    <b-row class="mt-4">
      <b-col md="4">
        <b-form-group label="Currency:" label-for="currency">
          <b-form-select id="currency" v-model="currency" required>
            <option disabled value="" :selected="!currency"
              >Select a currency</option
            >
            <option
              v-for="(option, idx) in Object.keys(currencyOptions)"
              :key="idx"
              :value="currencyOptions[option].code"
            >
              {{ currencyOptions[option].code }}
            </option>
          </b-form-select>
        </b-form-group>
      </b-col>
    </b-row>
    <b-row>
      <b-col md="4">
        <b-button type="submit" variant="primary">Next</b-button>
      </b-col>
    </b-row>
  </b-form>
</template>

<script>
import { CURRENCIES } from '@/constants';
export default {
  name: 'currency-selector',
  props: {
    curCurrency: { type: String, default: '' }
  },
  data() {
    return {
      currencyOptions: CURRENCIES,
      currency: ''
    };
  },
  created() {
    this.currency = this.curCurrency;
  },
  methods: {
    gotoPrev() {
      this.$emit('gotoPrev');
    },
    onSubmit(event) {
      event.preventDefault();
      this.$emit('gotoNext', this.currency);
    }
  }
};
</script>
