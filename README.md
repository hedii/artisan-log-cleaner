[![Build Status](https://github.com/hedii/artisan-log-cleaner/workflows/Tests/badge.svg)](https://github.com/hedii/artisan-log-cleaner/actions)
[![Total Downloads](https://poser.pugx.org/hedii/artisan-log-cleaner/downloads)](//packagist.org/packages/hedii/artisan-log-cleaner)
[![License](https://poser.pugx.org/hedii/artisan-log-cleaner/license)](//packagist.org/packages/hedii/artisan-log-cleaner)
[![Latest Stable Version](https://poser.pugx.org/hedii/artisan-log-cleaner/v)](//packagist.org/packages/hedii/artisan-log-cleaner)

# Artisan Log Cleaner

An artisan command to clear laravel log files

## Table of contents

- [Table of contents](#table-of-contents)
- [Installation](#installation)
- [Usage](#usage)
  - [Clear all log files](#clear-all-log-files)
  - [Clear all log files except the last one](#clear-all-log-files-except-the-last-one)
- [Testing](#testing)
- [License](#license)

## Installation

Install via [composer](https://getcomposer.org/doc/00-intro.md)

```sh
composer require hedii/artisan-log-cleaner
```

Add it to your providers array in `config/app.php`:

```php
Hedii\ArtisanLogCleaner\ArtisanLogCleanerServiceProvider::class
```

## Usage

### Clear all log files

Run this command to clear all log files in the log directory (`storage/logs`):

```
php artisan log:clear
```

### Clear all log files except the last one

Run this command to clear all log files except the last one in the log directory (`storage/logs`):

```
php artisan log:clear --keep-last
```

### Clear all log files except specified file names

Run this command to clear all log files except the specified files in the option. Specify file name without extension. Can be combined with `--keep-last` option.

```
php artisan log:clear --keep="keptLog"
```

### Automated cleanup of log files 

Add this to the App\Console\Kernel schedule method to ensure daily cleanup of old log files

```
$schedule->command('log:clear --keep-last')->daily();
```

## Testing

```
composer test
```

## License

hedii/artisan-log-cleaner is released under the MIT Licence. See the bundled [LICENSE](https://github.com/hedii/artisan-log-cleaner/blob/master/LICENSE.md) file for details.
