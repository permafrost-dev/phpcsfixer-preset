<p align="center">
<img src="https://static.permafrost.dev/images/permafrost-logo-02.png" alt="Permafrost Dev" height="150" style="block">
<br><br>
<span style="font-size:2.3rem">phpcsfixer-preset</span>
</p>

<p align="center">
<img src="https://img.shields.io/packagist/v/permafrost-dev/phpcsfixer-preset" alt="version"/> <img src="https://img.shields.io/packagist/l/permafrost-dev/phpcsfixer-preset" alt="license"/> <img src="https://img.shields.io/packagist/dt/permafrost-dev/phpcsfixer-preset" alt="downloads"/>
</p>

<br>

---

This package provides an opinionated, shared `php-cs-fixer` configuration as well as pre-configured `Finder` classes for common project formats and use cases.

The original concept for this package came from this excellent article on [sharing php-cs-fixer configurations across projects](https://laravel-news.com/sharing-php-cs-fixer-rules-across-projects-and-teams)  written by [Tim Mcdonald](https://timacdonald.me/).

## Installation

`composer require permafrost-dev/phpcsfixer-preset --dev`

---

## Example `.php_cs.dist` file

```php
<?php

require_once(__DIR__.'/vendor/autoload.php');

use Permafrost\PhpCsFixerRules\Finders\LaravelProjectFinder;
use Permafrost\PhpCsFixerRules\Rulesets\DefaultRuleset;
use Permafrost\PhpCsFixerRules\SharedConfig;

$finder = LaravelProjectFinder::create(__DIR__);

return SharedConfig::create($finder, new DefaultRuleset());
```

---

### Using php-cs-fixer

To format all files specified in the configuration, run:
`$ vendor/bin/php-cs-fixer fix --config=.php_cs.dist`

To see which files will change, run:
`$ vendor/bin/php-cs-fixer fix --config=.php_cs.dist --dry-run`

---

### Quick Setup

To generate a configuration file for a project, run `vendor/bin/pf-create-cs-config <type> [--ruleset=<ruleset>]`.

The `type` parameter is mandatory.
Possible values for `type`:

- `project`
- `package`
- `laravel` _(alias for laravel:project)_
- `laravel:project`
- `laravel:package`

The `--ruleset` flag is optional.
Possible values for `--ruleset`:

- `default`
- `laravel_shift`
- `php_unit`
- `spatie`

This will generate the appropriate `.php_cs.dist` file in your project directory,

---

## Finder Presets

#### `BasicProjectFinder`

- ignores VCS files
- ignores dot files
- inclues php files
- excludes `vendor` directory

#### `LaravelProjectFinder`

- inherits `BasicProjectFinder`
- excludes `*.blade.php` files
- excludes all files in `bootstrap/`, `public/`, `resources/`, `storage/`
- includes php files in `app/`, `config/`, `database/`, `routes/`, `tests/`

#### `LaravelPackageFinder`

- inherits `BasicProjectFinder`
- excludes `*.blade.php` files
- excludes all files in `resources/`
- includes php files in `src/`, `tests/`, `config/`

#### `ComposerPackageFinder`

- inherits `BasicProjectFinder`
- includes php files in `src/`, `tests/`

## Rulesets

#### `DefaultRuleset`

- The default, opinionated ruleset provided by this package.

#### `LaravelShiftRuleset`

- The ruleset used by [Laravel Shift](https://laravelshift.com).

#### `PhpUnitRuleset`

- The ruleset used by [PHPUnit](https://github.com/sebastianbergmann/phpunit).

#### `SpatieRuleset`

- The ruleset used by [Spatie](https://github.com/spatie).

## Usage

Select a Finder preset or create an instance of `\PhpCsFixer\Finder` and return `SharedConfig::create($finder)` from the `.php_cs.dist` file.

## Updating Rules

Update the `rules()` method in the `Permafrost\PhpCsFixerRules\Rulesets\DefaultRuleset` class.

## Creating Rulesets

Create a class that implements the `Permafrost\PhpCsFixerRules\Rulesets\Ruleset` interface, returning your rules from the `rules()` method.
