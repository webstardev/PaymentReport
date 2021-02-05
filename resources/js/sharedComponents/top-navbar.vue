<template>
  <b-navbar
    id="pr-navbar"
    toggleable="lg"
    type="dark"
    variant="info"
    fixed="top"
  >
    <b-navbar-brand href="/">Payment Report</b-navbar-brand>

    <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

    <b-collapse id="nav-collapse" is-nav>
      <b-navbar-nav class="ml-4">
        <b-nav-item href="/brand">Brand</b-nav-item>
      </b-navbar-nav>

      <b-navbar-nav class="ml-auto">
        <!-- <b-nav-item-dropdown text="Lang" right>
          <b-dropdown-item href="#">EN</b-dropdown-item>
          <b-dropdown-item href="#">HR</b-dropdown-item>
        </b-nav-item-dropdown> -->
        <b-nav-item v-b-modal.login-modal>Login</b-nav-item>
        <b-nav-item v-b-modal.register-modal>Register</b-nav-item>

        <b-nav-item-dropdown right>
          <!-- Using 'button-content' slot -->
          <template #button-content>
            <em>User</em>
          </template>
          <b-dropdown-item href="#" disabled>Profile</b-dropdown-item>
          <b-dropdown-item href="#">Sign Out</b-dropdown-item>
        </b-nav-item-dropdown>
      </b-navbar-nav>
    </b-collapse>

    <login-modal></login-modal>
  </b-navbar>
</template>

<script>
import LoginModal from './login-modal.vue';
export default {
  name: 'top-navbar',
  components: { LoginModal }
};
</script>

<style scoped lang="scss">
#pr-navbar {
  .nav-link {
    color: white;
  }
}
</style>
