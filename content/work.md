---
title: 'My Work'
date: '1999-09-09'
menu:
  main:
    name: 'Work'
    weight: 10
---

Want to see what I am made of? Here you go, I'm proud of the work I've done.
It's only a tiny collection of the most interesting projects.

<div class="card-deck">
  <div class="card mb-3">
    <img loading="eager" class="card-img-top" alt="Screenshot pakyruiz.com" src="{{< imgurl "/img/screenshot-pakyruiz-com.jpg" >}}" />
    <div class="card-body">
      <h2 class="card-title">pakyruiz.com</h2>
      <p class="card-text">
        This a perfect example of where not to use javascript. The site is only
        built with what is needed: html and css.
      </p>
    </div>
    <div class="card-footer">
      <ul class="list-inline">
        <li class="list-inline-item badge badge-light">
          <a href="https://www.pakyruiz.com/" aria-label="pakyruiz.com">Visit project</a>
        </li>
        <li class="list-inline-item badge badge-danger">HTML</li>
        <li class="list-inline-item badge badge-danger">CSS</li>
        <li class="list-inline-item badge badge-info">Hugo</li>
        <li class="list-inline-item badge badge-info">Netlify</li>
      </ul>
    </div>
  </div>

  <div class="card mb-3">
    <img loading="eager" class="card-img-top" alt="Screenshot linksbek.nl" src="{{< imgurl "/img/screenshot-linksbek-nl.jpg" >}}" />
    <div class="card-body">
      <h2 class="card-title">linksbek.nl</h2>
      <p class="card-text">
        This site is a long term project. Started back in 2002 it was built on
        top of a custom cms. Later it was converted to wordpress with a custom
        theme. In 2019 GitHub Actions were available which makes it possible to
        schedule rebuilds easily and together with Hugo you can schedule posts.
        Enough reason to convert it to a static site, move it to netlify and
        trigger daily builds with GitHub Actions.
      </p>
    </div>
    <div class="card-footer">
      <ul class="list-inline">
        <li class="list-inline-item badge badge-light">
          <a href="https://linksbek-nl.netlify.com/" aria-label="linksbek.nl">Visit project</a>
        </li>
        <li class="list-inline-item badge badge-danger">HTML</li>
        <li class="list-inline-item badge badge-danger">CSS</li>
        <li class="list-inline-item badge badge-info">Hugo</li>
        <li class="list-inline-item badge badge-info">Netlify</li>
        <li class="list-inline-item badge badge-info">GitHub Actions</li>
      </ul>
    </div>
  </div>

  <div class="card mb-3">
    <img loading="lazy" class="card-img-top" alt="Screenshot ai-total.nl" src="{{< imgurl "/img/screenshot-ai-total-nl.jpg" >}}" />
    <div class="card-body">
      <h2 class="card-title">ai-total.nl</h2>
      <p class="card-text">
        This custom build webshop ran on PHP a while back. However the
        filtering of products was done server side and used too many resources.
        The site was converted to a Vue.js app and the filtering is now done
        client side. The server still uses PHP for an API and admin pages.
      </p>
    </div>
    <div class="card-footer">
      <ul class="list-inline">
        <li class="list-inline-item badge badge-light">
          <a href="https://www.ai-total.nl/" aria-label="ai-total.nl">Visit project</a>
        </li>
        <li class="list-inline-item badge badge-danger">PHP</li>
        <li class="list-inline-item badge badge-danger">Vue.js</li>
        <li class="list-inline-item badge badge-info">
          <abbr title="Progressive Web Application">PWA</abbr>
        </li>
        <li class="list-inline-item badge badge-info">Netlify</li>
        <li class="list-inline-item badge badge-info">API</li>
        <li class="list-inline-item badge badge-info">
          <abbr title="Self managed VPS">VPS</abbr>
        </li>
      </ul>
    </div>
  </div>

  <div class="card mb-3">
    <img loading="lazy" class="card-img-top" alt="Screenshot auctions" src="{{< imgurl "/img/screenshot-auctions.jpg" >}}" />
    <div class="card-body">
      <h2 class="card-title">Custom build auctions</h2>
      <p class="card-text">
        Built with PHP this app supports multiple auction sites and auction
        types (dutch, english, timed, live). It has mass mailing, scripts to
        automatically close and handle auctions, bid master screen for live
        auctions and much more.
      </p>
    </div>
    <div class="card-footer">
      <ul class="list-inline">
        <li class="list-inline-item badge badge-danger">PHP</li>
        <li class="list-inline-item badge badge-danger">JavaScript</li>
        <li class="list-inline-item badge badge-info">API</li>
        <li class="list-inline-item badge badge-info">
          <abbr title="Self managed VPS">VPS</abbr>
        </li>
      </ul>
    </div>
  </div>
</div>
