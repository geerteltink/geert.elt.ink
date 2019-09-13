process.env.VUE_APP_VERSION = require('./package.json').version;
process.env.VUE_APP_REV = process.env.COMMIT_REF || 'dev';

/*
if (!dev) {
    const generateRSS = require('./.next/server/scripts/build-rss.js')
        .default;
    generateRSS(outDir);
}
*/

const manifestJSON = require('./public/manifest.json');

module.exports = {
  // https://github.com/vuejs/vue-cli/tree/dev/packages/@vue/cli-plugin-pwa#readme
  pwa: {
    name: manifestJSON.short_name,
    themeColor: manifestJSON.theme_color,
    msTileColor: manifestJSON.theme_color,
    appleMobileWebAppCapable: 'yes',
    appleMobileWebAppStatusBarStyle: manifestJSON.theme_color,

    // configure the workbox plugin
    //workboxPluginMode: 'InjectManifest',
    workboxOptions: {
      // these options encourage the ServiceWorkers to get in there fast
      // and not allow any straggling "old" SWs to hang around
      skipWaiting: true,
      clientsClaim: true,
      offlineGoogleAnalytics: true,
      // swSrc is required in InjectManifest mode.
      //swSrc: 'src/service-worker.js',
      // ...other Workbox options...
    },
  },
};
