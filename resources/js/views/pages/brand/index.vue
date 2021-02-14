<template>
  <b-container fluid class="root-container">
    <top-navbar></top-navbar>
    <b-container fluid="xl" class="main-container  ml-auto mr-auto py-4">
      <template v-if="curStep === brandSteps.CREATE_BRAND">
        <b-row>
          <b-col md="6" class="d-flex">
            <b-button variant="primary" type="button" @click="createBrand">
              Create Button
            </b-button>
          </b-col>
        </b-row>
      </template>

      <template v-if="curStep === brandSteps.BRAND_NAME">
        <brand-name
          :curBrandName="formData.brand_name"
          @gotoNext="
            $event => {
              formData = { ...formData, ...$event };
              curStep = brandSteps.CATEGORY_SELECT;
            }
          "
        ></brand-name>
      </template>

      <b-form @submit="onSubmit" class="pt-4">
        <template v-if="curStep === brandSteps.CATEGORY_SELECT">
          <category-select
            :curCategory="formData.category"
            @gotoPrev="
              $event => {
                curStep = brandSteps.BRAND_NAME;
              }
            "
            @gotoNext="
              $event => {
                formData = { ...formData, category: { ...$event } };
                curStep = brandSteps.CATEGORY_INFO;
              }
            "
          >
          </category-select>
        </template>

        <template v-if="curStep === brandSteps.CATEGORY_INFO">
          <category-info
            :curCategory="formData.category"
            @gotoPrev="
              $event => {
                curStep = brandSteps.CATEGORY_SELECT;
              }
            "
            @setValue="
              $event => {
                formData = { ...formData, category: { ...$event } };
                curStep = brandSteps.BRAND_DETAILS;
              }
            "
          ></category-info>
        </template>

        <template v-if="curStep === brandSteps.BRAND_DETAILS">
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
                <b-form-select
                  id="country"
                  v-model="formData.currency"
                  required
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
        </template>
        <!-- <b-row class="mt-2" v-if="curStep !== brandSteps.CREATE_BRAND">
          <b-col>
            <b-button variant="primary" @click="nextStep">
              {{ curStep !== brandSteps.BRAND_DETAILS ? 'Next' : 'Submit' }}
            </b-button>
          </b-col>
        </b-row> -->
      </b-form>
    </b-container>
  </b-container>
</template>

<script>
import Swal from 'sweetalert2';
import {
  BRAND_STPES,
  CATEGORY,
  COUNTRIES,
  CURRENCIES,
  AGENT_SYSTEM
} from '@/constants.js';

import TopNavbar from '@/sharedComponents/top-navbar.vue';
import BrandName from './brand-name.vue';
import CategorySelect from './category-select.vue';
import CategoryInfo from './category-info.vue';

export default {
  components: { TopNavbar, BrandName, CategorySelect, CategoryInfo },
  name: 'brand',
  data() {
    return {
      brandSteps: BRAND_STPES,
      categoryOptions: CATEGORY,
      countryOptions: COUNTRIES,
      currencyOptions: CURRENCIES,
      agentSystem: AGENT_SYSTEM,
      curStep: BRAND_STPES.CREATE_BRAND,
      formData: {
        brand_name: '',
        category: {
          type: CATEGORY.AGENT_SYSTEM,
          content: { supermaster: '', master: '', agent: '' }
        },
        country: '',
        currency: '',
        selling: 0,
        comment: ''
      }
    };
  },
  methods: {
    createBrand() {
      this.curStep = this.brandSteps.BRAND_NAME;
    },
    async onSubmit(event) {
      event.preventDefault();
      const loader = this.$loading.show();
      try {
        let res = await axios.post('/api/brand', this.formData, {
          headers: {
            'Content-Type': 'application/json'
          }
        });
        if (res && res.data) {
          Swal.fire({
            title: `Brand ${this.formData.brand_name} Created`,
            icon: 'success'
          }).then(result => {
            this.formData = {
              brand_name: '',
              category: {
                type: CATEGORY.AGENT_SYSTEM,
                content: { supermaster: '', master: '', agent: '' }
              },
              country: '',
              currency: '',
              selling: 0,
              comment: ''
            };
          });
        }
      } catch (err) {
        Swal.fire({
          title: 'Create Brand Failed.',
          icon: 'error'
        });
      }
      loader.hide();
    }
  }
};
</script>

<style lang="scss">
.btn-prev {
  cursor: pointer;
}
</style>
