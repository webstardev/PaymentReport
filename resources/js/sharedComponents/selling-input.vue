<template>
  <b-form @submit="onSubmit">
    <b-row>
      <b-col md="4">
        <a class="btn-prev" @click="gotoPrev">{{ `< Prev` }}</a>
      </b-col>
    </b-row>

    <b-row class="mt-4">
      <b-col md="4">
        <b-form-group label="Selling:" label-for="selling">
          <b-input-group append="%" label="Selling">
            <b-form-input
              id="selling"
              v-model="selling"
              type="number"
              step="0.0001"
              min="0"
              required
            ></b-form-input>
          </b-input-group>
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
  name: 'selling-input',
  props: {
    curSelling: { type: Number, default: 0 }
  },
  data() {
    return {
      selling: 0
    };
  },
  created() {
    this.selling = this.curSelling;
  },
  methods: {
    gotoPrev() {
      this.$emit('gotoPrev');
    },
    onSubmit(event) {
      event.preventDefault();
      this.$emit('gotoNext', this.selling);
    }
  }
};
</script>
