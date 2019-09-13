---
id: 2014-06-20-jekyll-atom-feed
title: Jekyll Atom Feed
summary: A proper atom feed for Jekyll.
draft: false
public: true
published: 2014-06-20T10:00:00+01:00
modified: 2015-02-21T15:39:00+01:00
tags:
    - Jekyll
---

I read a twitter post today about the lack of rss feeds on blogs. Then I realized this one was missing it too. Google is your friend and pretty fast I found an example. When I tried to throw it at the W3C feed validation service it gave some errors. Guess what? They mixed rss and atom code together. Those are two different specifications and you can't make up your own. Well, actually you can, but then you should use a different xml namespace.

For this to work you need to update your default template e.g. `/_layouts/default.html` and add the line listed below.

```html
<!doctype html>
<html lang="en">
<head>
    <!-- Other head stuff here -->
    <link rel="alternate" type="application/atom+xml" title="News Feed" href="{{ site.url }}/site/feed.xml" />
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
    <title>{{ site.name | xml_escape }}</title>
    <subtitle type="text">{{ site.description | xml_escape }}</subtitle>
    <updated>{{ site.time | date_to_xmlschema }}</updated>
    <link type="application/atom+xml" href="{{ site.url | uri_escape }}/site/feed.xml" rel="self" />
    <link type="text/html" href="{{ site.url | uri_escape }}" rel="alternate"/>
    <id>{{ site.url | uri_escape }}/</id>
    <generator uri="http://jekyllrb.com/">Jekyll</generator>
    <rights>Copyright (c) {{ site.time | date: "%Y" }} Geert Eltink. All Rights Reserved.</rights>
    {% for post in site.posts limit:10 %}
    <entry>
        <title type="html"><![CDATA[{{ post.title }}]]></title>
        <link href="{{ site.url | uri_escape }}{{ post.url | uri_escape }}" />
        <id>{{ site.url | uri_escape }}{{ post.url | uri_escape }}</id>
        <published>{{ post.date | date_to_xmlschema }}</published>
        <updated>{{ post.date | date_to_xmlschema }}</updated>
        <author>
            <name>Geert Eltink</name>
            <uri>{{ site.url | uri_escape }}</uri>
        </author>
        <summary type="html"><![CDATA[{{ post.summary }}]]></summary>
    </entry>
    {% endfor %}
</feed>
```

And here you have the result: <a href="http://validator.w3.org/feed/check.cgi?url={{ site.url | uri_escape }}/site/feed.xml">validator.w3.org</a>
