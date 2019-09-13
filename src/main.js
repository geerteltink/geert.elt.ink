import * as Sentry from '@sentry/browser';
import Vue from 'vue';
import App from './App.vue';
import './registerServiceWorker';
import router from './router';
import store from './store';

Vue.config.productionTip = false;

if (process.env.NODE_ENV === 'production') {
  Sentry.init({
    dsn: 'https://a16dcb199b9e4ad59a4da59a5fe092a0@sentry.io/1315496',
    release: `${process.env.VUE_APP_REV}`,
    integrations: [new Sentry.Integrations.Vue({ Vue })],
    beforeSend(event) {
      // Check if it is an exception, if so, show the report dialog
      if (event.exception) {
        Sentry.showReportDialog();
      }
      return event;
    },
  });
}

new Vue({
  router,
  store,
  render: h => h(App),
}).$mount('#app');
