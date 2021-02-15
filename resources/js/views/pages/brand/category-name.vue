<template>
  <b-form @submit="onSubmit">
    <b-row>
      <b-col md="4">
        <a class="btn-prev" @click="gotoPrev">{{ `< Prev` }}</a>
      </b-col>
    </b-row>
    <b-row class="pt-4">
      <b-col md="4">
        <b-form-group label="Category Name" label-for="category-name">
          <b-form-input
            id="category-name"
            v-model="category"
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

<script>
import { CATEGORY } from '@/constants.js';

export default {
  name: 'category-name',
  data() {
    return {
      categoryOptions: CATEGORY,
      category: ''
    };
  },
  props: {
    curCategory: {
      type: String,
      default: ''
    }
  },
  created() {
    this.category = this.curCategory;
  },
  methods: {
    gotoPrev() {
      this.$emit('gotoPrev');
    },
    onSubmit(event) {
      event.preventDefault();
      this.$emit('setValue', this.category);
    }
  }
};
</script>
