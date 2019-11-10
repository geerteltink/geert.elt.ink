---
title: "Project documentation with Hugo Modules"
summary: "Generating multi project documentation with Hugo Modules blazing fast"
date: 2019-11-09
tags:
    - documentation
    - hugo
    - netlify
    - GitHub Actions
---

Recently I changed my GitHub account name and converted my old username to an organization. My important projects are now nicely grouped into that organization and not hidden between all forked projects. Each project comes with documentation but so far it was a mess. One project used the GitHub wiki, another one used only the main readme. It's time to bring some order into the chaos. The question was how?

## GitHub pages

I started with [GitHub pages](https://help.github.com/en/github/working-with-github-pages/creating-a-github-pages-site). The main organization site was simple. Generate some HTML and push it to a master branch in `<organization>.github.io`. Done. Next up was adding project sites. Project sites are built automatically from the docs folder if you use Jekyll or you push the HTML pages directly to a `gh-pages` branch. That was easily done and I got the documentation online.

Now the first issue I ran into was making the organization site and all project sites feel like one website. This is very hard to do but not impossible. A good example is the [Zend Framework documentation](https://docs.zendframework.com/). Every project is integrated into the website and it feels like they all belong together.

## A shared theme

For the Zend Framework documentation, mkdocs is used with a custom [theme](https://github.com/zendframework/zf-mkdoc-theme). For each project, the documentation is generated with this theme and pushed to its `gh-pages` branch. With a lot of configuration, every project is glued into the documentation. Now here it comes... Developing the documentation is a pain. Writing itself is easy but testing it, not really. To get started you need Python 3, PIP, mkdocs, several plugins, npm, gulp and perl. I've tried to set this up on windows a while back but gave up on it. Recently I've stumbled upon [mkdocs-material](https://github.com/squidfunk/mkdocs-material) which adds all dependencies in a docker image and uses the mkdocs command as its entrypoint. This should make life easier.

I've got a lot of experience with [Hugo](https://gohugo.io/) and it's my preferred static site generator. So I tried that instead of mkdocs in the example.

On a side note: If you haven't used Hugo yet, it's fast... Really fast. It has a steep learning curve when it comes to customizing themes and adding functionality to it. But I guess that if you have golang experience it will be a lot easier. Oh, did I mention that it is fast? It also transforms your SCSS files into CSS if you use the extended version and supports postcss processing.

## The docker image variant

The [docker image](https://github.com/xtreamwayz/docs-sdk/blob/master/Dockerfile) is easy to build once you figured out which dependencies are needed for Hugo in an alpine image. I've extended node:lts-alpine, installed Hugo, installed the npm dependencies and copied the custom Hugo theme into the image. Installing the npm packages inside the image makes it bigger but you only need to install it once and not whenever you start a new container. It saves you bandwidth and time.

I've written a [docker-entrypoint.sh](https://github.com/xtreamwayz/docs-sdk/blob/master/docker-entrypoint.sh) script which sets up the theme and generates the project documentation. It supports a few commands:

> *NOTE:*
>
> Don't try to run these commands as it won't work because the docker image is not available.

```bash
# Build documentation into ./build
docker run --rm -it -v ${PWD}:/src <docker_user>/docs build

# Preview documentation at http:/localhost:1313/
docker run --rm -it -p 1313:1313 -v ${PWD}:/src <docker_user>/docs server

# Preview documentation at http:/localhost:1313/
# (including draft and future content)
docker run --rm -it -p 1313:1313 -v ${PWD}:/src <docker_user>/docs preview
```

So far so good. The project documentation can be previewed and build for production without setting up a ton of dependencies.

While working on documentation, I soon found some limitations with this setup. First of all, if there is a bug or missing feature in the theme, you need to go to the theme project, make changes, push the image to docker hub, wait until the new image is ready and download it again. If there is a specific need for a change in the theme you need to copy the project documentation to the theme project to be able to develop the new feature. It's not ideal but it works.

## Publishing project pages

I've been using Github Actions more and more lately and the documentation was built and pushed with an action:

```yaml
# ./.github/workflows/build-docs.yml
name: github-pages

on:
  push:
    branches:
      - master

jobs:
  build-deploy:
    runs-on: ubuntu-latest
    steps:
      - name: Checkout
        uses: actions/checkout@master

      - name: Prepare
        run: |
          rm -rf build
          git config user.name "${GITHUB_ACTOR}"
          git config user.email "${GITHUB_ACTOR}@users.noreply.github.com"
          git worktree add build gh-pages

      - name: Build
        uses: docker://iswai/iswai-docs:latest
        with:
          args: 'build'

      - name: Deploy
        run: |
          cd build
          git add --all
          git commit --allow-empty -m "docs: deploy from ${GITHUB_SHA}"
          git push origin gh-pages
```

This uses a git worktree to import the current project documentation located at the `gh-pages` branch and push changed files. However, currently, there is an annoying bug which prevents notifying the GitHub service to rebuild a project page on changes to the `gh-pages` branch. It works for private repositories, but not public ones. My solution was creating a bot account to deploy the documentation with a [Personal Access Token](https://help.github.com/en/github/authenticating-to-github/creating-a-personal-access-token-for-the-command-line). Once the GitHub devs figured out this issue and the rebuild event is triggered as it should, this solution might work for you. I say *might* because this is still not ideal.

So what if you update your theme? You end up with your main organization page with the changed theme, but not the project pages. They are still on an older theme version. Zend Framework solved this by creating a bot from where you can trigger project page rebuilds for all repositories (around 150 or so). This is a long-running process and to play nicely with GitHub and Travis CI, all rebuilds are queued and triggered with some time in between.

To automate this, one could trigger a documentation build action from the theme repository once it received an update. Let's not discuss why you don't want this for 150+ linked repositories. It's also not possible: You can't trigger GitHub Actions in other repositories within a GitHub Action. This is done to prevent chain reactions. If you set it up wrong you start a never-ending loop.

## Back to the drawing board

It's time to rethink all this. So we can't trigger all repositories automatically once a theme gets an update. It's a pain to make changes to the theme. Things like generating a sitemap.xml file won't work because all projects are generated separately.

What would be ideal is having 1 repository that builds documentation and serve it from 1 location. If project documentation is changed, it should be able to trigger a rebuild. If the theme is changed, it should trigger a site-wide rebuild.

## The Hugo modules experiment

And then there are [Hugo modules](https://gohugo.io/categories/hugo-modules). It is possible you haven't heard of it yet, because it's pretty new. It's available since version 0.56 and it requires go 1.12. With modules, you can import themes, layouts, CSS, partials, shortcodes, etc. However, it's so powerful that you can even import content from somewhere else.

```yaml
# ./config/_default/module.yml
modules:
  imports:
  - path: "github.com/xtreamwayz/html-form-validator"
    mounts:
    - source: "docs"
      target: "content/html-form-validator"
```

The config above tells Hugo to download https://github.com/xtreamwayz/html-form-validator and link it's docs dir to the content dir. You could even use this to import bootstrap and link its scss path to the assets path. I've tried this but it's pretty slow and using npm for bootstrap is much faster.

We need some kind of configuration. In Hugo you can use `params` for configuration. I've given each project it's own section so we can retrieve its data where ever we want.

```yaml
# ./config.yml
params:
  html-form-validator:
    name: html-form-validator
    latest: v1
    versions:
      - v1
```

Next, we define the projects landing page:

```yaml
# ./docs/_index.md
---
title: xtreamwayz/html-form-validator
type: project
layout: landingpage
project: html-form-validator
---
```

The `type` and `layout` are needed to tell which layout to use. `project` is used to tell Hugo to generate a page for that specific project and uses it to trigger some features in the theme.

A normal page uses the same config and sets an extra version as well:

```yaml
# ./docs/latest/_index.md
---
title: Getting started
type: project
layout: page
project: html-form-validator
version: v1
---
```

And to bind all projects together I've added a config to the main website config:

```yaml
# ./config/_default/params.yml
projects:
  - "devops"
  - "expressive-console"
  - "expressive-messenger"
  - "html-form-validator"
```

Now Hugo knows (or better yet, the theme knows) which projects to generate. Maybe I can get that list from the module imports config, but that's something for later.

So that's the configuration. Now triggering `Hugo server`, it downloads the modules, builds the entire site, including all linked project pages, and serves it locally. That's part one done. It sounds easy, but it was a lot of trial and error to get this working as I want because there is not much documentation about using modules like this yet.

## Hugo modules caveats

There is a small issue. The main website itself must also be a module (`hugo mod init <name>`). When doing this it creates a `go.mod` file with all downloaded modules and versions and it creates a `go.sum` with the checksums. This is nice and very good for security however it prevents updating the modules:

```go
module website

go 1.13

require (
	github.com/xtreamwayz/expressive-console v0.0.0-20191108154255-d1d08d24588a // indirect
	github.com/xtreamwayz/expressive-messenger v0.0.0-20191108160421-b557e2c28728 // indirect
	github.com/xtreamwayz/html-form-validator v0.0.0-20191108173115-a044c4a9b259 // indirect
)
```

This locks the imported modules to the specific version. There is a `hugo mod get -u` command which supposed to update modules, but I couldn't get it working on Windows. Ideally, you want always the latest version of the modules with the latest documentation changes like this:

```go
module website

go 1.13

require (
	github.com/xtreamwayz/expressive-console master
	github.com/xtreamwayz/expressive-messenger master
	github.com/xtreamwayz/html-form-validator master
)
```

However, when updating modules, the `master` part is changed into the downloaded version again. The solution is to git ignore the two files in your main website repo and then run this for development: `hugo mod init website && hugo server`. This makes sure you always import the latest documentation. The `go.mod` and `go.sum` should be added to each of your projects, otherwise Hugo complaints if you develop locally.

## Hugo modules and theme development

Developing locally is pretty easy. You can develop your main website, the included theme and optionally local projects with `replace` in `go.mod`:

```go
module website

go 1.13

require (
	github.com/xtreamwayz/devops v0.0.0-20191108125323-133e1062dfbc // indirect
	github.com/xtreamwayz/expressive-console v0.0.0-20191108154255-d1d08d24588a // indirect
	github.com/xtreamwayz/expressive-messenger v0.0.0-20191108160421-b557e2c28728 // indirect
	github.com/xtreamwayz/html-form-validator v0.0.0-20191108173115-a044c4a9b259 // indirect
)

replace (
	github.com/xtreamwayz/expressive-console => ../expressive-console
	github.com/xtreamwayz/html-form-validator => ../html-form-validator
)
```

The modules that aren't locally replaced are downloaded from the internet. That way you have the full website available locally and you can test and develop the full site and check each loaded project module.

## Hugo modules and ci

With this setup GitHub project pages aren't used, so why use GitHub Pages at all? I've got a very good experience with [netlify](https://www.netlify.com/). It's faster than GitHub Pages and after linking it to a repository it builds automatically your site. If you use Hugo combined with Netlify you've got a very fast pipeline. So using that takes care of building the site when changes are made to the [website](https://github.com/xtreamwayz/website) repository.

The last part that is missing is triggering a rebuild when the documentation of the project has changed:

```yaml
# ./.github/workflows/build-docs.yml
name: build-docs

on:
  push:
    branches:
    - master
    paths:
    - "docs/**"

jobs:
  build:
    runs-on: ubuntu-latest
    steps:
    - name: Trigger build webhook on Netlify
      run: curl -s -X POST "https://api.netlify.com/build_hooks/${TOKEN}"
      env:
        TOKEN: ${{ secrets.NETLIFY_DOCS_BUILD_HOOK }}
```

A Netlify site has a build hook which you can trigger. This GitHub Action is triggered on a push to the master branch and if there are changes to the docs path. It will trigger a netlify build hook. And that's the last part done.

Now we can

- Build one website with all projects integrated tightly
- Trigger site-wide rebuilds if a theme changes
- Trigger a rebuild if project documentation changes
- Develop our theme rapidly with minimal dependencies

If needed, I could still create a docker image with Hugo, Go, Node and npm inside but that's something for the future as well.

Enjoy.

## Resources

At the time of writing the theme and main website code can be viewed at the [website](https://github.com/xtreamwayz/website) repository and the imported project modules are in the [xtreamwayz](https://github.com/xtreamwayz) namespace. The generated documentation is available at https://xtreamwayz.netlify.com/.
