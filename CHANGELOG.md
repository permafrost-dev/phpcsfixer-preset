# Changelog

All notable changes to `permafrost-dev/phpcsfixer-preset` will be documented in this file _(as of 2021-Jan-05)_.

---

## 1.5.6 - 2021-Jan-14

- add `increment_style.style = post` to the Default Ruleset.

## 1.5.5 - 2021-Jan-12

- Begin documentation overhaul.
- add `push()` helper method to the `Collection` class.
- add coveralls.io workflow and badge to README.

## 1.5.4 - 2021-Jan-06

- Reduce complexity and remove unnecessary code and tests in a number of places.
- Minor updates & fixes to the README.

## 1.5.3 - 2021-Jan-06

- Minor formatting fixes, updated changelog.

## 1.5.2 - 2021-Jan-06

- Add additional unit tests for custom config generator and generate config command, bringing code coverage to >90%.

## 1.5.0 - 2021-Jan-05

- Changed `Ruleset` classes to accept an optional array parameter that overrides/adds ruleset configuration options.
- Removed undocumented feature to pass overriding/additional ruleset configuration options to `SharedConfig`.
- Simplified `Ruleset` unit tests by consolidating common tests into a single base TestCase class.
- `Ruleset` classes must now return `array_merge([/* array of rules */, $this->additional])` from the `rules()` method.  This ensures the default rules can be overridden or updated if needed.
- Added `CHANGELOG.md` to document changes to the package.

## 1.4.1 - 2021-Jan-05

- Updated unit tests to make them more robust and increase code coverage.

## 1.4.0 - 2021-Jan-05

- Added 'custom' type to the configuration generator script. Specifying this type will cause the user to be prompted for the directories `php-cs-fixer` should include and exclude, and the generated configuation file uses the standard `PhpCsFixer\Finder` class instead of a custom Finder preset.
