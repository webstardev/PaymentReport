<template>
  <b-form @submit="onSubmit">
    <b-row>
      <b-col md="4">
        <a class="btn-prev" @click="gotoPrev">{{ `< Prev` }}</a>
      </b-col>
    </b-row>
    <b-row v-if="category.type === categoryOptions.AGENT_SYSTEM" class="pt-4">
      <b-col md="4">
        <b-form-group label="Super Master:" label-for="super-master">
          <b-form-input
            id="supermaster"
            v-model="category.content.supermaster"
            required
          ></b-form-input>
        </b-form-group>
      </b-col>
      <b-col md="4">
        <b-form-group label="Master:" label-for="master">
          <b-form-input
            id="master"
            v-model="category.content.master"
            required
          ></b-form-input>
        </b-form-group>
      </b-col>
      <b-col md="4">
        <b-form-group label="Agent:" label-for="agent">
          <b-form-input
            id="agent"
            v-model="category.content.agent"
            required
          ></b-form-input>
        </b-form-group>
      </b-col>
    </b-row>
    <b-row v-if="category.type !== categoryOptions.AGENT_SYSTEM" class="pt-4">
      <b-col md="4">
        <b-form-group
          :label="`${curCategory.type} name`"
          label-for="category-name"
        >
          <b-form-input
            id="category-name"
            v-model="category.content.name"
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
  name: 'category-info',
  data() {
    return {
      categoryOptions: CATEGORY,
      category: {
        type: '',
        content: { supermaster: '', master: '', agent: '' }
      }
    };
  },
  props: {
    curCategory: {
      type: Object,
      default: function() {
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
    gotoPrev() {
      this.$emit('gotoPrev');
    },
    onSubmit(event) {
      event.preventDefault();
      if (this.curCategory.type === CATEGORY.AGENT_SYSTEM) {
        this.$emit('setValue', {
          category: {
            type: this.curCategory.type,
            content: {
              supermaster: this.category.content.supermaster,
              master: this.category.content.master,
              agent: this.category.content.agent
            }
          }
        });
      } else {
        this.$emit('setValue', {
          category: {
            type: this.curCategory.type,
            content: { name: this.category.content.name }
          }
        });
      }
    }
  }
};
</script>
