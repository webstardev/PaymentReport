<template>
  <div
    class="d-flex justify-content-center align-items-center min-height-100vh"
  >
    <b-form class="login-signup-form" @submit.prevent="onSubmit">
      <h1 class="text-center mb-2">Login</h1>
      <b-form-group
        id="login-username"
        label="Username or Email"
        label-for="input-username"
        :invalid-feedback="errors.first('username')"
        :state="!(submitted && errors.has('username'))"
      >
        <b-form-input
          id="input-username"
          name="username"
          v-model="formData.username"
          v-validate="`required`"
          placeholder="Enter usrename or email"
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
          v-model="formData.password"
          placeholder="Enter password"
          v-validate="`required|min:8|verify_password`"
          type="password"
        ></b-form-input>
      </b-form-group>
      <div class="d-flex justify-content-end mt-4">
        <b-button variant="primary" class="ml-3" type="submit"
          >Sign In</b-button
        >
      </div>
    </b-form>
  </div>
</template>

<script>
import Swal from 'sweetalert2';
import { LOGIN } from '@/services/store/auth.module';
import { LOGIN_USER } from '@/utils/validators';

export default {
  name: 'login',
  data() {
    return {
      submitted: false,
      formData: {
        username: '',
        password: ''
      }
    };
  },
  created() {
    this.$validator.localize('en', LOGIN_USER.en);
  },

  methods: {
    async onSubmit(event) {
      this.submitted = true;
      this.$validator.validateAll().then(async valid => {
        if (valid) {
          const loader = this.$loading.show();
          try {
            let res = await axios.post('/api/login', this.formData);
            if (res && res.data) {
              this.$store.dispatch(LOGIN, res.data);
              this.$router.push('/');
            }
          } catch (err) {
            Swal.fire({
              title: 'Login Failed',
              text: 'Username or passowrd is wrong.',
              icon: 'error'
            });
          }
          loader.hide();
        }
      });
    }
  }
};
</script>
