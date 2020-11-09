<p align="center">
<img src="https://static.permafrost.dev/images/permafrost-logo-02.png" alt="Permafrost Dev" height="150" style="block">
<br><br>
<span style="font-size:2.3rem">phpcsfixer-preset</span>
</p>

<center>
![version](https://img.shields.io/packagist/v/permafrost-dev/phpcsfixer-preset  "version") ![license](https://img.shields.io/packagist/l/permafrost-dev/phpcsfixer-preset  "license") ![downloads](https://img.shields.io/packagist/dt/permafrost-dev/phpcsfixer-preset  "downloads")
</center>

<br>

---

This package provides an opinionated, shared `php-cs-fixer` configuration as well as pre-configured `Finder` classes for common project formats and use cases.

## Installation

`composer require permafrost-dev/phpcsfixer-preset --dev`

---

## Example `.php_cs.dist` file:

```php
<?php

use Permafrost\PhpCsFixerRules\Finders\LaravelProjectFinder;
use Permafrost\PhpCsFixerRules\SharedConfig;

$finder = LaravelProjectFinder::create(__DIR__);

return SharedConfig::create($finder);
```
---

### Using php-cs-fixer

To format all files specified in the configuration, run:
`$ vendor/bin/php-cs-fixer fix --config=.php_cs.dist`

To see which files will be changed, run:
`$ vendor/bin/php-cs-fixer fix --config=.php_cs.dist --dry-run`

---

## Finder Presets:

#### `BasicProjectFinder`
- ignores VCS files
- ignores dot files
- inclues php files
- excludes `vendor` directory

#### `LaravelProjectFinder`
- inherits `BasicProjectFinder`
- only includes directories that exist
- excludes `*.blade.php` files
- excludes all files in `bootstrap/`, `public/`, `resources/`, `storage/`
- includes php files in `app/`, `config/`, `database/`, `routes/`, `tests/`

#### `LaravelPackageFinder`
- inherits `BasicProjectFinder`
- only includes directories that exist
- excludes `*.blade.php` files
- excludes all files in `resources/`
- includes php files in `src/`, `tests/`, `config/`

#### `ComposerPackageFinder`
- inherits `BasicProjectFinder`
- only includes directories that exist
- includes php files in `src/`, `tests/`

## Usage
Select a Finder preset or create an instance of `\PhpCsFixer\Finder` and return `SharedConfig::create($finder)` from the `.php_cs.dist` file.

## Adding Rules
Add or remove the desired rules to the `src/rules.php` file.

