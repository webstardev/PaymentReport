<template>
  <b-form @submit="onSubmit">
    <b-row>
      <b-col md="4">
        <a class="btn-prev" @click="gotoPrev">{{ `< Prev` }}</a>
      </b-col>
    </b-row>

    <b-row>
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
      <b-col md="4">
        <b-form-group label="Currency:" label-for="currency">
          <b-form-select id="country" v-model="formData.currency" required>
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
      <b-col md="4">
        <b-form-group label="Selling:" label-for="selling">
          <b-input-group append="%" label="Selling">
            <b-form-input
              id="selling"
              v-model="formData.selling"
              type="number"
              required
            ></b-form-input>
          </b-input-group>
        </b-form-group>
      </b-col>
      <b-col md="12">
        <b-form-group label="Comment:" label-for="comment">
          <b-form-textarea
            id="comment"
            v-model="formData.comment"
            placeholder="Enter something..."
            rows="3"
            max-rows="6"
            required
          ></b-form-textarea>
        </b-form-group>
      </b-col>
    </b-row>
  </b-form>
</template>

<script>
export default {
  name: 'brand-details',
  props: {
    brandDetails: {
      type: Object,
      default: function() {
        return {
          country: '',
          currency: '',
          selling: 0,
          comment: ''
        };
      }
    }
  },
  data() {
    return {
      curBrandDetails: {
        country: '',
        currency: '',
        selling: 0,
        comment: ''
      }
    };
  },
  created() {
    this.brandDetails = { ...this.curBrandDetails };
  },
  methods: {}
};
</script>
