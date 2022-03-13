<p align="center">
<img src="https://static.permafrost.dev/images/phpcsfixer-preset/phpcsfixer-preset-logo-03.png" alt="Permafrost Dev" height="200" style="block">
<br><br>
<span style="font-size:2.3rem">phpcsfixer-preset</span>
</p>

<p align="center">
<img src="https://img.shields.io/packagist/v/permafrost-dev/phpcsfixer-preset" alt="version"/> <img src="https://img.shields.io/packagist/l/permafrost-dev/phpcsfixer-preset" alt="license"/> <img src="https://img.shields.io/packagist/dt/permafrost-dev/phpcsfixer-preset" alt="downloads"/> <img src="https://img.shields.io/github/workflow/status/permafrost-dev/phpcsfixer-preset/Run%20Tests/main" alt="Run Tests"/> <img src="https://coveralls.io/repos/github/permafrost-dev/phpcsfixer-preset/badge.svg?branch=main" alt="Coverage Status" />
</p>

<br>

---

This package allows sharing identical [php-cs-fixer](https://github.com/FriendsOfPhp/PHP-CS-Fixer) formatting rules across all of your projects without copy-and-pasting configuration files. There is also a quick setup script to automatically generate a configuration file for the project structure and preferred formatting preset.

`permafrost-dev/phpcsfixer-preset` provides several opinionated `php-cs-fixer` configuration choices as well as pre-configured `Finder` classes for common project formats and use cases.

Supported PHP versions are `7.3`, `7.4`, `8.0`, and `8.1`.

The original concept for this package came from this excellent article on [sharing php-cs-fixer configurations across projects](https://laravel-news.com/sharing-php-cs-fixer-rules-across-projects-and-teams)  written by [Tim Mcdonald](https://timacdonald.me/).

## Installation

`composer require permafrost-dev/phpcsfixer-preset --dev`

---

## Example `.php-cs-fixer.dist.php` files

This example uses the Laravel project finder and the Default Ruleset:

```php
<?php

require_once(__DIR__ . '/vendor/autoload.php');

use Permafrost\PhpCsFixerRules\Finders\LaravelProjectFinder;
use Permafrost\PhpCsFixerRules\Rulesets\DefaultRuleset;
use Permafrost\PhpCsFixerRules\SharedConfig;

$finder = LaravelProjectFinder::create(__DIR__);

return SharedConfig::create($finder, new DefaultRuleset());
```

Standard `PhpCsFixer\Finder` options can be chained onto the custom `Finder` class to customize it to your preferences:

```php
    // ...
    $finder = LaravelProjectFinder::create(__DIR__)
        ->in([__DIR__ . '/custom-src-dir'])
        ->notName('*.ignored.php')
        ->notPath('another-custom-dir/cache/*');
    // ...
```

The standard `PhpCsFixer\Finder` class can be used along with any of the Rulesets:

```php
<?php

require_once(__DIR__ . '/vendor/autoload.php');

use PhpCsFixer\Finder;
use Permafrost\PhpCsFixerRules\Rulesets\SpatieRuleset;
use Permafrost\PhpCsFixerRules\SharedConfig;

$finder = Finder::create()
    ->ignoreVCS(true)
    ->ignoreDotFiles(true)
    ->name('*.php')
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->exclude(__DIR__ . '/vendor');

return SharedConfig::create($finder, new SpatieRuleset());
```

---

## Overriding Ruleset Rules

When creating a `Ruleset` class, you may pass an array of `php-cs-fixer` rules that add or override the Ruleset's default rules.

```php
<?php

require_once(__DIR__.'/vendor/autoload.php');

use Permafrost\PhpCsFixerRules\Finders\LaravelProjectFinder;
use Permafrost\PhpCsFixerRules\Rulesets\DefaultRuleset;
use Permafrost\PhpCsFixerRules\SharedConfig;

$finder = LaravelProjectFinder::create(__DIR__);

return SharedConfig::create($finder, new DefaultRuleset([
    // existing rules can be overridden:
    'no_break_comment' => true,
    'no_closing_tag' => false,
    // new rules can be added:
    'a_new_option' => [
        'some_sub_option' => 12,
    ],
]));
```

---

## Quick Setup

To generate a `php-cs-fixer` configuration file for your project, run:

```bash
vendor/bin/pf-create-cs-config <type> [-o|--outfile=filename] [-r|--ruleset=name] [-f|--force]
```

Parameter: `<type>`

Required: **yes**

Default: _no default_

Possible values:

- `custom`
- `project`
- `package`
- `laravel` _(alias for laravel:project)_
- `laravel:project`
- `laravel:package`

Flag: `--outfile` (or `-o`)

Required: **no**

Default: `.php-cs-fixer.dist.php`

Possible values: any valid filename

Flag: `--ruleset` (or `-r`)<br>

Required: **no**<br>

Default: `default`<br>

Possible values:<br>

- `default`
- `laravel_shift`
- `php_unit`
- `spatie`

Flag: `--force` (or `-f`)<br>

Required: **no**<br>

Default: `false`<br>

Possible values: none<br>

Effect: overwrites any existing configuration file<br>

Examples:

```bash
vendor/bin/pf-create-cs-config laravel:package

vendor/bin/pf-create-cs-config package -f

vendor/bin/pf-create-cs-config laravel -o .php-cs-fixer.php -r spatie

vendor/bin/pf-create-cs-config project --ruleset=laravel_shift

vendor/bin/pf-create-cs-config custom --outfile=.my-config
```

**Note on the `custom` type:**

The `custom` type will prompt you to enter the directory names you would like `php-cs-fixer` to include and exclude. The generated configuration file implements the `PhpCsFixer\Finder` class instead of one of the preconfigured finder classes.

---

## Automatic Formatting

To apply `php-cs-fixer` formatting using Github Actions automatically, see the [automation with Github Actions](docs/automation.md) documentation.

---

## [Finder Presets](docs/finders.md)

#### `BasicProjectFinder`

- ignores VCS files
- ignores dotfiles
- includes PHP files
- excludes `vendor/` directory

#### `LaravelProjectFinder`

- inherits [`BasicProjectFinder`](#basicprojectfinder) presets
- excludes `*.blade.php` files
- excludes all files in `bootstrap/`, `public/`, `resources/`, `storage/`
- includes PHP files in `app/`, `config/`, `database/`, `routes/`, `tests/`

#### `LaravelPackageFinder`

- inherits [`BasicProjectFinder`](#basicprojectfinder) presets
- excludes `*.blade.php` files
- excludes all files in `resources/`
- includes PHP files in `src/`, `tests/`, `config/`

#### `ComposerPackageFinder`

- inherits [`BasicProjectFinder`](#basicprojectfinder) presets
- includes PHP files in `src/`, `tests/`

---

## [Rulesets](docs/rulesets/index.md)

#### `Default`

- Default opinionated Ruleset provided by this package.
- [View Rules](docs/rulesets/default_rules.md)

#### `LaravelShift`

- Ruleset used by [Laravel Shift](https://laravelshift.com).
- [View Rules](docs/rulesets/laravel_shift_rules.md)

#### `PhpUnit`

- Ruleset used by [PHPUnit](https://github.com/sebastianbergmann/phpunit).
- [View Rules](docs/rulesets/php_unit_rules.md)

#### `Spatie`

- Ruleset used by [Spatie](https://github.com/spatie).
- [View Rules](docs/rulesets/spatie_rules.md)

<br>

---

## Usage

Select a Finder preset or create an instance of `\PhpCsFixer\Finder` and return `SharedConfig::create($finder)` from the `.php-cs-fixer.dist.php` file.

## Updating Default Rules

Update the `rules()` method in the `Permafrost\PhpCsFixerRules\Rulesets\DefaultRuleset` class.

## Creating Rulesets

Create a class that implements the `Permafrost\PhpCsFixerRules\Rulesets\Ruleset` interface, returning your rules from the `rules()` method.

Sample Ruleset:

```php
<?php

namespace Permafrost\PhpCsFixerRules\Rulesets;

class MyCustomRulesRuleset implements RuleSet
{
    public function allowRisky(): bool
    {
        return true; //this tells php-cs-fixer whether or not to permit "risky" rules.
    }

    public static function name(): string
    {
        return 'my_custom_rules'; //the name should omit 'ruleset' from the end.
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return array_merge([
            '@PSR2' => true,
            // additional php-cs-fixer rules
        ], $this->additional); //it's important that the additional rules property is merged
    }
}
```

If adding a new Ruleset to this package, the Ruleset must be registered in `\Permafrost\PhpCsFixerRules\Commands\GenerateConfigCommand@rulesets()` to allow the quick setup command to use it.

When creating a new Ruleset package, follow the above example but use a namespace unique to the package.

---

## Code Formatting

To format all files specified in the configuration, run:

`vendor/bin/php-cs-fixer fix`

To list the files to be processed without making any changes:

`vendor/bin/php-cs-fixer fix  --dry-run`

---

## Testing

This package uses PHPUnit for unit tests. To run the test suite, run:

`./vendor/bin/phpunit`

---

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

---

## Contributions

Contributions of `Rulesets`, `Finders`, bug fixes, suggestions, or improvements are welcome. Please open an appropriately labeled issue or pull request for any of these.

---

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
