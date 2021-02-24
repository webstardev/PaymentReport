<template>
  <b-container fluid class="root-container">
    <top-navbar></top-navbar>
    <b-container fluid class="main-container  ml-auto mr-auto py-4">
      <template v-if="curStep === brandSteps.BRAND_NAME">
        <b-form @submit="onSubmit">
          <b-row class="pt-4">
            <b-col md="4">
              <b-form-group label="Insert Brand Name" label-for="brand-name">
                <b-form-input
                  id="brand-name"
                  v-model="formData.brand_name"
                  required
                ></b-form-input>
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

      <template v-if="curStep === brandSteps.CATEGORY_SELECT">
        <b-form @submit="onSubmit">
          <b-row>
            <b-col md="4">
              <a class="btn-prev" @click="curStep = brandSteps.BRAND_NAME">{{
                `< Prev`
              }}</a>
            </b-col>
          </b-row>
          <b-row class="pt-4">
            <b-col md="4">
              <b-form-group label="Category:" label-for="category">
                <b-form-select
                  id="category"
                  v-model="formData.category_id"
                  placeholder="Select category"
                  @change="changeCategoryId"
                  required
                >
                  <option disabled value="" :selected="!formData.category_id"
                    >Select Category</option
                  >
                  <option
                    v-for="category in categories.filter(
                      item => item.parent === -1
                    )"
                    :key="category.id"
                    :value="category.id"
                  >
                    {{ category.name }}
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

      <template v-if="curStep === brandSteps.AGENT_SYSTEM_SELECT">
        <b-form @submit="onSubmit">
          <b-row>
            <b-col md="4">
              <a
                class="btn-prev"
                @click="curStep = brandSteps.CATEGORY_SELECT"
                >{{ `< Prev` }}</a
              >
            </b-col>
          </b-row>
          <b-row class="pt-4">
            <b-col md="4">
              <b-form-group label="Agent System:" label-for="agent-system">
                <b-form-select
                  id="agent-system"
                  v-model="formData.subcategory_id"
                  required
                >
                  <option
                    value=""
                    disabled
                    :selected="!formData.subcategory_id"
                  >
                    Select agent system
                  </option>
                  <option
                    v-for="subCategory in subCategories"
                    :key="subCategory.id"
                    :value="subCategory.id"
                  >
                    {{ subCategory.name }}
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

      <template v-if="curStep === brandSteps.COUNTRY">
        <country-selector
          :curCountry="formData.country"
          @gotoPrev="
            $event => {
              if (formData.subcategory_id)
                curStep = brandSteps.AGENT_SYSTEM_SELECT;
              else curStep = brandSteps.CATEGORY_SELECT;
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
        <b-form @submit="onSubmit">
          <b-row>
            <b-col md="4">
              <a class="btn-prev" @click="curStep = brandSteps.SELLING">{{
                `< Prev`
              }}</a>
            </b-col>
          </b-row>
          <b-row class="pt-4">
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
          <b-row>
            <b-col md="4">
              <b-button type="submit" variant="primary">
                Submit
              </b-button>
            </b-col>
          </b-row>
        </b-form>
      </template>
    </b-container>
  </b-container>
</template>

<script>
import Swal from 'sweetalert2';
import { BRAND_STPES, COUNTRIES, CURRENCIES } from '@/constants.js';

import TopNavbar from '@/sharedComponents/top-navbar.vue';
import CountrySelector from '@/sharedComponents/country-selector.vue';
import CurrencySelector from '@/sharedComponents/currency-selector.vue';
import SellingInput from '@/sharedComponents/selling-input.vue';
import CommentsInput from '@/sharedComponents/comments-input.vue';

export default {
  components: {
    TopNavbar,
    CountrySelector,
    CurrencySelector,
    SellingInput,
    CommentsInput
  },
  name: 'brand',
  data() {
    return {
      brandSteps: BRAND_STPES,
      categories: [],
      countryOptions: COUNTRIES,
      currencyOptions: CURRENCIES,
      curStep: BRAND_STPES.BRAND_NAME,
      formData: {
        brand_name: '',
        category_id: '',
        subcategory_id: '',
        country: '',
        currency: '',
        selling: 0,
        comments: ''
      }
    };
  },
  created() {
    const loader = this.$loading.show();
    axios
      .get('/api/category/all')
      .then(res => {
        if (res && res.data) this.categories = [...res.data];
        loader.hide();
      })
      .catch(err => {
        loader.hide();
      });
  },
  computed: {
    subCategories: function() {
      if (
        this.formData.category_id &&
        this.checkHadChild(this.formData.category_id)
      ) {
        return this.categories.filter(
          item => item.parent === this.formData.category_id
        );
      } else return [];
    }
  },
  methods: {
    changeCategoryId(event) {
      this.formData.subcategory_id = '';
    },
    checkHadChild(id) {
      const findOne = this.categories.find(item => item.id === id);
      if (findOne) {
        if (findOne.had_child >= 0) return true;
        else return false;
      } else return false;
    },
    async onSubmit(event) {
      event.preventDefault();
      switch (this.curStep) {
        case BRAND_STPES.BRAND_NAME:
          this.curStep = BRAND_STPES.CATEGORY_SELECT;
          break;
        case BRAND_STPES.CATEGORY_SELECT:
          {
            debugger;
            if (this.checkHadChild(this.formData.category_id))
              this.curStep = BRAND_STPES.AGENT_SYSTEM_SELECT;
            else this.curStep = BRAND_STPES.COUNTRY;
          }
          break;
        case BRAND_STPES.AGENT_SYSTEM_SELECT:
          this.curStep = BRAND_STPES.COUNTRY;
          break;
        case BRAND_STPES.COUNTRY:
          this.curStep = BRAND_STPES.CURRENCY;
          break;
        case BRAND_STPES.CURRENCY:
          this.curStep = BRAND_STPES.SELLING;
          break;
        case BRAND_STPES.COMMENTS:
          {
            const loader = this.$loading.show();
            try {
              let res = await axios.post(
                '/api/brand',
                {
                  ...this.formData,
                  category_id: this.checkHadChild(this.formData.category_id)
                    ? this.formData.subcategory_id
                    : this.formData.category_id
                },
                {
                  headers: {
                    'Content-Type': 'application/json'
                  }
                }
              );
              if (res && res.data) {
                Swal.fire({
                  title: `Brand ${this.formData.brand_name} Created`,
                  icon: 'success'
                }).then(result => {
                  this.formData = {
                    brand_name: '',
                    category_id: null,
                    subcategory_id: null,
                    country: '',
                    currency: '',
                    selling: 0,
                    comments: ''
                  };
                  this.curStep = BRAND_STPES.BRAND_NAME;
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
          break;
        default:
          this.curStep = BRAND_STPES.BRAND_NAME;
          break;
      }
    }
  }
};
</script>

<style lang="scss">
.btn-prev {
  cursor: pointer;
}
</style>
