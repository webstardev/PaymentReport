<template>
  <b-container fluid class="root-container">
    <top-navbar></top-navbar>
    <b-container fluid="xl" class="main-container ml-auto mr-auto py-4">
      <b-form @submit="onSubmit" class="pt-4">
        <b-row>
          <b-col md="6">
            <h3>Add Expenses Type</h3>
          </b-col>
        </b-row>

        <b-row>
          <b-col md="4">
            <b-form-group label="Expenses Name" label-for="name">
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
            <b-button type="submit" variant="primary">Submit</b-button>
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
  name: 'create-expenses-type',
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
          '/api/expenses-type',
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
              title: 'Create Expenses Type Success.',
              icon: 'success'
            });
          } else {
            Swal.fire({
              title: 'Create Expenses Type Failed.',
              icon: 'warning'
            });
          }
          loader.hide();
        })
        .catch(err => {
          Swal.fire({
            title: 'Create Expenses Type Failed.',
            icon: 'error'
          });
          loader.hide();
        });
    }
  }
};
</script>
