<template>
  <b-form @submit="onSubmit">
    <b-row>
      <b-col md="4">
        <a class="btn-prev" @click="gotoPrev">{{ `< Prev` }}</a>
      </b-col>
    </b-row>
    <b-row class="pt-4">
      <b-col md="4">
        <b-form-group label="Comments:" label-for="comments">
          <b-form-textarea
            id="comments"
            v-model="comments"
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
        <b-button type="submit" variant="primary">Next</b-button>
      </b-col>
    </b-row>
  </b-form>
</template>

<script>
export default {
  name: 'comments-input',
  props: {
    curComments: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      comments: ''
    };
  },
  created() {
    this.comments = this.curComments;
  },
  methods: {
    gotoPrev() {
      this.$emit('gotoPrev');
    },
    onSubmit(event) {
      event.preventDefault();
      this.$emit('gotoNext', this.comments);
    }
  }
};
</script>
