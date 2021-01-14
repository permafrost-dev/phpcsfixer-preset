# Automatic Code Formatting

If you'd like to automatically format your code whenever you push to your `main` branch, you can use the following Github Action workflow.  

Save this file in your project directory as `.github/workflows/php-cs-fixer.yml`.

---

**This workflow makes several assumptions:** 

- that your primary branch name is `main`, not something else such as `master`

- that your `php-cs-fixer` configuration file is named `.php_cs.dist`

If either of these differ from your setup, please adjust the workflow accordingly.

---

## `.github/workflows/php-cs-fixer.yml`

```yaml
name: Check & fix styling

on:
  push:
    branches:
      - main

jobs:
  php-cs-fixer:

    runs-on: ubuntu-latest
    strategy:
      fail-fast: false

    steps:
      - name: Checkout code
        uses: actions/checkout@v2
        with:
          ref: ${{ github.head_ref }}

      -   name: Setup PHP
          uses: shivammathur/setup-php@v2
          with:
            php-version: 7.4
            extensions: mbstring
            coverage: none
            tools: composer:v2

      -   name: Cache dependencies
          uses: actions/cache@v2
          with:
            path: ~/.composer/cache/files
            key: dependencies-php-7.4-composer-${{ hashFiles('composer.json') }}

      -   name: Install dependencies
          run: composer install --prefer-dist --no-interaction --optimize-autoloader

      -   name: Run PHP CS Fixer
          run: vendor/bin/php-cs-fixer fix --config=.php_cs.dist --allow-risky=yes

      - name: Commit changes
        uses: stefanzweifel/git-auto-commit-action@v4
        with:
          commit_message: Fix styling
```
