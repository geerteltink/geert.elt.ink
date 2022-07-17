---
title: Jekyll Atom Feed
summary: A proper atom feed for Jekyll.
date: 2014-06-20
tags:
  - Jekyll
---

I read a twitter post today about the lack of rss feeds on blogs. Then I realized this one was missing it too. Google is your friend and pretty fast I found an example. When I tried to throw it at the W3C feed validation service it gave some errors. Guess what? They mixed rss and atom code together. Those are two different specifications and you can't make up your own. Well, actually you can, but then you should use a different xml namespace.

For this to work you need to update your default template e.g. `/_layouts/default.html` and add the line listed below.

```html
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Other head stuff here -->
    <link
      rel="alternate"
      type="application/atom+xml"
      title="News Feed"
      href="&#123;&#123; site.url &#125;&#125;/site/feed.xml"
    />
  </head>
  <body>
    <!-- Content -->
  </body>
</html>
```

Place the `feed.xml` file somewhere in your path. Mine is located in `/sites/feed.xml`. If you place it somewhere else make sure you change the line you just added to `default.html` accordingly as well as line 9 in `feed.xml`.

```xml
---
layout: none
---
<?xml version="1.0" encoding="utf-8"?>
<feed xmlns="http://www.w3.org/2005/Atom" xml:lang="en-US">
    <title>&#123;&#123; site.name | xml_escape &#125;&#125;</title>
    <subtitle type="text">&#123;&#123; site.description | xml_escape &#125;&#125;</subtitle>
    <updated>&#123;&#123; site.time | date_to_xmlschema &#125;&#125;</updated>
    <link type="application/atom+xml" href="&#123;&#123; site.url | uri_escape &#125;&#125;/site/feed.xml" rel="self" />
    <link type="text/html" href="&#123;&#123; site.url | uri_escape &#125;&#125;" rel="alternate"/>
    <id>&#123;&#123; site.url | uri_escape &#125;&#125;/</id>
    <generator uri="http://jekyllrb.com/">Jekyll</generator>
    <rights>Copyright (c) &#123;&#123; site.time | date: "%Y" &#125;&#125; Geert Eltink. All Rights Reserved.</rights>
    &#123;% for post in site.posts limit:10 %&#125;
    <entry>
        <title type="html"><![CDATA[&#123;&#123; post.title &#125;&#125;]]></title>
        <link href="&#123;&#123; site.url | uri_escape &#125;&#125;&#123;&#123; post.url | uri_escape &#125;&#125;" />
        <id>&#123;&#123; site.url | uri_escape &#125;&#125;&#123;&#123; post.url | uri_escape &#125;&#125;</id>
        <published>&#123;&#123; post.date | date_to_xmlschema &#125;&#125;</published>
        <updated>&#123;&#123; post.date | date_to_xmlschema &#125;&#125;</updated>
        <author>
            <name>Geert Eltink</name>
            <uri>&#123;&#123; site.url | uri_escape &#125;&#125;</uri>
        </author>
        <summary type="html"><![CDATA[&#123;&#123; post.summary &#125;&#125;]]></summary>
    </entry>
    &#123;% endfor %&#125;
</feed>
```
