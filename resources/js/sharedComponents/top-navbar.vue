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
      <b-navbar-nav>
        <b-nav-item href="/key-in">Key In</b-nav-item>
      </b-navbar-nav>

      <b-navbar-nav class="ml-auto">
        <template v-if="check">
          <b-nav-item-dropdown right>
            <!-- Using 'button-content' slot -->
            <template #button-content>
              <em>{{ currentUser.username }}</em
              ><br />
              <span style="fontSize:0.8rem; opacity: 0.8;">
                {{ currentUser.user_type }}
              </span>
            </template>
            <b-dropdown-item @click="handleClickSignOut"
              >Sign Out</b-dropdown-item
            >
          </b-nav-item-dropdown>
        </template>
        <template v-else>
          <b-nav-item @click="handleClickLogin">Login</b-nav-item>
          <b-nav-item @click="handleClickRegister">Register</b-nav-item>
        </template>
      </b-navbar-nav>
    </b-collapse>
  </b-navbar>
</template>

<script>
import { mapGetters } from 'vuex';
import { LOGOUT } from '@/services/store/auth.module';

export default {
  name: 'top-navbar',
  computed: {
    ...mapGetters(['currentUser', 'check'])
  },
  methods: {
    handleClickLogin() {
      this.$router.push({ name: 'login' });
    },
    handleClickRegister() {
      this.$router.push({ name: 'register' });
    },
    handleClickSignOut() {
      this.$store.dispatch(LOGOUT);
      this.$router.push({ name: 'login' });
    }
  }
};
</script>

<style lang="scss">
#pr-navbar {
  .navbar-nav {
    .nav-link {
      color: white;
      &.dropdown-toggle {
        padding-right: 20px;
        &:after {
          position: absolute;
          right: 0;
        }
      }
    }
  }
  .nav-link {
    color: white;
  }
}
</style>
