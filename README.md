PEAR_PackageFileManager_GitRepoOnly
===================================

A Git repository-only list plugin generator for both
PEAR_PackageFileManager, and PEAR_PackageFileManager2 classes.

It will only include files that are part of the git index-- that is, files
in the current branch plus files staged for commit.  It simply uses
`git ls-files` to get a list of files to package.  If you just want
files excluded that are in .gitignore or .git/info/exclude, see
https://github.com/armen/PEAR_PackageFileManager_Git

To build:
    $ pear package

To install:
    TBD
