---
id: 2015-05-21-git-troubleshooting
title: Git Troubleshooting
summary: Some ways to resolve git issues.
draft: false
public: true
published: 2015-05-21T10:00:00+01:00
modified: 2015-10-08T14:16:00+01:00
tags:
    - git
---

* [Stage changes and new files](#stage-changes-and-new-files)
* [Commit changes](#commit-changes)
* [Fix last commit](#fix-last-commit)
* [Pull before push](#pull-before-push)
* [Push changes](#push-changes)
* [Forced merge](#forced-merge)
* [Handling merge conflicts](#handling-merge-conflicts)
* [Cherry picking](#cherry-picking)
* [Renaming tags](#renaming-tags)
* [Deleting branches](#deleting-branches)
* [Stashing uncommitted changes](#stashing-uncommitted-changes)
* [Unstage files](#unstage-files)
* [Resetting files](#resetting-files)
* [Cleanup tracked files (after editing .gitignore)](#cleanup-tracked-files-after-editing-gitignore)
* [Trigger hooks and ci tests](#trigger-hooks-and-ci-tests)

## Stage changes and new files

```bash
git add .   // Stage created/modified files and not those deleted
git add -u  // Stage deleted/modified files and not those created
git add -A  // Stage created/modified/deleted files
```

## Commit changes

```bash
git commit -a -m "" // Stage changes to all tracked files
```

## Fix last commit

Amending the commit message.

```bash
git commit --amend -m "New commit message"
```

Amending changed files.

```bash
git commit --amend -a
```

## Pull before push

Sometimes a push failed because the remote branch contains commits which you do not have locally. You need to pull these in first. However doing this with a normal `git pull` command, it creates an extra *merged branch* commit. This is normal, but can get messy if it happens a lot. To prevent this, you need to *rebase* your commits behind the new commits made by others when pulling.

```bash
git pull --rebase
```

## Push changes

Push commits in current branch.

```bash
git push
```

Push commits in all branches.

```bash
git push --all
```

Push [tag] to origin

```bash
git push origin [tag]
```

## Forced merge

Force the merged branch as the new branch. This might prevent a lot of merge conflicts and force the release branch into the master branch.

```bash
git merge --no-ff -s recursive -Xtheirs [branch]
```

## Handling merge conflicts

First you have to fix the merge conflicts. Use ``git status`` to check where the conflicts are. After that you can commit resolved conflicts.

```bash
git add -A
git commit -a -m "Merge branch '[branch-name]'"
git push
```

## Cherry picking

Cherry pick a specific commit from another branch into the current branch.

```bash
git cherry-pick [commit]
```

## Renaming tags

```bash
git tag [new] [old]
git tag -d [old]
git push origin :refs/tags/[old]
git push origin [new]
```

## Deleting branches

Delete a remote branch.

```bash
git push origin :release/[x.x.x]
```

Delete a local branch.

```bash
git branch -d release/[x.x.x]
```

## Stashing uncommitted changes

Stash all changes and get a clean working directory.

```bash
git stash
// .. Do some git stuff like checkout branch or update the repo
git stash pop
```

Clear the stash stack if you don't need the changes after all.

```bash
git stash drop
```

## Unstage files

Unstage files wwhich are not committed yet.

```bash
git reset HEAD
git reset HEAD [file]
```

Discard all changes

```bash
git checkout .
```

## Resetting files

Reset files to the HEAD of the branch.

```bash
git reset --hard HEAD
git reset --hard HEAD [file]
```

## Cleanup tracked files (after editing .gitignore)

```bash
git rm -r --cached .
git add .
git commit -am "Remove ignored files"
```

## Trigger hooks and ci tests

Use an empty commit to trigger hooks and ci tests.

```bash
git commit --allow-empty -m "Trigger hooks"
```
