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

```json
{
  "name": "xtreamwayz",
  "private": true,
  "scripts": {
    "setup": "npm run setup:npm -s && npm run setup:jshint -s && npm run setup:less -s && npm run setup:uglify-js -s && npm run setup:bower -s",
    "setup:npm": "npm install npm -g",
    "setup:jshint": "npm install jshint -g",
    "setup:less": "npm install less -g",
    "setup:uglify-js": "npm install uglify-js -g",
    "setup:bower": "npm install bower -g",
    "setup:watch": "npm install -g watch",
    "setup:parallelshell": "npm install -g parallelshell",
    "setup:live-reload": "npm install -g live-reload",
    "setup:kss": "npm install -g kss",
    "update": "npm run update:bower -s",
    "update:bower": "bower install",
    "clean": "rm -rf _sites && rm -rf assets && rm -rf styleguide && mkdir \"assets\" && mkdir \"assets/css\" && mkdir \"assets/img\" && mkdir \"assets/js\"",
    "test": "npm run test:lint -s",
    "test:lint": "jshint src/js/",
    "build": "npm run build:css -s && npm run build:images -s && npm run build:jquery -s  && npm run build:js -s",
    "build:css": "npm run build:stylesheet -s && npm run build:styleguide -s",
    "build:stylesheet": "lessc -x src/less/stylesheet.less > assets/css/stylesheet.css",
    "build:styleguide": "kss-node --source src/less/ --template src/styleguide-template/",
    "build:images": "cp src/img/** assets/img/",
    "build:jquery": "uglifyjs components/jquery/dist/jquery.js -o assets/js/jquery.js -mc",
    "build:js": "cat components/bootstrap/dist/js/bootstrap.js | uglifyjs -o assets/js/bundle.js -mc",
    "prebuild:js": "npm run test:lint -s",
    "livereload": "live-reload _site --port 35729",
    "watch": "parallelshell \"npm run livereload\" \"npm run watch:css\" \"npm run watch:js\"",
    "watch:css": "watch \"npm run build:css\" src/less/",
    "watch:js": "watch \"npm run build:js\" src/js/"
  }
}
```

The main purpose was rebuilding sources on changes and notifying the browser. With mostly unexplained errors from the Jekyll auto generation and sometimes my own coding errors it became a problem restarting the build system all the time. Besides that Jekyll didn't detect changes straight away. Sometimes it even took minutes before it woke up.

## Converting to Gulp

So with the issues in npm and I didn't want to go back to grunt, I gave gulp.js a try. Again I converted all tasks and after some tweaking and speed optimizations, it looks like I've got a pretty stable build system now. It didn't crash yet and the errors are reported nicely. It feels a lot faster than grunt and especially npm.

```javascript
var gulp = require('gulp');
var cp = require('child_process');
var del = require('del');
var concat = require('gulp-concat');
var shell  = require('gulp-shell');
var express = require('express');
var livereload = require('gulp-livereload');
var plumber = require('gulp-plumber');
var notify = require('gulp-notify');
var less = require('gulp-less');
var autoprefixer = require('gulp-autoprefixer');
var minifycss = require('gulp-minify-css');
var imagemin = require('gulp-imagemin');
var jshint = require('gulp-jshint');
var uglify = require('gulp-uglify');
var bundler = process.platform === 'win32' ? 'bundle.bat' : 'bundle';

var onError = function(err) {
    notify.onError({
                title:    'Gulp',
                subtitle: 'Failure!',
                message:  'Error: <%= error.message %>',
                sound:    'Beep'
            })(err);

    this.emit('end');
};

gulp.task('default', ['watch']);

gulp.task('build', ['clean', 'images', 'styles', 'lint', 'scripts', 'styleguide']);

// Run static file server
gulp.task('serve', function () {
    var server = express();
    server.use(express.static('_site'));
    server.listen(3333);
});

// Watch for changed files
gulp.task('watch', ['jekyll', 'serve'], function () {
    // Create LiveReload server
    livereload.listen({ port: 35729, basePath: '_site' });
    // Watch files, reload on change
    gulp.watch('_site/**').on('change', function (file) {
        livereload.changed(file.path);
    });

    gulp.watch('src/images/**/*', ['images']);
    gulp.watch('src/styles/**/*.less', ['styles']);
    gulp.watch('src/scripts/**/*.js', ['scripts']);
    gulp.watch(['src/styles/**/*', 'src/styleguide/**/*'], ['styleguide', 'jekyll']);
    gulp.watch(['*.html', '**/*.html', '**/*.md', '!assets/**', '!src/**', '!_site/**', '!_site/**/*', '!styleguide/**'], ['jekyll']);
});

// Cleanup
gulp.task('clean', function (cb) {
    // Force cleaning first before all other tasks
    del(['_site', 'assets/css/**/*', 'assets/img/**/*', 'assets/js/**/*', 'styleguide'], function (err, deletedFiles) {
        //console.log('Files deleted:', deletedFiles.join(', '));
    });
    cb();
});

// Process images
gulp.task('images', function () {
    return gulp.src('src/images/*')
        .pipe(plumber({errorHandler: onError}))
        .pipe(imagemin({
            progressive: true,
            svgoPlugins: [{removeViewBox: false}]
        }))
        .pipe(gulp.dest('assets/img'))
        .pipe(gulp.dest('_site/assets/img')); // Copy to static dir
});

// Compile style sheets
gulp.task('styles', function () {
    return gulp.src('src/styles/stylesheet.less')
        .pipe(plumber({errorHandler: onError}))
        .pipe(less())
        .pipe(autoprefixer('last 2 version', '> 1%'))
        .pipe(minifycss())
        .pipe(gulp.dest('assets/css'))
        .pipe(gulp.dest('_site/assets/css')); // Copy to static dir
});

// Lint scripts
gulp.task('lint', function () {
    return gulp.src(['gulpfile.js', 'src/js/**/*.js'])
        .pipe(plumber({errorHandler: onError}))
        .pipe(jshint());
});

gulp.task('scripts', ['scripts:jquery', 'scripts:bundle']);

// Compress jquery
gulp.task('scripts:jquery', function() {
    return gulp.src(['components/jquery/dist/jquery.js'])
        .pipe(plumber({errorHandler: onError}))
        .pipe(jshint())
        .pipe(uglify())
        .pipe(gulp.dest('assets/js'))
        .pipe(gulp.dest('_site/assets/js')); // Copy to static dir
});

// Compile all custom scripts and plugins into one bundle
gulp.task('scripts:bundle', function() {
    return gulp.src(['src/scripts/app.js', 'src/scripts/**/*.js'])
        .pipe(plumber({errorHandler: onError}))
        .pipe(jshint())
        .pipe(concat('bundle.js'))
        .pipe(uglify())
        .pipe(gulp.dest('assets/js'))
        .pipe(gulp.dest('_site/assets/js')); // Copy to static dir
});

// Build styleguide docs
gulp.task('styleguide', shell.task([
        // kss-node [source folder of files to parse] [destination folder] --template [location of template files]
        'kss-node <%= source %> <%= destination %> --template <%= template %>'
    ], {
        templateData: {
            template:     'src/styleguide',
            source:       'src/styles',
            destination:  'styleguide'
        }
    }
));

// Compile pages with Jekyll
gulp.task('jekyll', function () {
     return cp.spawn(bundler, ['exec', 'jekyll', 'build', '--drafts', '--quiet'], {stdio: 'inherit'});
});
```

So far I like gulp.js. I like it better than the Grunt config file and it's more readable than npm scripting.
