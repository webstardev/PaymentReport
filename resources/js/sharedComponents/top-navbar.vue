<template>
  <b-navbar
    id="pr-navbar"
    toggleable="lg"
    type="dark"
    variant="info"
    fixed="top"
  >
    <b-navbar-brand>
      <router-link to="/">Payment Report</router-link>
    </b-navbar-brand>

    <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

    <b-collapse id="nav-collapse" is-nav>
      <b-navbar-nav class="ml-4">
        <router-link
          to="/income-key-in"
          :class="`${checkActiveMenu('income-key-in') && 'active'} mx-3`"
          >Income-Key in</router-link
        >
        <router-link
          to="/expenses-key-in"
          :class="`${checkActiveMenu('expenses-key-in') && 'active'} mx-3`"
          >Expenses Key in</router-link
        >
        <router-link
          :class="`${checkActiveMenu('create-new-item') && 'active'} mx-3`"
          to="/create-new-item"
          >Create New Item
        </router-link>
        <template v-if="currentUser.user_type !== userType.VIEW">
          <router-link
            :class="`${checkActiveMenu('income-report') && 'active'} mx-3`"
            to="/income-report"
            >Income Reports</router-link
          >

          <router-link
            :class="`${checkActiveMenu('expenses-report') && 'active'} mx-3`"
            to="/expenses-report"
          >
            Expenses Reports
          </router-link>
          <router-link
            :class="`${checkActiveMenu('expenses-india') && 'active'} mx-3`"
            to="/expenses-india"
          >
            Expenses India
          </router-link>
          <router-link
            :class="`${checkActiveMenu('cashflow-report') && 'active'} mx-3`"
            to="/cashflow-report"
          >
            CashFlow Report
          </router-link>
        </template>
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
            <template v-if="currentUser.user_type === userType.ADMIN">
              <b-dropdown-item @click="$router.push({ name: 'create-user' })"
                >Create User</b-dropdown-item
              >
              <b-dropdown-item
                @click="$router.push({ name: 'create-payment-method' })"
                >Create Payment Method</b-dropdown-item
              >
              <b-dropdown-item
                @click="$router.push({ name: 'create-expenses-type' })"
                >Create Expenses Type</b-dropdown-item
              >
            </template>
            <b-dropdown-item @click="handleClickSignOut"
              >Sign out</b-dropdown-item
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
import { USER_TYPE } from '@/constants';

export default {
  name: 'top-navbar',
  data() {
    return {
      userType: USER_TYPE
    };
  },
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
    },
    checkActiveMenu(name) {
      const routerName = this.$route.name;
      if (routerName === name) return true;
      else return false;
    }
  }
};
</script>

<style lang="scss">
#pr-navbar {
  .navbar-brand {
    a {
      color: white;
      font-size: 1.5rem;
      text-decoration: none;
    }
  }
  .navbar-nav {
    a {
      color: white;
      text-decoration: none;
      &.dropdown-toggle {
        padding-right: 20px;
        &:after {
          position: absolute;
          right: 0;
        }
      }
      &.active {
        border-bottom: 3px solid white;
      }
    }
  }

  .b-nav-dropdown {
    .dropdown-menu {
      a {
        color: rgb(33, 37, 41);
      }
    }
  }
}
</style>
