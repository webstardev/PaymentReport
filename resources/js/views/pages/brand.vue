<template>
  <b-container fluid class="root-container">
    <top-navbar></top-navbar>
    <b-container fluid="xl" class="ml-auto mr-auto main-container py-4">
      <b-form @submit="onSubmit">
        <b-row class="mb-4">
          <b-col md="6">
            <h3>Create Brand</h3>
          </b-col>
          <b-col md="6" class="d-flex">
            <b-button variant="primary" class="ml-auto" type="submit">
              Create
            </b-button>
          </b-col>
        </b-row>
        <b-row>
          <b-col md="4">
            <b-form-group label="Brand Name:" label-for="brand-name">
              <b-form-input
                id="brand-name"
                v-model="formData.brand_name"
                required
              ></b-form-input>
            </b-form-group>
          </b-col>
        </b-row>
        <hr />
        <b-row>
          <b-col md="4">
            <b-form-group label="Category:" label-for="category">
              <b-form-select
                id="category"
                @change="onChangeCategory"
                :value="formData.category.type"
                required
              >
                <option
                  v-for="option in Object.keys(categoryOptions)"
                  :key="option"
                  :value="categoryOptions[option]"
                >
                  {{ categoryOptions[option] }}
                </option>
              </b-form-select>
            </b-form-group>
          </b-col>
        </b-row>
        <template
          v-if="formData.category.type === categoryOptions.AGENT_SYSTEM"
        >
          <b-row>
            <b-col md="4">
              <b-form-group label="Super Master:" label-for="super-master">
                <b-form-input
                  id="super-master"
                  v-model="formData.category.content.supermaster"
                  required
                ></b-form-input>
              </b-form-group>
            </b-col>
            <b-col md="4">
              <b-form-group label="Master:" label-for="master">
                <b-form-input
                  id="master"
                  v-model="formData.category.content.master"
                  required
                ></b-form-input>
              </b-form-group>
            </b-col>
            <b-col md="4">
              <b-form-group label="Agent:" label-for="agent">
                <b-form-input
                  id="agent"
                  v-model="formData.category.content.agent"
                  required
                ></b-form-input>
              </b-form-group>
            </b-col>
          </b-row>
        </template>
        <template v-else>
          <b-row>
            <b-col
              md="4"
              v-if="formData.category.type !== categoryOptions.AGENT_SYSTEM"
            >
              <b-form-group
                :label="`${formData.category.type} name`"
                label-for="category-name"
              >
                <b-form-input
                  id="category-name"
                  :value="formData.category.content.name"
                  @change="onChangeCategoryName"
                  required
                ></b-form-input>
              </b-form-group>
            </b-col>
          </b-row>
        </template>
        <hr />
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
    </b-container>
  </b-container>
</template>

<script>
import Swal from 'sweetalert2';
import { CATEGORY, COUNTRIES, CURRENCIES, AGENT_SYSTEM } from '@/constants.js';
import TopNavbar from '@/sharedComponents/top-navbar.vue';
export default {
  components: { TopNavbar },
  name: 'brand',
  data() {
    return {
      categoryOptions: CATEGORY,
      countryOptions: COUNTRIES,
      currencyOptions: CURRENCIES,
      agentSystem: AGENT_SYSTEM,
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
    onChangeCategory(value) {
      this.formData.category.type = value;
      if (value === AGENT_SYSTEM.SUPERMASTER) {
        this.formData.category.content = {
          supermaster: '',
          master: '',
          agent: ''
        };
      } else this.formData.category.content = { name: '' };
    },
    onChangeCategoryName(value) {
      this.formData.category.content.name = value;
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
          });
        }
      } catch (err) {
        Swal.fire({
          title: 'Create Brand Failed',
          icon: 'error'
        });
      }
      loader.hide();
    }
  }
};
</script>
