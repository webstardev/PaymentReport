<template>
  <b-container fluid class="root-container">
    <top-navbar></top-navbar>
    <b-container fluid="xl" class="main-container ml-auto mr-auto py-4">
      <b-row class="pt-4" v-if="curStep === steps.SELECT_ADD_TYPE">
        <b-col md="6">
          <b-dropdown
            id="dropdown-1"
            text="Add new item"
            class="m-md-2"
            variant="outline-primary"
          >
            <b-dropdown-item @click="curStep = steps.ADD_PAYMENT_METHOD"
              >Add Payment Method</b-dropdown-item
            >
            <b-dropdown-item @click="curStep = steps.ADD_EXPENSES_TYPE"
              >Add Expenses Type</b-dropdown-item
            >
          </b-dropdown>
        </b-col>
      </b-row>

      <b-form
        @submit="onSubmit"
        class="pt-4"
        v-if="curStep === steps.ADD_PAYMENT_METHOD"
      >
        <b-row>
          <b-col md="4">
            <a class="btn-prev" @click="curStep = steps.SELECT_ADD_TYPE">{{
              `< Prev`
            }}</a>
          </b-col>
        </b-row>
        <b-row class="mt-2">
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
      <b-form
        @submit="onSubmit"
        class="pt-4"
        v-if="curStep === steps.ADD_EXPENSES_TYPE"
      >
        <b-row>
          <b-col md="4">
            <a class="btn-prev" @click="curStep = steps.SELECT_ADD_TYPE">{{
              `< Prev`
            }}</a>
          </b-col>
        </b-row>
        <b-row class="mt-2">
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
import { ADD_NEW_ITEM_STEPS } from '@/constants';
import TopNavbar from '@/sharedComponents/top-navbar.vue';

export default {
  components: {
    TopNavbar
  },
  name: 'add-item-select',
  data() {
    return {
      steps: ADD_NEW_ITEM_STEPS,
      curStep: ADD_NEW_ITEM_STEPS.SELECT_ADD_TYPE,
      name: ''
    };
  },
  methods: {
    onSubmit(event) {
      event.preventDefault();
      const loader = this.$loading.show();
      if (this.curStep === ADD_NEW_ITEM_STEPS.ADD_PAYMENT_METHOD) {
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
      } else if (this.curStep === ADD_NEW_ITEM_STEPS.ADD_EXPENSES_TYPE) {
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
  }
};
</script>

<style lang="scss">
.btn-prev {
  cursor: pointer;
}
</style>
