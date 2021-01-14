## Finder Presets

---

#### [`BasicProjectFinder`](../src/Finders/BasicProjectFinder.php)

**Name:** `project`

**Features:**

- ignores VCS files
- ignores dot files
- includes PHP files
- excludes `vendor/` directory

**Description:** basic project

**Example:**

```bash
vendor/bin/pf-create-cs-config project
```

#### [`LaravelProjectFinder`](../src/Finders/LaravelProjectFinder.php)

**Name:** `laravel`

**Features:**

- inherits [`BasicProjectFinder`](#basicprojectfinder) presets
- excludes `*.blade.php` files
- excludes all files in `bootstrap/`, `public/`, `resources/`, `storage/`
- includes PHP files in `app/`, `config/`, `database/`, `routes/`, `tests/`

**Description:**  standard laravel project

**Example:**

```bash
vendor/bin/pf-create-cs-config laravel
```

#### [`LaravelPackageFinder`](../src/Finders/LaravelPackageFinder.php)

**Name:** `laravel:package`

**Features:**

- inherits [`BasicProjectFinder`](#basicprojectfinder) presets
- excludes `*.blade.php` files
- excludes all files in `resources/`
- includes PHP files in `src/`, `tests/`, `config/`

**Description:** standard laravel package

**Example:**

```bash
vendor/bin/pf-create-cs-config laravel:package
```

#### [`ComposerPackageFinder`](../src/Finders/ComposerPackageFinder.php)

**Name:** `package`

**Features:**

- inherits [`BasicProjectFinder`](#basicprojectfinder) presets
- includes PHP files in `src/`, `tests/`

**Description:**  standard composer package

**Example:**

```bash
vendor/bin/pf-create-cs-config package
```
