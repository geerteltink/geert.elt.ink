import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    posts: [
      {
        id: '2013-11-05-hello-world',
        title: 'Hello World!',
        summary: 'Hello World! I`m back. Serving this site with GitHub pages with a touch of Jekyll.',
        created: new Date('2013-11-05'),
      },
      {
        id: '2013-12-06-symfony-2-flash-messages',
        title: 'Symfony 2.4 flash messages',
        summary: 'In Symfony 2.4 the session is not always initialized in a template. You need an extra step to detect this.',
        created: new Date('2013-12-06'),
      },
      {
        id: '2014-06-20-jekyll-atom-feed',
        title: 'Jekyll Atom Feed',
        summary: 'A proper atom feed for Jekyll.',
        created: new Date('2014-06-20'),
      },
      {
        id: '2014-10-02-symfony-2-dynamic-router',
        title: 'Symfony 2 Dynamic Router',
        summary: 'There are some dynamic router examples out there for Symfony 2. But most are overly complicated. This is an easy way to load dynamic routes from a database.',
        created: new Date('2014-10-02'),
      },
      {
        id: '2014-11-03-howto-update-teamspeak-3',
        title: 'Howto Update TeamSpeak 3 on Debian',
        summary: 'This is the missing manual for updating TeamSpeak 3 on Debian.',
        created: new Date('2014-11-03'),
      },
      {
        id: '2014-11-04-phpunit-selenium-2',
        title: 'PHPunit and Selenium Server 2',
        summary: 'This is the missing manual for phpunit-selenium.',
        created: new Date('2014-11-04'),
      },
      {
        id: '2014-11-10-using-sismo-as-your-local-continuous-integration-server',
        title: 'Using Sismo as Your Personal CI Server on Windows',
        summary: 'Sismo is a lightweight and easy to install continuous testing server which you can use locally and trigger from a git post-commit hook.',
        created: new Date('2014-11-10'),
      },
      {
        id: '2015-01-24-check-git-status-recursively-on-windows',
        title: 'Check git status recursively on windows',
        summary: 'Easy command to check all git repo`s for changes not committed or pushed in all sub directories.',
        created: new Date('2015-01-24'),
      },
      {
        id: '2015-03-13-grunt-gulp-and-npm',
        title: 'Grunt, Gulp and NPM scripts',
        summary: 'Build tools and task runners.',
        created: new Date('2015-03-13'),
      },
      {
        id: '2015-05-20-git-worklow',
        title: 'Git Workflow',
        summary: 'A git workflow suitable for large projects.',
        created: new Date('2015-05-20'),
      },
      {
        id: '2015-05-21-git-troubleshooting',
        title: 'Git Troubleshooting',
        summary: 'Some ways to resolve git issues.',
        created: new Date('2015-05-21'),
      },
      {
        id: '2015-06-04-symfony-shibboleth-login-the-easy-way',
        title: 'Symfony 2.6 Shibboleth Login - The Easy Way',
        summary: 'Since Symfony 2.6 Shibboleth logins can be added easily with the remote_user security option.',
        created: new Date('2015-06-04'),
      },
      {
        id: '2015-09-07-pimple-3-container-interop',
        title: 'Container-Interop wrapper for Pimple 3.0',
        summary: 'While the Pimple developers are waiting for the PSR it doesn`t mean you have too.',
        created: new Date('2015-09-07'),
      },
      {
        id: '2015-12-09-contributing-to-github-projects',
        title: 'Contributing to a github project',
        summary: 'Keep your github fork in sync.',
        created: new Date('2015-12-09'),
      },
      {
        id: '2015-12-12-setup-doctrine-for-zend-expressive',
        title: 'How to setup doctrine for zend expressive',
        summary: 'Build a Zend Expressive Doctrine factory and cache driver factory.',
        created: new Date('2015-12-12'),
      },
      {
        id: '2015-12-14-phpstorm-container-interop-code-completion',
        title: 'PhpStorm PSR-11 Container Interface Code Completion',
        summary: 'Easily add code completion for PSR-11 Container Interface in PhpStorm.',
        created: new Date('2015-12-14'),
      },
      {
        id: '2015-12-29-zend-expressive-action-factory-one-for-all',
        title: 'Zend Expressive: One Action Factory For All',
        summary: 'Use one action factory for all zend expressive actions.',
        created: new Date('2015-12-29'),
      },
      {
        id: '2015-12-30-psr7-abstract-action-factory-one-for-all',
        title: 'One Abstract Action Factory For All',
        summary: 'Use one abstract action factory for all PSR-7 actions.',
        created: new Date('2015-12-30'),
      },
      {
        id: '2016-02-07-zend-expressive-console-cli-commands',
        title: 'zend-expressive console cli commands',
        summary: 'Use Symfony console for your zend-expressive console commands.',
        created: new Date('2016-02-07'),
      },
      {
        id: '2017-12-03-phpstorm-phpunit-docker-compose-windows',
        title: 'Running PHPUnit in PhpStorm with docker-compose on Windows',
        summary: 'Setup PhpStorm with docker-compose and PHPUnit integration on Windows.',
        created: new Date('2017-12-03'),
      },
      {
        id: '2018-08-26-cqrs-message-validation',
        title: 'CQRS Message Validation',
        summary: 'Validating cqrs command/event/query messages with Assert, Symfony validator and zend-inputvalidator.',
        created: new Date('2018-08-26'),
      },
    ],
  },
  getters: {
    posts: state => {
      return state.posts.sort((a, b) => b.created - a.created);
    },
    post: state => {
      return id => state.posts.filter(post => {
        return post.id === id;
      })[0];
    },
  },
  mutations: {},
  actions: {},
});
