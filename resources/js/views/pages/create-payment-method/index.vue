<template>
  <b-container fluid class="root-container">
    <top-navbar></top-navbar>
    <b-container fluid="xl" class="main-container ml-auto mr-auto py-4">
      <b-form @submit="onSubmit" class="pt-4">
        <b-row>
          <b-col md="6">
            <h3>Add Payment Method</h3>
          </b-col>
        </b-row>

        <b-row>
          <b-col md="4">
            <b-form-group label="Payment Method Name:" label-for="name">
              <b-form-input
                id="name"
                v-model="name"
                type="text"
                required
              ></b-form-input>
            </b-form-group>
          </b-col>
        </b-row>
        <b-row class="mt-2">
          <b-col>
            <b-button type="submit" variant="primary">Sbumit</b-button>
          </b-col>
        </b-row>
      </b-form>
    </b-container>
  </b-container>
</template>

<script>
import Swal from 'sweetalert2';
import TopNavbar from '@/sharedComponents/top-navbar.vue';

export default {
  name: 'create-payment-method',
  components: {
    TopNavbar
  },
  data() {
    return {
      name: ''
    };
  },
  methods: {
    onSubmit(event) {
      event.preventDefault();
      const loader = this.$loading.show();

      axios
        .post(
          '/api/payment-method',
          { name: this.name },
          {
            headers: {
              'Content-Type': 'application/json'
            }
          }
        )
        .then(res => {
          if (res && res.data) {
            this.name = '';
            Swal.fire({
              title: 'Add Payment Method Success.',
              icon: 'success'
            });
          } else {
            Swal.fire({
              title: 'Add Payment Method Failed.',
              icon: 'warning'
            });
          }
          loader.hide();
        })
        .catch(err => {
          Swal.fire({
            title: 'Add Payment Method Failed.',
            icon: 'error'
          });
          loader.hide();
        });
    }
  }
};
</script>
