<template>
  <b-container fluid class="root-container">
    <top-navbar></top-navbar>
    <b-container fluid="xl" class="main-container ml-auto mr-auto py-4">
      <b-form @submit="onSubmit" class="pt-4">
        <b-row class="mb-2" v-if="curStep !== keySteps.SELECT_BRAND">
          <b-col md="4">
            <a class="btn-prev" @click="gotoPrev">{{ `< Prev` }}</a>
          </b-col>
        </b-row>

        <b-row v-if="curStep === keySteps.SELECT_BRAND">
          <b-col md="4">
            <b-form-group label="Brand:" label-for="brand">
              <b-form-select id="brand" v-model="formData.brand_id" required>
                <option disabled :select="!formData.brand_id" value=""
                  >Select a brand</option
                >
                <option
                  v-for="(brand, idx) in brandList"
                  :key="idx"
                  :value="brand.id"
                  >{{ brand.name }}
                </option>
              </b-form-select>
            </b-form-group>
          </b-col>
        </b-row>

        <b-row v-if="curStep === keySteps.SELECT_DATE">
          <b-col md="4">
            <b-form-group label="Date" label-for="date-picker">
              <b-form-datepicker
                id="date-picker"
                v-model="formData.date"
                required
              ></b-form-datepicker>
            </b-form-group>
          </b-col>
        </b-row>

        <b-row v-if="curStep === keySteps.SELECT_CURRENCY">
          <b-col md="4">
            <b-form-group label="Currency:" label-for="currency">
              <b-form-select id="country" v-model="formData.currency" required>
                <option disabled :selected="!formData.currency" value=""
                  >Select a currency</option
                >
                <option
                  v-for="(option, idx) in currencyOptions"
                  :key="idx"
                  :value="option"
                >
                  {{ option }}
                </option>
              </b-form-select>
            </b-form-group>
          </b-col>
        </b-row>
        <b-row v-if="curStep === keySteps.SELECT_COUNTRY">
          <b-col md="4">
            <b-form-group label="Counry:" label-for="country">
              <b-form-select id="country" v-model="formData.country" required>
                <option disabled :selected="!formData.country" value=""
                  >Select a country</option
                >
                <option
                  v-for="(option, idx) in countryOptions"
                  :key="idx"
                  :value="option.name"
                >
                  {{ option.name }}
                </option>
              </b-form-select>
            </b-form-group>
          </b-col>
        </b-row>

        <b-row v-if="curStep === keySteps.SELECT_EXPENSES_TYPE">
          <b-col md="4">
            <b-form-group label="Expenses type:" label-for="expenses-type">
              <b-form-select v-model="formData.expenses_type_id" required>
                <option disabled :selected="!formData.expenses_type_id" value=""
                  >Select a expenses type</option
                >
                <option
                  v-for="expensesType in expensesTypeList"
                  :value="expensesType.id"
                  :key="expensesType.id"
                >
                  {{ expensesType.name }}
                </option>
              </b-form-select>
            </b-form-group>
          </b-col>
        </b-row>
        <b-row v-if="curStep === keySteps.SELECT_SUM">
          <b-col md="4">
            <b-form-group label="Sum" label-for="sum">
              <b-form-input
                id="sum"
                v-model="formData.sum"
                type="number"
                step="0.0001"
                min="0"
                required
              ></b-form-input>
            </b-form-group>
          </b-col>
        </b-row>

        <b-row v-if="curStep === keySteps.SELECT_PAYMENT_METHOD">
          <b-col md="4">
            <b-form-group label="Payment method:" label-for="payment-method">
              <multiselect
                v-model="formData.payment_method"
                tag-placeholder="Add this as new payment method"
                placeholder="Search or add a payment method"
                label="name"
                track-by="id"
                :options="paymentMethodList"
                :multiple="true"
                required
              ></multiselect>
            </b-form-group>
          </b-col>
        </b-row>
        <b-row v-if="curStep === keySteps.SELECT_COMMENTS">
          <b-col md="6">
            <b-form-group label="Comments:" label-for="comments">
              <b-form-textarea
                id="comments"
                v-model="formData.comments"
                placeholder="Enter something..."
                rows="3"
                max-rows="6"
                required
              ></b-form-textarea>
            </b-form-group>
          </b-col>
        </b-row>
        <b-row class="mt-2">
          <b-col>
            <b-button type="submit" variant="primary">{{ buttonStr }}</b-button>
          </b-col>
        </b-row>
      </b-form>
    </b-container>
  </b-container>
</template>

<script>
import Swal from 'sweetalert2';
import { KEY_IN_STEPS, CURRENCIES, COUNTRIES } from '@/constants';
import TopNavbar from '@/sharedComponents/top-navbar.vue';
import { getBrand, getPaymentMethod, getExpensesType } from '@/services/apis';

