<template>
  <v-app-bar app color="dark" dark>
    <div class="d-flex align-center">
      <v-toolbar-title>Downstream</v-toolbar-title>

      <v-btn class="pa-1" href="https://github.com/travierm/downstream" target="_blank" text fab small>
        <v-icon>mdi-github</v-icon>
      </v-btn>
    </div>

    <v-spacer />

    <v-autocomplete class="main-search" solo dense flat/>

    <v-btn class="mr-2 ml-2" rounded text>
      Activity
      <v-badge content="6" class="ml-2 mr-2" color="#1E1E1E"></v-badge>
    </v-btn>
    <v-btn class="mr-2 ml-2" rounded text>Admin</v-btn>
    <v-btn class="mr-2 ml-2" rounded text>Collection</v-btn>
    <v-btn class="mr-2 ml-2" rounded text>
      Discover
      <v-badge content="2" class="ml-2 mr-2" color="#1E1E1E"></v-badge>
    </v-btn>

    <v-menu offset-y >
      <template v-slot:activator="{ on, attrs }">
        <v-btn text v-bind="attrs" v-on="on" rounded>
          <v-icon>mdi-account</v-icon>
        </v-btn>
      </template>
      <v-list class="pa-2">
        <v-list-item>
          <v-icon class="pr-4">mdi-account-circle-outline</v-icon>
          <v-list-item-title>Profile</v-list-item-title>
        </v-list-item>
        <v-list-item>
          <v-icon class="pr-4">mdi-cog</v-icon>
          <v-list-item-title>Settings</v-list-item-title>
        </v-list-item>
        <v-list-item>
          <v-icon class="pr-4">mdi-logout</v-icon>
          <v-list-item-title>Logout</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-menu>

  </v-app-bar>
</template>

<script>
export default {
  name: 'NavBar',
  components: {
  },
  data: () => {
    return {}
  },
  mounted() {
  },
  methods:{
  }
}
</script>

<style scoped>
.main-search {
  padding-top: 22px !important;
}
</style>
