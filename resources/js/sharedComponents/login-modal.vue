<template>
  <b-modal id="login-modal" title="Login" hide-footer>
    <b-form>
      <b-form-group
        id="login-username"
        label="Username or Email"
        label-for="input-username"
      >
        <b-form-input
          id="input-username"
          v-model="form.username"
          placeholder="Enter Username or Email"
          required
        ></b-form-input>
      </b-form-group>
      <b-form-group
        id="login-password"
        label="Password"
        label-for="input-password"
      >
        <b-form-input
          id="input-password"
          v-model="form.password"
          placeholder="Enter password"
          required
        ></b-form-input>
        <div class="d-flex justify-content-end mt-4">
          <b-button>Cancel</b-button>
          <b-button variant="primary" class="ml-3" type="submit">Ok</b-button>
        </div>
      </b-form-group>
    </b-form>
  </b-modal>
</template>

<script>
export default {
  name: 'login-modal',
  data() {
    return {
      form: {
        username: '',
        password: ''
      }
    };
  }
};
</script>
