<template>
  <b-container fluid class="root-container">
    <top-navbar></top-navbar>
    <b-container fluid="xl" class="main-container  ml-auto mr-auto py-4">
      <b-row class="pt-4">
        <b-col md="6">
          <b-dropdown
            id="dropdown-1"
            text="Select Add"
            class="m-md-2"
            variant="outline-primary"
          >
            <b-dropdown-item @click="$router.push({ name: 'create-new-item' })"
              >Add Item</b-dropdown-item
            >
            <b-dropdown-item
              @click="$router.push({ name: 'create-payment-method' })"
              >Add Payment Method</b-dropdown-item
            >
            <b-dropdown-item
              @click="$router.push({ name: 'create-expenses-type' })"
              >Add Expenses Type</b-dropdown-item
            >
          </b-dropdown>
        </b-col>
      </b-row>
    </b-container>
  </b-container>
</template>

<script>
import TopNavbar from '@/sharedComponents/top-navbar.vue';

export default {
  components: {
    TopNavbar
  },
  name: 'add-item-select',
  data() {
    return {};
  },
  methods: {}
};
</script>

<style lang="scss">
.btn-prev {
  cursor: pointer;
}
</style>
