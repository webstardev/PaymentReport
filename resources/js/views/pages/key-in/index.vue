<template>
  <b-container fluid class="root-container">
    <top-navbar></top-navbar>
    <b-container fluid="xl" class="main-container ml-auto mr-auto py-4">
      <b-form @submit="onSubmit" class="pt-4">
        <b-row v-if="curStep === keySteps.CREATE_KEY_IN">
          <b-col md="6">
            <h3>Create Key In</h3>
          </b-col>
        </b-row>

        <b-row
          v-if="
            curStep !== keySteps.CREATE_KEY_IN &&
              curStep !== keySteps.SELECT_KEY_IN_TYPE
          "
        >
          <b-col md="4">
            <a class="btn-prev" @click="gotoPrev">{{ `< Prev` }}</a>
          </b-col>
        </b-row>

        <b-row v-if="curStep === keySteps.SELECT_KEY_IN_TYPE">
          <b-col md="4">
            <b-form-group label="Key in type:" label-for="keyin-type">
              <b-form-select
                id="key-in-type"
                v-model="formData.key_in_type"
                rqeuired
              >
                <option
                  v-for="(option, idx) in Object.keys(keyInType)"
                  :key="idx"
                  :value="keyInType[option]"
                >
                  {{ keyInType[option] }}
                </option>
              </b-form-select>
            </b-form-group>
          </b-col>
        </b-row>

        <b-row v-if="curStep === keySteps.SELECT_BRAND">
          <b-col md="4">
            <b-form-group label="Brand:" label-for="brand">
              <b-form-select id="brand" v-model="formData.brand_id" rqeuired>
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
              <multiselect
                v-model="formData.expenses_type"
                tag-placeholder="Add this as new expenses type"
                placeholder="Search or add a expenses type"
                :options="expensesType"
                :multiple="false"
                :taggable="true"
                @tag="addExpensesType"
                required
              ></multiselect>
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
                track-by="code"
                :options="paymentOptions"
                :multiple="true"
                :taggable="true"
                @tag="addPaymentMethod"
                required
              ></multiselect>
            </b-form-group>
          </b-col>
        </b-row>
        <b-row v-if="curStep === keySteps.SELECT_RECEIVED">
          <b-col md="4">
            <b-form-group label="Received" label-for="received">
              <b-form-select id="received" v-model="formData.received" rqeuired>
                <option
                  v-for="(option, idx) in Object.keys(receivedStatus)"
                  :key="idx"
                  :value="receivedStatus[option]"
                  >{{ receivedStatus[option] }}
                </option>
              </b-form-select>
            </b-form-group>
          </b-col>
        </b-row>
        <b-row v-if="curStep === keySteps.SELECT_COMMENTS">
          <b-col md="12">
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

import {
  KEY_IN_STEPS,
  KEY_IN_TYPE,
  PAYMENT_METHOD,
  RECEIVED_STATUS,
  CURRENCIES,
  COUNTRIES,
  EXPENSES_TYPE
} from '@/constants';
import TopNavbar from '@/sharedComponents/top-navbar.vue';

