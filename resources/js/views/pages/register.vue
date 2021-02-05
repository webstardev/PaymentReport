<template>
  <div
    class="d-flex justify-content-center align-items-center min-height-100vh"
  >
    <b-form class="login-signup-form" @submit.prevent="onSubmit">
      <h1 class="text-center mb-2">Register</h1>
      <b-form-group
        id="register-email"
        label="Email"
        label-for="input-email"
        :invalid-feedback="errors.first('email')"
        :state="!(submitted && errors.has('email'))"
      >
        <b-form-input
          id="input-email"
          name="email"
          v-model="form.email"
          v-validate="`required`"
          placeholder="Enter email"
          type="email"
        ></b-form-input>
      </b-form-group>
      <b-form-group
        id="register-username"
        label="Username"
        label-for="input-username"
        :invalid-feedback="errors.first('username')"
        :state="!(submitted && errors.has('username'))"
      >
        <b-form-input
          id="input-username"
          name="username"
          v-model="form.username"
          v-validate="`required`"
          placeholder="Enter username"
        ></b-form-input>
      </b-form-group>
      <b-form-group
        id="login-password"
        label="Password"
        label-for="input-password"
        :invalid-feedback="errors.first('password')"
        :state="!(submitted && errors.has('password'))"
      >
        <b-form-input
          id="input-password"
          name="password"
          v-model="form.password"
          v-validate="`required|min:8|verify_password`"
          placeholder="Enter password"
          type="password"
        ></b-form-input>
      </b-form-group>
      <div class="d-flex justify-content-end mt-4">
        <b-button variant="outline-primary" @click="handleClickLogin"
          >Login</b-button
        >
        <b-button variant="primary" class="ml-3" type="submit"
          >Register</b-button
        >
      </div>
    </b-form>
  </div>
</template>

<script>
import Swal from 'sweetalert2';
import { LOGIN } from '@/services/store/auth.module';
import { REGISTER_USER } from '@/utils/validators';

export default {
  name: 'register-modal',
  components: {
    Swal
  },
  data() {
    return {
      submitted: false,
      form: {
        email: '',
        username: '',
        password: ''
      }
    };
  },
  created() {
    this.$validator.localize('en', REGISTER_USER.en);
  },
  methods: {
    async onSubmit(event) {
      this.submitted = true;
      this.$validator.validateAll().then(async valid => {
        if (valid) {
          const loader = this.$loading.show();
          try {
            let res = await axios.post('/api/register', this.form);
            debugger;
            if (res && res.data) {
              Swal.fire({
                title: 'Register Successed',
                icon: 'success'
              }).then(result => {
                this.$store.dispatch(LOGIN, res.data);
                this.$router.push('/');
              });
            }
          } catch (err) {
            if (err.response.status === 409) {
              this.$validator.errors.add({
                field: err.response.data.error.field,
                msg: err.response.data.error.msg
              });
            } else {
              Swal.fire({
                title: 'Register Failed',
                icon: 'error'
              });
            }
          }
          loader.hide();
        }
      });
    },
    handleClickLogin() {
      this.$router.push({ name: 'login' });
    }
  }
};
</script>
