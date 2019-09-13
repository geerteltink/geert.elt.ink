import Vue from 'vue';
import Meta from 'vue-meta';
import Router from 'vue-router';
import Home from './views/Home.vue';

Vue.use(Router);
Vue.use(Meta);

export default new Router({
  mode: 'history',
  base: process.env.BASE_URL,

  routes: [
    {
      path: '/',
      name: '/',
      component: Home,
    },
    {
      path: '/blog',
      name: 'blog',
      component: () => import(/* webpackChunkName: "blog" */ './views/Blog.vue'),
    },
    {
      path: '/blog/:id',
      name: 'blog-post',
      component: () => import(/* webpackChunkName: "blog-post" */ './views/BlogPost.vue'),
    },
    {
      path: '/code',
      name: 'code',
      component: () => import(/* webpackChunkName: "code" */ './views/Code.vue'),
    },
    {
      path: '/contact',
      name: 'contact',
      component: () => import(/* webpackChunkName: "contact" */ './views/Contact.vue'),
    },
    { path: '/404', component: () => import(/* webpackChunkName: "not-found" */ './views/NotFound.vue') },
    { path: '*', redirect: '/404' },
  ],

  scrollBehavior() {
    return { x: 0, y: 0 };
  },
});
