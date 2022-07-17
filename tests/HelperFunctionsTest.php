<?php

use function Jhavenz\LaravelHelpers\Helpers\allTableNames;
use function Jhavenz\LaravelHelpers\Helpers\tableColumns;

it('gets all table names', function () {
    $tableNames = allTableNames('testing');

    expect($tableNames)->toBe([
        'users',
        'posts',
    ]);
});

it('gets all table columns', function () {
    $tableNames = tableColumns('users', 'testing');

    expect($tableNames)->toBe([
        'id',
        'username',
        'password',
        'created_at',
        'updated_at',
    ]);
});
