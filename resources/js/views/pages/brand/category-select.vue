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
          <b-form-select id="category" v-model="category.type" required>
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
          content: { supermaster: '', master: '', agent: '' }
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
        if (this.category.type === CATEGORY.AGENT_SYSTEM) {
          this.$emit('gotoNext', {
            type: this.category.type,
            content: {
              supermaster: '',
              master: '',
              agent: ''
            }
          });
        } else {
          this.$emit('gotoNext', {
            type: this.category.type,
            content: {
              name: ''
            }
          });
        }
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
