<template>
  <b-container fluid class="root-container">
    <top-navbar></top-navbar>
    <b-container fluid="xl" class="ml-auto mr-auto py-4">
      <b-row>
        <b-col md="12">
          <h1>Create Brand</h1>
        </b-col>
        <b-col md="6">
          <b-form-group label="Brand Name:" label-for="brand-name">
            <b-form-input
              id="brand-name"
              v-model="formData.brandName"
            ></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>
      <hr />
      <b-row>
        <b-col md="6">
          <b-form-group label="Category:" label-for="category">
            <b-form-select id="category" v-model="formData.category">
              <option
                v-for="(option, idx) in categoryOptions"
                :key="idx"
                :value="option.value"
              >
                {{ option.text }}
              </option>
            </b-form-select>
          </b-form-group>
        </b-col>
      </b-row>
    </b-container>
  </b-container>
</template>

<script>
import { CATEGORY_OPTIONS } from '@/constants.js';
import TopNavbar from '@/sharedComponents/top-navbar.vue';
export default {
  components: { TopNavbar },
  name: 'brand',
  data() {
    return {
      categoryOptions: CATEGORY_OPTIONS,
      formData: {
        brandName: '',
        category: CATEGORY_OPTIONS[0].value
      }
    };
  }
};
</script>
