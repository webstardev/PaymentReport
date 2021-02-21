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
                :taggable="true"
                :creatable="false"
                required
              ></multiselect>
            </b-form-group>
          </b-col>
        </b-row>
        <b-row v-if="curStep === keySteps.SELECT_RECEIVED">
          <b-col md="4">
            <b-form-group label="Received" label-for="received">
              <b-form-select id="received" v-model="formData.received" required>
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
import { getBrand, getPaymentMethod } from '@/services/apis';
import { KEY_IN_STEPS, KEY_IN_TYPE, RECEIVED_STATUS } from '@/constants';
import TopNavbar from '@/sharedComponents/top-navbar.vue';

export default {
  name: 'income-key-in',
  components: {
    TopNavbar
  },
  data() {
    return {
      keySteps: KEY_IN_STEPS,
      curStep: KEY_IN_STEPS.SELECT_BRAND,
      receivedStatus: RECEIVED_STATUS,
      brandList: [],
      paymentMethodList: [],
      formData: {
        brand_id: '',
        date: null,
        sum: 0,
        payment_method: [],
        received: RECEIVED_STATUS.YES,
        comments: ''
      }
    };
  },
  async created() {
    const loader = this.$loading.show();
    try {
      this.brandList = await getBrand();
      this.paymentMethodList = await getPaymentMethod();
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
        case KEY_IN_STEPS.SELECT_SUM:
          this.curStep = KEY_IN_STEPS.SELECT_DATE;
          break;
        case KEY_IN_STEPS.SELECT_PAYMENT_METHOD:
          this.curStep = KEY_IN_STEPS.SELECT_SUM;
          break;
        case KEY_IN_STEPS.SELECT_RECEIVED:
          this.curStep = KEY_IN_STEPS.SELECT_PAYMENT_METHOD;
          break;
        case KEY_IN_STEPS.SELECT_COMMENTS:
          this.curStep = KEY_IN_STEPS.SELECT_RECEIVED;
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
          type: KEY_IN_TYPE.INCOME,
          brand_id: this.formData.brand_id,
          date: this.formData.date,
          sum: this.formData.sum,
          payment_method: this.formData.payment_method.map(x => x.id),
          received: this.formData.received,
          comments: this.formData.comments
        };
        try {
          let res = await axios.post('/api/income-key-in', keyInFormData, {
            headers: {
              'Content-Type': 'application/json'
            }
          });
          if (res && res.data) {
            if (res.status === 201) {
              Swal.fire({
                title: 'Income Add Success.',
                icon: 'success'
              }).then(result => {
                this.formData = {
                  brand_id: null,
                  date: null,
                  sum: 0,
                  payment_method: [],
                  received: RECEIVED_STATUS.YES,
                  comments: ''
                };
              });
              this.curStep = KEY_IN_STEPS.SELECT_BRAND;
            } else {
              Swal.fire({
                title: 'Income Add Failed.',
                icon: 'warning'
              });
            }
          }
        } catch (err) {
          Swal.fire({
            title: 'Income Add Failed.',
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
            this.curStep = KEY_IN_STEPS.SELECT_SUM;
            break;
          case KEY_IN_STEPS.SELECT_SUM:
            this.curStep = KEY_IN_STEPS.SELECT_PAYMENT_METHOD;
            break;
          case KEY_IN_STEPS.SELECT_PAYMENT_METHOD:
            this.curStep = KEY_IN_STEPS.SELECT_RECEIVED;
            break;
          case KEY_IN_STEPS.SELECT_RECEIVED:
            this.curStep = KEY_IN_STEPS.SELECT_COMMENTS;
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
