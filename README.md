# Composer SvnExport
A composer plugin that allows for exporting svn repositories

## Installation
Install the latest version with

```
$ composer require linearsoft/composer-svn-export
```

## Usage
There are two ways to use SvnExport
### 1. Modify the repository
If you are hosting your own composer repository you can simply change the source type
from 'svn' to 'svn-export'.
##### composer.json
```json
{
    "require": {
      "vendor/my-package": "dev-trunk"
    },
    "repositories": [
        {
            "type": "composer",
            "url": "http://my-repo.vendor.com"
        }
    ]
}
```
##### packages.json
```json
{
    "packages": {
        "vendor/my-package": {
            "dev-trunk": {
                "name": "vendor/my-package",
                "description": "My very special package",
                "version": "dev-trunk",
                "source": {
                    "type": "svn-export",
                    "url": "http://svn.vendor.com/my-package",
                    "reference": "/trunk/@50"
                },
                "other tags...": "blah, blah, blah"
            }
        }
    }
}
```
### 2. Add a SvnExport repository
A repository of type 'svn' or 'composer' can be used as a SvnExport repository
via the extras section. Note: All packages of type 'svn' in a composer repository will automatically be
converted to type 'svn-export'. **Warning:** If you add a repository as a SvnExport repository _do NOT
also add_ it as a standard repository or you will have problems.
##### composer.json
```json
{
    "require": {
      "vendor/my-package-alpha": "dev-trunk",
      "vendor/my-package-beta": "dev-trunk"
    },
    "extra": {
        "svn-export-repositories": [
            {
                "name": "My Repo (optional)",
                "type": "composer",
                "url": "http://my-repo.vendor.com"
            },
            {
                "type": "svn",
                "url": "http://svn.vendor.com/my-package-beta"
            }
        ]
    }
}
```
##### packages.json
```json
{
    "packages": {
        "vendor/my-package": {
            "dev-trunk": {
                "name": "vendor/my-package",
                "description": "My very special package",
                "version": "dev-trunk",
                "source": {
                    "type": "svn",
                    "url": "http://svn.vendor.com/my-package",
                    "reference": "/trunk/@50"
                },
                "other tags...": "blah, blah, blah"
            }
        }
    }
}
```
## About
### Bugs or features requests
Found a problem or would like a feature submit it via [GitHub](https://github.com/LinearSoft/composer-svn-export/issues)
### License
SvnExport is license under the GPLv3 License - see the `LICENSE` file for details
### Acknowledgements
François Pluchino's [composer-asset-plugin](https://github.com/francoispluchino/composer-asset-plugin) design was used as a foundation for this plugin.
