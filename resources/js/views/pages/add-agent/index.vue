<template>
  <b-container fluid class="root-container">
    <top-navbar></top-navbar>
    <b-container fluid class="main-container ml-auto mr-auto py-4">
      <template v-if="curStep === agentSteps.AGENT_NAME">
        <b-form @submit="onSubmit">
          <b-row class="pt-4">
            <b-col md="4">
              <b-form-group label="Insert Brand Name" label-for="agent-name">
                <b-form-input
                  id="agent-name"
                  v-model="formData.name"
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

      <template v-if="curStep === agentSteps.BRAND_SELECT">
        <b-form @submit="onSubmit">
          <b-row>
            <b-col md="4">
              <a class="btn-prev" @click="curStep = agentSteps.AGENT_NAME">{{
                `< Prev`
              }}</a>
            </b-col>
          </b-row>
          <b-row class="pt-4">
            <b-col md="4">
              <b-form-group label="Brand:" label-for="brand">
                <b-form-select
                  id="brand"
                  v-model="formData.parent_id"
                  placeholder="Select Brand"
                  required
                >
                  <option disabled value="" :selected="!formData.parent_id"
                    >Select Brand</option
                  >
                  <option
                    v-for="brand in agentBrandList"
                    :key="brand.id"
                    :value="brand.id"
                  >
                    {{ brand.name }}
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

      <template v-if="curStep === agentSteps.CATEGORY_SELECT">
        <b-form @submit="onSubmit">
          <b-row>
            <b-col md="4">
              <a class="btn-prev" @click="curStep = agentSteps.BRAND_SELECT">{{
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
                  required
                >
                  <option disabled value="" :selected="!formData.category_id"
                    >Select Category</option
                  >
                  <option
                    v-for="category in categoryList"
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

      <template v-if="curStep === agentSteps.COUNTRY">
        <country-selector
          :curCountry="formData.country"
          @gotoPrev="
            $event => {
              curStep = agentSteps.CATEGORY_SELECT;
            }
          "
          @gotoNext="
            $event => {
              curStep = agentSteps.CURRENCY;
              formData = { ...formData, country: $event };
            }
          "
        ></country-selector>
      </template>

      <template v-if="curStep === agentSteps.CURRENCY">
        <currency-selector
          :curCurrency="formData.currency"
          @gotoPrev="
            $event => {
              curStep = agentSteps.COUNTRY;
            }
          "
          @gotoNext="
            $event => {
              curStep = agentSteps.SELLING;
              formData = { ...formData, currency: $event };
            }
          "
        ></currency-selector>
      </template>

      <template v-if="curStep === agentSteps.SELLING">
        <selling-input
          :curSelling="formData.selling"
          @gotoPrev="
            $event => {
              curStep = agentSteps.CURRENCY;
            }
          "
          @gotoNext="
            $event => {
              curStep = agentSteps.COMMENTS;
              formData.selling = $event;
            }
          "
        ></selling-input>
      </template>

      <template v-if="curStep === agentSteps.COMMENTS">
        <b-form @submit="onSubmit">
          <b-row>
            <b-col md="4">
              <a class="btn-prev" @click="curStep = agentSteps.SELLING">{{
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
import { AGENT_STEPS } from '@/constants';
import TopNavbar from '@/sharedComponents/top-navbar.vue';
import CountrySelector from '@/sharedComponents/country-selector';
import CurrencySelector from '@/sharedComponents/currency-selector';
import SellingInput from '@/sharedComponents/selling-input.vue';
import CommentsInput from '@/sharedComponents/comments-input.vue';

export default {
  name: 'add-agent',
  components: {
    TopNavbar,
    CountrySelector,
    CurrencySelector,
    SellingInput,
    CommentsInput
  },
  data() {
    return {
      agentSteps: AGENT_STEPS,
      curStep: AGENT_STEPS.AGENT_NAME,
      categoryList: [],
      agentBrandList: [],
      formData: {
        name: '',
        parent_id: '',
        category_id: '',
        country: '',
        currency: '',
        selling: '',
        comments: ''
      }
    };
  },
  async created() {
    try {
      const resAgentBrand = await axios.post(
        '/api/brand/filter',
        { filter: { category_id: 1 } },
        {
          headers: {
            'Content-Type': 'application/json'
          }
        }
      );
      if (resAgentBrand && resAgentBrand.data)
        this.agentBrandList = resAgentBrand.data;
    } catch (err) {
      console.log('Add agent page: Get list data error');
    }

    try {
      const resSubCategory = await axios.get(`/api/category/${1}/sub-category`);
      if (resSubCategory && resSubCategory.data)
        this.categoryList = resSubCategory.data;
    } catch (err) {
      console.log('Add agent page: Get list data error');
    }
  },
  methods: {
    createAgent() {
      const loader = this.$loading.show();
      axios
        .post('/api/brand', this.formData, {
          headers: {
            'Content-Type': 'application/json'
          }
        })
        .then(res => {
          if (res && res.data) {
            Swal.fire({
              title: `Agent ${this.formData.name} Created`,
              icon: 'success'
            });
            this.curStep = AGENT_STEPS.AGENT_NAME;
            this.formData = {
              name: '',
              parent_id: '',
              category_id: '',
              country: '',
              currency: '',
              selling: 0,
              comments: ''
            };
          } else {
            Swal.fire({
              title: `Agent ${this.formData.name} Failed`,
              icon: 'warning'
            });
          }
          loader.hide();
        })
        .catch(err => {
          loader.hide();
          Swal.fire({
            title: `Agent ${this.formData.name} Failed`,
            icon: 'error'
          });
        });
    },
    onSubmit(event) {
      event.preventDefault();
      switch (this.curStep) {
        case AGENT_STEPS.AGENT_NAME:
          this.curStep = AGENT_STEPS.BRAND_SELECT;
          break;
        case AGENT_STEPS.BRAND_SELECT:
          this.curStep = AGENT_STEPS.CATEGORY_SELECT;
          break;
        case AGENT_STEPS.CATEGORY_SELECT:
          this.curStep = AGENT_STEPS.COUNTRY;
          break;
        case AGENT_STEPS.COUNTRY:
          this.curStep = AGENT_STEPS.CURRENCY;
          break;
        case AGENT_STEPS.CURRENCY:
          this.curStep = AGENT_STEPS.SELLING;
          break;
        case AGENT_STEPS.SELLING:
          this.curStep = AGENT_STEPS.COMMENTS;
          break;
        case AGENT_STEPS.COMMENTS:
          this.createAgent();
          break;
        default:
          this.curStep = AGENT_STEPS.AGENT_NAME;
          break;
      }
    }
  }
};
</script>
