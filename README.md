# OpenScholar build for Platform.sh and quick install

This project provides a starter kit for OpenScholar hosted on Platform.sh. or other hosting services.

To use in platform.sh create a new platform environment and place project.make:

```make
api = 2
core = 7.x

projects[openscholar][type] = core
projects[openscholar][download][type] = git
projects[openscholar][download][branch] = master
projects[openscholar][download][url] = "https://github.com/Gizra/openscholar-build.git"

; Drush make allows a default sub directory for all contributed projects.
defaults[projects][subdir] = contrib

; Platform indicator module.
projects[platform][version] = 1.4
```
