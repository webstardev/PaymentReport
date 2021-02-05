<template>
  <div
    class="d-flex justify-content-center align-items-center min-height-100vh"
  >
    <b-form class="login-signup-form" @submit="onSubmit">
      <h1 class="text-center mb-2">Login</h1>
      <b-form-group
        id="login-username"
        label="Username or Email"
        label-for="input-username"
      >
        <b-form-input
          id="input-username"
          v-model="form.username"
          placeholder="Enter usrename or email"
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
          type="password"
          required
        ></b-form-input>
        <div class="d-flex justify-content-end mt-4">
          <b-button @click="hideModal">Register</b-button>
          <b-button variant="primary" class="ml-3" type="submit">Ok</b-button>
        </div>
      </b-form-group>
    </b-form>
  </div>
</template>

<script>
export default {
  name: 'login',
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