export default {
  name: 'expenses-key-in',
  components: {
    TopNavbar
  },
  data() {
    return {
      keySteps: KEY_IN_STEPS,
      curStep: KEY_IN_STEPS.SELECT_BRAND,
      currencyOptions: Object.keys(CURRENCIES),
      countryOptions: COUNTRIES,
      brandList: [],
      paymentMethodList: [],
      expensesTypeList: [],
      formData: {
        brand_id: '',
        date: null,
        sum: 0,
        payment_method: [],
        comments: '',
        currency: '',
        country: '',
        expenses_type_id: ''
      }
    };
  },
  async created() {
    const loader = this.$loading.show();
    try {
      this.brandList = await getBrand();
      this.paymentMethodList = await getPaymentMethod();
      this.expensesTypeList = await getExpensesType();
    } catch (err) {
      loader.hide();
    }
    loader.hide();
  },
  computed: {
    buttonStr: function() {
      if (this.curStep === KEY_IN_STEPS.SELECT_COMMENTS) return 'Submit';
      return 'Next';
    }
  },
  methods: {
    gotoPrev() {
      switch (this.curStep) {
        case KEY_IN_STEPS.SELECT_DATE:
          this.curStep = KEY_IN_STEPS.SELECT_BRAND;
          break;
        case KEY_IN_STEPS.SELECT_CURRENCY:
          this.curStep = KEY_IN_STEPS.SELECT_DATE;
          break;
        case KEY_IN_STEPS.SELECT_COUNTRY:
          this.curStep = KEY_IN_STEPS.SELECT_CURRENCY;
          break;
        case KEY_IN_STEPS.SELECT_EXPENSES_TYPE:
          this.curStep = KEY_IN_STEPS.SELECT_COUNTRY;
          break;
        case KEY_IN_STEPS.SELECT_SUM:
          this.curStep = KEY_IN_STEPS.SELECT_COUNTRY;
          break;
        case KEY_IN_STEPS.SELECT_PAYMENT_METHOD:
          this.curStep = KEY_IN_STEPS.SELECT_SUM;
          break;
        case KEY_IN_STEPS.SELECT_COMMENTS:
          this.curStep = KEY_IN_STEPS.SELECT_PAYMENT_METHOD;
          break;
        default:
          this.curStep = KEY_IN_STEPS.SELECT_BRAND;
          break;
      }
    },
    async onSubmit(event) {
      event.preventDefault();

      if (this.curStep === KEY_IN_STEPS.SELECT_COMMENTS) {
        const loader = this.$loading.show();
        let keyInFormData = {
          brand_id: this.formData.brand_id,
          date: this.formData.date,
          currency: this.formData.currency,
          country: this.formData.country,
          expenses_type_id: this.formData.expenses_type_id,
          sum: this.formData.sum,
          payment_method: this.formData.payment_method.map(x => x.id),
          comments: this.formData.comments
        };
        try {
          let res = await axios.post('/api/expenses-key-in', keyInFormData, {
            headers: {
              'Content-Type': 'application/json'
            }
          });
          if (res && res.data) {
            if (res.status === 201) {
              Swal.fire({
                title: 'Expenses Add Success.',
                icon: 'success'
              }).then(result => {
                this.formData = {
                  brand_id: null,
                  date: null,
                  sum: 0,
                  payment_method: [],
                  comments: '',
                  currency: '',
                  country: '',
                  expenses_type_id: ''
                };
              });
              this.curStep = KEY_IN_STEPS.SELECT_BRAND;
            } else {
              Swal.fire({
                title: 'Expenses Add Failed.',
                icon: 'warning'
              });
            }
          }
        } catch (err) {
          Swal.fire({
            title: 'Expenses Add Failed.',
            icon: 'error'
          });
        }

        loader.hide();
      } else {
        switch (this.curStep) {
          case KEY_IN_STEPS.SELECT_BRAND:
            this.curStep = KEY_IN_STEPS.SELECT_DATE;
            break;
          case KEY_IN_STEPS.SELECT_DATE:
            this.curStep = KEY_IN_STEPS.SELECT_CURRENCY;
            break;
          case KEY_IN_STEPS.SELECT_SUM:
            this.curStep = KEY_IN_STEPS.SELECT_PAYMENT_METHOD;
            break;
          case KEY_IN_STEPS.SELECT_PAYMENT_METHOD:
            this.curStep = KEY_IN_STEPS.SELECT_COMMENTS;
            break;
          case KEY_IN_STEPS.SELECT_CURRENCY:
            this.curStep = KEY_IN_STEPS.SELECT_COUNTRY;
            break;
          case KEY_IN_STEPS.SELECT_COUNTRY:
            this.curStep = KEY_IN_STEPS.SELECT_EXPENSES_TYPE;
            break;
          case KEY_IN_STEPS.SELECT_EXPENSES_TYPE:
            this.curStep = KEY_IN_STEPS.SELECT_SUM;
            break;
          default:
            this.curStep = KEY_IN_STEPS.SELECT_BRAND;
            break;
        }
      }
    }
  }
};
</script>

<style scoped>
.btn-prev {
  cursor: pointer;
}
</style>
