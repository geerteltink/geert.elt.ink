<template>
  <div>
    <div class="flex-container" style="margin-top:3rem;margin-bottom:3rem;">
      <div class="media">
        <img class="mr-3 rounded-circle" height="96" width="96" src="../assets/avatar.jpg" />
        <div class="media-body">
          <h2 class="mt-0">Web Developer</h2>
          <p class="h5">
            Backend and frontend developer (PHP/API/UI/UX/JavaScript).<br />
            Open source contributor and maintainer.
          </p>
          <p class="text-muted">
            #full-stack #web #developer
          </p>
        </div>
      </div>
    </div>
    <div class="card-deck-wrapper">
      <div class="card-deck">
        <div class="card mb-3" style="min-width: 256px;">
          <div class="card-body">
            <h4 class="card-title text-xs-center">
              <svg class="icon icon-rss">
                <use xlink:href="/img/icons.svg#icon-design"></use>
              </svg>
              Design
            </h4>
            <p class="card-text">
              Great looking designs will be created which will meet your expectations. On a regular basis
              they will be reviewed with you. Responsive web design (RWD) is something we take seriously
              and will use by default.
            </p>
          </div>
        </div>
        <div class="card mb-3" style="min-width: 256px;">
          <div class="card-body">
            <h4 class="card-title text-xs-center">
              <svg class="icon icon-rss">
                <use xlink:href="/img/icons.svg#icon-code"></use>
              </svg>
              Development
            </h4>
            <p class="card-text">
              Whether it's the front-end, back-end or both, we will use the latest industry standards and
              techniques.
            </p>
          </div>
        </div>
        <div class="card mb-3" style="min-width: 256px;">
          <div class="card-body">
            <h4 class="card-title text-xs-center">
              <svg class="icon icon-rss">
                <use xlink:href="/img/icons.svg#icon-upload"></use>
              </svg>
              Deployment
            </h4>
            <p class="card-text">
              We can deploy the code for you, setup the server for you and even maintain it. Also
              performing regular content updates can be done for you.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'Home',
    metaInfo: {
      title: 'I Am',
    },
  };
</script>
