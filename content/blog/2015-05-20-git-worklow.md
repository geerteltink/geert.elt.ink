---
id: 2015-05-20-git-worklow
title: Git Workflow
summary: A git workflow suitable for large projects.
date: 2015-05-20
tags:
    - git
---

Git workflow works best for large projects. For smaller projects or projects with little commits and releases I suggest to use something lightweight like [GitHub Flow](https://guides.github.com/introduction/flow/).

* [Versioning](#versioning)
* [Commit Messages](#commit-messages)
* [Feature Branches](#feature-branches)
* [Release Branches](#release-branches)
* [Hotfix Branches](#hotfix-branches)

This guide is based on [a successful git branching model](http://nvie.com/posts/a-successful-git-branching-model/). The central repo holds the two main branches, `master` and `develop`, with an infinite lifetime.

The `origin/master` branch is considered the main branch where the source code always reflects a production-ready state. Never push code changes into this branch. Only merge other tested stable branches in to this.

The `origin/develop` branch contains the source code with the latest development changes for the next release. If there automatic nightly builds, this is where they should be built from. Don't start big code changes into this branch and keep the `develop` branch clean. Make a local feature branch for big projects and once ready, merge it back into this `develop` branch.

There are 3 supporting branches: [feature/*](#feature-branches), [release/*](#release-branches) and [hotfix/*](#hotfix-branches). These branches aid development between team members, ease tracking of features, prepare for production releases and to assist in quickly fixing live production problems. Unlike the main branches, these branches always have a limited lifetime, since they will be removed eventually.

To help with the workflow and save typing commands, predefined git-flow commands can be installed from [gitflow](https://github.com/petervanderdoes/gitflow). Although gitflow is faster, I think it's better to use the manual commands so you learn / know what is actually going on. It might help when troubleshooting in case a gitflow command gives an error.

## Versioning

For versioning [Semantic Versioning](http://semver.org/) is used. This means `<major>.<minor>.<patch>`.

## Commit Messages

The first line should be a short summary, followed by an empty line and a detailed explanatory. If the summary contains (closes #1) or (fixes #1), it auto closes issue #1 when merged into the default (master) branch. To reference to an issue use "Partially fix issue #1".

```text
Capitalized, short (50 chars or less) summary (closes #1), (fixes #1) or issue #1

More detailed explanatory text, if necessary. Wrap it to about 72
characters or so.  In some contexts, the first line is treated as the
subject of an email and the rest of the text as the body. The blank
line separating the summary from the body is critical (unless you omit
the body entirely); tools like rebase can get confused if you run the
two together.

Write your commit message in the imperative: "Fix bug" and not "Fixed bug"
or "Fixes bug". This convention matches up with commit messages generated
by commands like git merge and git revert.

Further paragraphs come after blank lines.

- Bullet points are okay, too

- Typically a hyphen or asterisk is used for the bullet, preceded by a
  single space, with blank lines in between, but conventions vary here

- Use a hanging indent
```

## Git flow branches

### Feature Branches

* May branch off from: `develop`
* Must merge back into: `develop`
* Branch naming convention: `feature/*`

Feature branches are used to develop new features for upcoming or distant future releases. The essence of a feature branch is that it exists as long as the feature is in development, but will eventually be merged back into `develop` (to definitely add the new feature to the upcoming release) or discarded (in case of a disappointing experiment).

```bash
// Create feature branch
git checkout -b feature/[branch-name] develop

// ... Develop feature
// ... Perform tests

// Finish feature branch
git pull origin develop
git checkout develop
git merge --no-ff feature/[branch-name]
git push

// Delete feature branch
git branch -d feature/[branch-name]

// ... Test new feature in the develop branch
```

### Release Branches

* May branch off from: `develop`
* Must merge back into: `develop` and `master`
* Branch naming convention: `release/*`

Release branches support preparation of a new production release. They are in a feature freeze state and allow only for QA tests, bug fixes and preparing meta-data for a release (version number, build dates, etc.).

```bash
// Prepare a release
git checkout -b release/[x.x.x] develop

// ... Bump version
git commit -a -m "Bumped version number to [x.x.x]"

// (optional) Make release branch available on origin
git push -u origin release/[x.x.x]

// ... Perform QA tests
// ... Bugfix release

// Finish a release and merge into master
git checkout master
git merge --no-ff release/[x.x.x]
git tag -a v[x.x.x] -m "Release v[x.x.x]"
git push
git push origin v[x.x.x]

// Merge into develop
git checkout develop
git merge --no-ff release/[x.x.x]
git push

// (optional) Delete branch on origin
git push origin :release/[x.x.x]

// Delete release branch locally
git branch -d release/[x.x.x]
```

### Hotfix Branches

* May branch off from: `master`
* Must merge back into: `develop` and `master`
* Branch naming convention: `hotfix/*`

Hotfix branches are used to fix critical bugs in a production version that must be resolved immediately.

```bash
// Prepare a hotfix
git checkout -b hotfix/[x.x.x] master

// ... Bump version
git commit -a -m "Bumped version number to [x.x.x]"

// ... Fix the bug(s)
// ... Perform QA tests

// Finish a hotfix and merge into master
git checkout master
git merge --no-ff hotfix/[x.x.x]
git tag -a v[x.x.x] -m "Hotfix v[x.x.x]"
git push
git push origin v[x.x.x]

// Merge into develop
git checkout develop
git merge --no-ff hotfix/[x.x.x]
git push

// Delete hotfix
git branch -d hotfix/[x.x.x]
```
