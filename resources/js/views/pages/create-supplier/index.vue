<template>
  <b-container fluid class="root-container">
    <top-navbar></top-navbar>
    <b-container fluid class="main-container ml-auto mr-auto py-4">
      <b-form @submit="onSubmit" class="pt-4">
        <b-row>
          <b-col md="6">
            <h3>Add Supplier</h3>
          </b-col>
        </b-row>

        <b-row>
          <b-col md="4">
            <b-form-group label="Supplier Name" label-for="name">
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
  name: 'create-supplier',
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
          '/api/supplier',
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
              title: `Supplier ${this.name} is created.`,
              icon: 'success'
            });
          } else {
            Swal.fire({
              title: `Supplier ${this.name} is create failed.`,
              icon: 'warning'
            });
          }
          loader.hide();
        })
        .catch(err => {
          Swal.fire({
            title: 'Create Supplier Failed.',
            icon: 'error'
          });
          loader.hide();
        });
    }
  }
};
</script>
