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
              if ($event.type === categoryOptions.AGENT_SYSTEM.value) {
                curStep = brandSteps.AGENT_SYSTEM_SELECT;
              } else {
                curStep = brandSteps.CATEGORY_INFO;
              }
            }
          "
        >
        </category-select>
      </template>

      <template v-if="curStep === brandSteps.AGENT_SYSTEM_SELECT">
        <agent-system-selector
          :curAgentySystem="formData.category.agent_system"
          @gotoPrev="
            $event => {
              curStep = brandSteps.CATEGORY_SELECT;
            }
          "
          @gotoNext="
            $event => {
              curStep = brandSteps.CATEGORY_INFO;
              formData = {
                ...formData,
                category: { ...formData.category, ...$event }
              };
            }
          "
        >
        </agent-system-selector>
      </template>

      <template v-if="curStep === brandSteps.CATEGORY_INFO">
        <category-name
          :curCategory="formData.category.name"
          @gotoPrev="
            $event => {
              if (formData.category.type === categoryOptions.AGENT_SYSTEM.value)
                curStep = brandSteps.AGENT_SYSTEM;
              else curStep = brandSteps.CATEGORY_SELECT;
            }
          "
          @setValue="
            $event => {
              formData = {
                ...formData,
                category: { ...formData.category, name: $event }
              };
              curStep = brandSteps.COUNTRY;
            }
          "
        ></category-name>
      </template>

      <template v-if="curStep === brandSteps.COUNTRY">
        <country-selector
          :curCountry="formData.country"
          @gotoPrev="
            $event => {
              curStep = brandSteps.CATEGORY_INFO;
            }
          "
          @gotoNext="
            $event => {
              curStep = brandSteps.CURRENCY;
              formData = { ...formData, country: $event };
            }
          "
        ></country-selector>
      </template>

      <template v-if="curStep === brandSteps.CURRENCY">
        <currency-selector
          :curCurrency="formData.currency"
          @gotoPrev="
            $event => {
              curStep = brandSteps.COUNTRY;
            }
          "
          @gotoNext="
            $event => {
              curStep = brandSteps.SELLING;
              formData = { ...formData, currency: $event };
            }
          "
        ></currency-selector>
      </template>

      <template v-if="curStep === brandSteps.SELLING">
        <selling-input
          :curSelling="formData.selling"
          @gotoPrev="
            $event => {
              curStep = brandSteps.CURRENCY;
            }
          "
          @gotoNext="
            $event => {
              curStep = brandSteps.COMMENTS;
              formData.selling = $event;
            }
          "
        ></selling-input>
      </template>

      <template v-if="curStep === brandSteps.COMMENTS">
        <comments-input
          actionButton="Submit"
          @curComments="formData.comments"
          @gotoPrev="
            $event => {
              curStep = brandSteps.SELLING;
            }
          "
          @gotoNext="
            $event => {
              formData = { ...formData, comments: $event };
              submit();
            }
          "
        ></comments-input>
      </template>
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
import CategoryName from './category-name.vue';
import AgentSystemSelector from './agent-system-selector.vue';
import CountrySelector from '@/sharedComponents/country-selector.vue';
import CurrencySelector from '@/sharedComponents/currency-selector.vue';
import SellingInput from '@/sharedComponents/selling-input.vue';
import CommentsInput from '@/sharedComponents/comments-input.vue';

export default {
  components: {
    TopNavbar,
    BrandName,
    CategorySelect,
    CategoryName,
    AgentSystemSelector,
    CountrySelector,
    CurrencySelector,
    SellingInput,
    CommentsInput
  },
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
          type: '',
          agent_system: '',
          name: ''
        },
        country: '',
        currency: '',
        selling: 0,
        comments: ''
      }
    };
  },
  methods: {
    createBrand() {
      this.curStep = BRAND_STPES.BRAND_NAME;
    },
    async submit(event) {
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
                type: '',
                agent_system: '',
                name: ''
              },
              country: '',
              currency: '',
              selling: 0,
              comments: ''
            };
            this.curStep = BRAND_STPES.CREATE_BRAND;
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
