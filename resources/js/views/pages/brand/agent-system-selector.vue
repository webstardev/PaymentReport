<template>
  <b-form @submit="onSubmit">
    <b-row>
      <b-col md="4">
        <a class="btn-prev" @click="gotoPrev">{{ `< Prev` }}</a>
      </b-col>
    </b-row>
    <b-row class="pt-4">
      <b-col md="4">
        <b-form-group label="Agent System:" label-for="agent-system">
          <b-form-select id="agent-system" v-model="agentSystem" required>
            <option value="" disabled :selected="!agentSystem">
              Select agent system
            </option>
            <option
              v-for="option in Object.keys(agentSystemOptions)"
              :key="option"
              :value="agentSystemOptions[option].value"
            >
              {{ agentSystemOptions[option].title }}
            </option>
          </b-form-select>
        </b-form-group>
      </b-col>
    </b-row>
    <b-row>
      <b-col md="4">
        <b-button type="submit" variant="primary">Next</b-button>
      </b-col>
    </b-row>
  </b-form>
</template>

<script>
import { AGENT_SYSTEM } from '@/constants.js';

export default {
  name: 'agent-system-selector',
  props: {
    curAgentySystem: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      agentSystem: '',
      agentSystemOptions: AGENT_SYSTEM
    };
  },
  created() {
    this.agentSystem = this.curAgentySystem;
  },
  methods: {
    gotoPrev() {
      this.$emit('gotoPrev');
    },
    onSubmit(event) {
      event.preventDefault();
      if (this.agentSystem !== this.curAgentySystem)
        this.$emit('gotoNext', { agent_system: this.agentSystem, name: '' });
      else this.$emit('gotoNext', { agent_system: this.agentSystem });
    }
  }
};
</script>
