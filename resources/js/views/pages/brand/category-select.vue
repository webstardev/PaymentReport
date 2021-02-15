<template>
  <b-form @submit="onSubmit">
    <b-row>
      <b-col md="4">
        <a class="btn-prev" @click="gotoPrev">{{ `< Prev` }}</a>
      </b-col>
    </b-row>
    <b-row class="pt-4">
      <b-col md="4">
        <b-form-group label="Category:" label-for="category">
          <b-form-select
            id="category"
            v-model="category.type"
            placeholder="Select category"
            required
          >
            <option disabled value="" :selected="!category.type"
              >Select Category</option
            >
            <option
              v-for="option in Object.keys(categoryOptions)"
              :key="option"
              :value="categoryOptions[option].value"
            >
              {{ categoryOptions[option].title }}
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

<script>
import { CATEGORY } from '@/constants.js';

export default {
  name: 'category-select',
  data() {
    return {
      categoryOptions: CATEGORY,
      category: {}
    };
  },
  props: {
    curCategory: {
      type: Object,
      defautl: function() {
        return {
          type: CATEGORY.AGENT_SYSTEM,
          name: ''
        };
      }
    }
  },
  created() {
    this.category = { ...this.curCategory };
  },
  methods: {
    onSubmit(event) {
      event.preventDefault();
      if (this.category.type !== this.curCategory.type) {
        this.$emit('gotoNext', {
          type: this.category.type,
          agent_system: '',
          name: ''
        });
      } else {
        this.$emit('gotoNext', this.category);
      }
    },
    gotoPrev() {
      this.$emit('gotoPrev');
    }
  }
};
</script>
