<template>
  <div
    class="d-flex justify-content-center align-items-center min-height-100vh"
  >
    <b-form class="login-signup-form" @submit="onSubmit">
      <h1 class="text-center mb-2">Register</h1>
      <b-form-group id="register-email" label="Email" label-for="input-email">
        <b-form-input
          id="input-email"
          v-model="form.email"
          placeholder="Enter email"
          type="email"
          required
        ></b-form-input>
      </b-form-group>
      <b-form-group
        id="register-username"
        label="Username"
        label-for="input-username"
      >
        <b-form-input
          id="input-username"
          v-model="form.username"
          placeholder="Enter username"
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
          <b-button variant="outline-primary">Login</b-button>
          <b-button variant="primary" class="ml-3" type="submit"
            >Register</b-button
          >
        </div>
      </b-form-group>
    </b-form>
  </div>
</template>

<script>
import Swal from 'sweetalert2';
import { LOGIN } from '@/services/store/auth.module';

export default {
  name: 'register-modal',
  components: {
    Swal
  },
  data() {
    return {
      form: {
        email: '',
        username: '',
        password: ''
      }
    };
  },
  methods: {
    async onSubmit(event) {
      event.preventDefault();
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
        Swal.fire({
          title: 'Register Failed',
          icon: 'error'
        });
      }
      loader.hide();
    }
  },
  mounted() {
    this.form = { email: '', username: '', password: '' };
  }
};
</script>
