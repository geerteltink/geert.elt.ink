<template>
  <div>
    <h1 class="h2 text-muted m-t-3">Page Not Found</h1>
    <h2 class="h1 m-y-2">Oops! Looks like you followed a bad link.</h2>
    <p>
      If you think this is a problem on our site, please
      <router-link to="/contact">tell us</router-link>
      .
    </p>
    <p>
      You are looking for something that doesn't exist or may have moved.
      Check out our
      <router-link to="/">home page</router-link>
      instead.
    </p>
  </div>
</template>

<script>
  export default {
    name: 'NotFound',
    metaInfo: {
      title: '404 Not Found',
    },
  };
</script>
