<template>
  <div id="app" class="app">
    <app-header />
    <app-hero />
    <main class="app-content container" itemscope itemprop="mainContentOfPage">
      <router-view></router-view>
    </main>
    <app-footer />
  </div>
</template>

<script>
  import AppFooter from '@/components/AppFooter';
  import AppHeader from '@/components/AppHeader';
  import AppHero from '@/components/AppHero';
  import router from '@/router';
  import Vue from 'vue';
  import VueAnalytics from 'vue-analytics';

  Vue.use(VueAnalytics, {
    id: 'UA-56579105-2',
    router,
    debug: {
      enabled: process.env.NODE_ENV !== 'production',
      sendHitTask: process.env.NODE_ENV === 'production',
    },
  });

  export default {
    name: 'Home',

    components: {
      AppHeader,
      AppFooter,
      AppHero,
    },

    metaInfo: {
      title: 'I am',
      titleTemplate: '%s - xtreamwayz',
      meta: [
        { vmid: 'description', name: 'description', content: 'A web developers playground, notes, and thoughts.' },
        { vmid: 'version', name: 'version', content: `${process.env.VUE_APP_VERSION}` },
        { vmid: 'revision', name: 'revision', content: `${process.env.VUE_APP_REV}` },
      ],
    },
  };
</script>

<style lang="scss">
  @import "styles/main";
</style>
