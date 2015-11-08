---
id: 2014-06-20-jekyll-atom-feed
title: Symfony 2.4 flash messages
summary: In Symfony 2.4 the session is not always initialized in a template. You need an extra step to detect this.
draft: false
public: true
created: 2014-06-20T10:00:00+01:00
updated: 2015-02-21T15:39:00+01:00
tags:
    - Jekyll
---

I read a twitter post today about the lack of rss feeds on blogs. Then I realized this one was missing it too. Google is your friend and pretty fast I found an example. When I tried to throw it at the W3C feed validation service it gave some errors. Guess what? They mixed rss and atom code together. Those are two different specifications and you can't make up your own. Well, actually you can, but then you should use a different xml namespace.

For this to work you need to update your default template e.g. `/_layouts/default.html` and add the line listed below.

<gist data-id="56e7222e9a8200577518" data-file="default.html"></gist>

Place the `feed.xml` file somewhere in your path. Mine is located in `/sites/feed.xml`. If you place it somewhere else make sure you change the line you just added to `default.html` accordingly as well as line 9 in `feed.xml`.

<gist data-id="56e7222e9a8200577518" data-file="feed.xml"></gist>

And here you have the result: <a href="http://validator.w3.org/feed/check.cgi?url={{ site.url | uri_escape }}/site/feed.xml">validator.w3.org</a>
