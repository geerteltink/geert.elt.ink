---
title: 'Git squash all commits in a branch'
summary: 'How to squash all commits in a branch.'
date: 2022-07-16
tags:
  - git
---

Sometimes you have a feature branch on which you are working for a while with a lot of commits.
In the mean time, more work by others is merged in the main branch. When trying to rebase, you
keep getting merge conflicts every single time. Sounds familiar?

Why not squash all commits in the feature branch and save time fixing conflicts:

```bash
git checkout your_branch
git reset $(git merge-base main $(git branch --show-current))
git add -A
git commit -m "feat: a new feature with a single commit"
```
