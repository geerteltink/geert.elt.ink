---
id: 2015-01-24-check-git-status-recursively-on-windows
title: Check git status recursively on windows
summary: Easy command to check all git repo's for changes not committed or pushed in all sub directories.
draft: false
public: true
published: 2015-01-24T10:00:00+01:00
modified: 2015-02-21T15:42:00+01:00
tags:
    - Windows
    - cli
---

Sometimes you wonder if you forgot to commit and push changes. And if you are working on several projects at the same time you just don't know which ones. With this small batch script you can check the current directory and all sub directories for changes that still need to be committed or pushed.

## The code

Create a new file called ``git-status.cmd`` somewhere in your PATH, place this code in there and run it from where ever you want.

```powershell
@ECHO OFF
setlocal ENABLEDELAYEDEXPANSION

SET _START=%CD%

ECHO.
ECHO -------------------------------------------------------------
ECHO Checking git repos for uncommitted files and unpushed commits
ECHO -------------------------------------------------------------
ECHO.

SET _ERROR=0

:: Get all directories
FOR /F %%D IN ('DIR /B /S /A:D ^| FINDSTR /V "node_modules" ^| FINDSTR /E ".git"') DO (
    SET _CURRENT_DIR=%%D

    :: Change to current dir
    PUSHD !_CURRENT_DIR:~0,-4!
    SET _CURRENT_ERROR=0
    SET _UNCOMMITTED=0
    SET _UNPUSHED=0

    :: Check for uncommitted changes
    git status --porcelain | FIND /v /c "" > %TMP%\git-status
    SET /P _COUNT= < %TMP%\git-status
    IF !_COUNT! GTR 0 (
        SET _ERROR=1
        SET _CURRENT_ERROR=1
        SET _UNCOMMITTED=1
    )
    rm %TMP%\git-status

    :: Check for changes that need a push
    git log --oneline --branches --not --remotes | FIND /v /c "" > %TMP%\git-status
    SET /P _COUNT= < %TMP%\git-status
    IF !_COUNT! GTR 0 (
        SET _ERROR=1
        SET _CURRENT_ERROR=1
        SET _UNPUSHED=1
    )
    rm %TMP%\git-status

    IF !_CURRENT_ERROR! == 1 (
        ECHO.
        ECHO Scanning: !_CURRENT_DIR:~0,-4!
    )

    IF !_UNCOMMITTED! == 1 (
        ECHO [31mWarning: Uncommitted files found![0m
    )

    IF !_UNPUSHED! == 1 (
        ECHO [31mWarning: Not all commits are pushed![0m
    )
)

IF !_ERROR! == 0 (
    ECHO.
    ECHO [33mAll files committed and pushed.[0m
)
```
