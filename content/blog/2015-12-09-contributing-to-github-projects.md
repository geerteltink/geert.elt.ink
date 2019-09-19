---
id: 2015-12-09-contributing-to-github-projects
title: Contributing to a github project
summary: Keep your github fork in sync.
date: 2015-12-09
tags:
    - git
    - git-flow
---

This guide is a mix of several other guides and my own experience. I'm just writing this down altogether so I can easily find the right commands.

## Setup forked project

Clone your forked project:

```bash
git clone git@github.com:<username>/<projectname>.git
```

Change into the new project's directory:

```bash
cd <projectname>
```

Add an upstream so the fork can be synced easily.

```bash
git remote add upstream git@github.com:<original-namespace>/<projectname>.git
```

Disable push to upstream

```bash
git remote set-url --push upstream no_push
```

## Keeping Up-to-Date

Periodically, you should update your fork or personal repository to match the upstream repository. Assuming you have setup your local repository per the instructions above, you can do the following:

```bash
git checkout master
git fetch upstream master
git rebase upstream/master
git push origin master

git checkout develop
git fetch upstream develop
git rebase upstream/develop
git push origin develop
```

## Working on a patch

**The number one rule is to put each piece of work on its own branch.** If the project is using git-flow, then it will have both a master and a develop branch. The general rule is that if you are bug fixing, then branch from master and if you are adding a new feature then branch from develop.

Create a branch for a hotfix to the master branch, mentioned in issue #1234.

```bash
# Checkout master branch and update upstream first
git checkout -b hotfix/1234
```

Create a branch for a hotfix to the master branch.

```bash
# Checkout master branch and update upstream first
git checkout -b hotfix/<issue-that-needs-to-be-fixed>
```

Create a branch for a feature to the develop branch or the master branch if there is no develop branch.

```bash
# Checkout develop branch and update upstream first
git checkout -b feature/<awesome-feature>
```

Time to start coding...

## Rebase PR

Once in a while the code changed before the pull request is merged. You will be asked to rebase the the PR. What you
need to do is update the master or develop branch as explained above. And after that checkout the hotfix or feature
and rebase it to the master or develop branch, followed by pushing the changes.

```bash
# Update master or develop
git checkout hotfix/1234
git rebase master

# Fix conflicts
git add <file>
git rebase --continue
# Repeat for multiple conflicts

git push -f origin
```

You probably get a "Updates were rejected because the tip of your current branch is behind" error. In that case
use `git push -f` to force the new changes.

## Create the PR

To create a PR you need to push your branch to the origin remote and then press some buttons on GitHub.

```bash
git push -u origin hotfix/1234
```

Usually on the forked project page in github, a green button shows up for comparing and creating the pull request. However if the official package is forked from your project, it's easier to go to the official package and press the green button there. The right base and headfork are then choosen for you.

## Resources

- [Contributing to Zend Framework projects](https://github.com/zendframework/zend-expressive-skeleton/blob/master/CONTRIBUTING.md)
- [Beginners guide to contributing to a GitHub project](http://akrabat.com/the-beginners-guide-to-contributing-to-a-github-project/)
- [Beginners guide to rebasing your pr](http://akrabat.com/the-beginners-guide-to-rebasing-your-pr/)
