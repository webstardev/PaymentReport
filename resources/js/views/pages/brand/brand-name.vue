<template>
  <b-form @submit="onSubmit" class="pt-4">
    <b-row>
      <b-col md="4">
        <b-form-group label="Insert Brand Name" label-for="brand-name">
          <b-form-input
            id="brand-name"
            v-model="brandName"
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
export default {
  name: 'brand-name',
  data() {
    return {
      brandName: ''
    };
  },
  props: {
    curBrandName: {
      type: String,
      default: ''
    }
  },
  created() {
    this.brandName = this.curBrandName;
  },
  methods: {
    onSubmit(event) {
      event.preventDefault();
      this.$emit('gotoNext', {
        brand_name: this.brandName
      });
    }
  }
};
</script>
