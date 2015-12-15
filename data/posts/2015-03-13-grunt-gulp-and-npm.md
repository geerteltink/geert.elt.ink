---
id: 2015-03-13-grunt-gulp-and-npm
title: Grunt, Gulp and NPM scripts
summary: Build tools and task runners.
draft: false
public: true
published: 2015-03-13T10:00:00+01:00
modified: false
tags:
    - nodejs
    - npm
    - grunt
    - gulp
---

Two years ago I ran into Grunt and started using it as my preferred build tool. The more I used it and the bigger the projects became, the more I screamed for a better tool. The configuration gets so complicated. I read a bit about Gulp but didn't want to convert all projects again. And then recently I read an article from Keith Cirkel about [how to use npm as a build tool](http://blog.keithcirkel.co.uk/how-to-use-npm-as-a-build-tool/). It was really interested and gave it a try.

## NPM scripting

I converted the Grunt tasks and started to explore npm as a build tool. At first everything was fine but recently I started experimenting with living styleguides and KSS. Using npm scripts became slow and it crashes on errors. I haven't found a solution for catching the errors yet. In case you are wondering, here is the code:

<gist data-id="e28c574c466d546d0c6f" data-file="package.json"></gist>

The main purpose was rebuilding sources on changes and notifying the browser. With mostly unexplained errors from the Jekyll auto generation and sometimes my own coding errors it became a problem restarting the build system all the time. Besides that Jekyll didn't detect changes straight away. Sometimes it even took minutes before it woke up.

## Converting to Gulp

So with the issues in npm and I didn't want to go back to grunt, I gave gulp.js a try. Again I converted all tasks and after some tweaking and speed optimizations, it looks like I've got a pretty stable build system now. It didn't crash yet and the errors are reported nicely. It feels a lot faster than grunt and especially npm.

<gist data-id="e28c574c466d546d0c6f" data-file="gulpfile.js"></gist>

So far I like gulp.js. I like it better than the Grunt config file and it's more readable than npm scripting.