export default {
  name: 'key-in',
  components: {
    TopNavbar
  },
  data() {
    return {
      keySteps: KEY_IN_STEPS,
      keyInType: KEY_IN_TYPE,
      curStep: KEY_IN_STEPS.CREATE_KEY_IN,
      paymentOptions: PAYMENT_METHOD.map(item => {
        return { name: item, code: item };
      }),
      receivedStatus: RECEIVED_STATUS,
      currencyOptions: Object.keys(CURRENCIES),
      countryOptions: COUNTRIES,
      expensesType: EXPENSES_TYPE,
      brandList: [],
      formData: {
        key_in_type: KEY_IN_TYPE.INCOME,
        brand_id: null,
        date: null,
        sum: 0,
        payment_method: [],
        received: RECEIVED_STATUS.YES,
        comments: '',
        // for expenses
        currency: '',
        country: '',
        expenses_type: ''
      }
    };
  },
  created() {
    const loader = this.$loading.show();

    axios
      .get('/api/brand/all')
      .then(res => {
        loader.hide();
        if (res && res.data) {
          this.brandList = res.data;
        }
      })
      .catch(err => {
        loader.hide();
        this.brandList = [];
      });
  },
  computed: {
    buttonStr: function() {
      if (this.curStep === KEY_IN_STEPS.CREATE_KEY_IN) return 'Create Key In';
      else if (this.curStep === KEY_IN_STEPS.SELECT_COMMENTS) return 'Submit';
      return 'Next';
    }
  },
  methods: {
    addPaymentMethod(newPayment) {
      const payment = {
        name: newPayment,
        code: newPayment
      };
      this.paymentOptions.push(payment);
      this.formData.payment_method.push(payment);
    },
    addExpensesType(newType) {
      this.expensesType.push(newType);
      this.formData.expenses_type = newType;
    },
    gotoPrev() {
      switch (this.curStep) {
        case KEY_IN_STEPS.SELECT_BRAND:
          this.curStep = KEY_IN_STEPS.SELECT_KEY_IN_TYPE;
          break;
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
          {
            if (this.formData.key_in_type === KEY_IN_STEPS.INCOME)
              this.curStep = KEY_IN_STEPS.SELECT_DATE;
            else if (this.formData.key_in_type === KEY_IN_TYPE.EXPENSES)
              this.curStep = KEY_IN_STEPS.SELECT_COUNTRY;
          }
          break;
        case KEY_IN_STEPS.SELECT_PAYMENT_METHOD:
          this.curStep = KEY_IN_STEPS.SELECT_SUM;
          break;
        case KEY_IN_STEPS.SELECT_RECEIVED:
          this.curStep = KEY_IN_STEPS.PAYMENT_METHOD;
          break;
        case KEY_IN_STEPS.SELECT_COMMENTS:
          {
            if (this.formData.key_in_type === KEY_IN_STEPS.INCOME)
              this.curStep = KEY_IN_STEPS.SELECT_RECEIVED;
            else if (this.formData.key_in_type === KEY_IN_TYPE.EXPENSES)
              this.curStep = KEY_IN_STEPS.SELECT_PAYMENT_METHOD;
          }
          break;
        default:
          this.curStep = KEY_IN_STEPS.CREATE_KEY_IN;
          break;
      }
    },
    async onSubmit(event) {
      event.preventDefault();
      switch (this.curStep) {
        case KEY_IN_STEPS.CREATE_KEY_IN:
          this.curStep = KEY_IN_STEPS.SELECT_KEY_IN_TYPE;
          break;
        case KEY_IN_STEPS.SELECT_KEY_IN_TYPE:
          this.curStep = KEY_IN_STEPS.SELECT_BRAND;
          break;
        case KEY_IN_STEPS.SELECT_BRAND:
          this.curStep = KEY_IN_STEPS.SELECT_DATE;
          break;
        case KEY_IN_STEPS.SELECT_DATE:
          {
            if (this.formData.key_in_type === KEY_IN_TYPE.INCOME)
              this.curStep = KEY_IN_STEPS.SELECT_SUM;
            else if (this.formData.key_in_type === KEY_IN_TYPE.EXPENSES)
              this.curStep = KEY_IN_STEPS.SELECT_CURRENCY;
          }
          break;
        case KEY_IN_STEPS.SELECT_SUM:
          this.curStep = KEY_IN_TYPE.SELECT_PAYMENT_METHOD;
          break;
        case KEY_IN_STEPS.SELECT_PAYMENT_METHOD:
          {
            if (this.formData.key_in_type === KEY_IN_TYPE.INCOME)
              this.curStep = KEY_IN_STEPS.SELECT_RECEIVED;
            else if (this.formData.key_in_type === KEY_IN_TYPE.EXPENSES)
              this.curStep = KEY_IN_STEPS.SELECT_COMMENTS;
          }
          break;
        case KEY_IN_STEPS.KEY_IN_STEPS.SELECT_RECEIVED:
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
          this.curStep = KEY_IN_STEPS.CREATE_KEY_IN;
          break;
      }

      if (this.curStep !== KEY_IN_STEPS.SELECT_COMMENTS) return;

      const loader = this.$loading.show();
      let keyInFormData = {
        type: this.formData.key_in_type,
        brand_id: this.formData.brand_id,
        date: this.formData.date,
        sum: this.formData.sum,
        payment_method: this.formData.payment_method,
        comments: this.formData.comments
      };

      if (this.formData.key_in_type === KEY_IN_TYPE.INCOME) {
        keyInFormData.received = this.formData.received;
      } else if (this.formData.key_in_type === KEY_IN_TYPE.EXPENSES) {
        keyInFormData.currency = this.formData.currency;
        keyInFormData.country = this.formData.country;
        keyInFormData.expenses_type = this.formData.expenses_type;
      }
      try {
        let res = await axios.post('/api/keyin', keyInFormData, {
          headers: {
            'Content-Type': 'application/json'
          }
        });
        if (res && res.data) {
          if (res.status === 201) {
            Swal.fire({
              title: 'KeyIn Created.',
              icon: 'success'
            }).then(result => {
              this.formData = {
                key_in_type: KEY_IN_TYPE.INCOME,
                brand_id: null,
                date: null,
                sum: 0,
                payment_method: [],
                received: RECEIVED_STATUS.YES,
                comments: '',
                currency: '',
                country: '',
                expenses_type: ''
              };
            });
          } else {
            Swal.fire({
              title: 'Create KeyIn Failed.',
              icon: 'warning'
            });
          }
        }
      } catch (err) {
        Swal.fire({
          title: 'Create KeyIn Failed.',
          icon: 'error'
        });
      }

      loader.hide();
    }
  }
};
</script>

<style scoped>
.btn-prev {
  cursor: pointer;
}
</style>
