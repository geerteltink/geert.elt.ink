<template>
  <footer class="app-footer" itemscope itemtype="http://schema.org/WPFooter">
    <div class="container d-flex flex-wrap justify-content-between">
      <ul class="list-unstyled">
        <li>
          <h3 class="h5 text-uppercase">
            <router-link to="/">xtreamwayz</router-link>
          </h3>
        </li>
        <li>
          <router-link to="/blog">Blog</router-link>
        </li>
        <li>
          <svg class="icon icon-github">
            <use xlink:href="/img/icons.svg#icon-github"></use>
          </svg>
          <a href="https://github.com/xtreamwayz" title="Follow me on GitHub">
            GitHub
          </a>
        </li>
      </ul>
      <ul class="list-unstyled">
        <li>
          <h3 class="h5 text-uppercase">Follow</h3>
        </li>
        <li>
          <svg class="icon icon-twitter">
            <use xlink:href="/img/icons.svg#icon-twitter"></use>
          </svg>
          <a href="https://twitter.com/xtreamwayz" title="Contact me on Twitter">
            @xtreamwayz
          </a>
        </li>
        <li>
          <svg class="icon icon-rss">
            <use xlink:href="/img/icons.svg#icon-rss"></use>
          </svg>
          <a href="/feed" title="xtreamwayz Blog Feed">
            Blog Feed
          </a>
        </li>
      </ul>
      <ul class="list-unstyled text-xs-center text-md-right">
        <li>
          &copy; 2005 - {{ year }} by Geert Eltink.
        </li>
        <li>
          All rights reserved.
        </li>
        <li>
          <small class="font-italic text-muted" :title="revision">v{{ version }} / {{ revShort }}</small>
        </li>
      </ul>
    </div>
  </footer>
</template>

<script>
  export default {
    name: 'AppFooter',

    data: function () {
      return {
        version: `${process.env.VUE_APP_VERSION}`,
        revision: `${process.env.VUE_APP_REV}`,
        revShort: `${process.env.VUE_APP_REV}`.substring(0,8),
      };
    },

    computed: {
      year: () => {
        return (new Date()).getFullYear();
      },
    },
  };
</script>

<style scoped lang="scss">
  @import "../styles/config";

  .app-footer {
    padding: $spacer 0;

    color: $footer-color;
    background-color: $footer-bg;

    a,
    .breadcrumbs,
    .linklist {
      color: $footer-color;
    }

    p {
      margin-bottom: 0;
    }
  }
</style>
