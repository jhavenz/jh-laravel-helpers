# Helper functions for any Laravel project

---

Convenience methods and helpers

---


## Installation

You can install the package via composer:

```bash
composer require jhavenz/jhavenz-laravel-helpers
```

## Methods included
    - dbListen()
    - carbon()
    - allTableNames()
    - tableColumns()
    - tableHasColumn()
    - classUsesTrait()
    - setSeedingRuntime()
    - isSeeding()
    - enforceString()
    - getRandomModel()

### Or Use Any Method On The Facade. e.g.
    - \Jhavenz\Facades\LaravelHelpers::dbListen()


```php
dbListen(): void
```
Logs any/all queries that are made after the execution of this method

---
```php
carbon(): \Carbon\Carbon
```
Carbon instance convenience helper

---

```php
allTableNames(): array
```
Get all table names for a specified database connection

---

```php
tableColumns(): array
```
Get all columns for the specified table

---

```php
tableHasColumn(): bool
```
Check whether a table has the specified column

---

```php
classUsesTrait(): bool
```
Check if a class uses the specified trait

---

```php 
setSeedingRuntime(): void
```
Sets bindings for use when seeding data

---

```php 
isSeeding(): bool
```
Checks if binding for use when seeding data

---

```php 
enforceString(): bool
```
Checks whether the $input is a string, returning a boolean or throwing an exception based on arguments provided

---

```php 
getRandomModel(): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection
```
Get random Model or Models

---

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
