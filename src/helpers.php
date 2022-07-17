<?php

use Carbon\Carbon;
use Illuminate\Auth\Access\Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\MySqlConnection;
use Illuminate\Database\PostgresConnection;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Database\SqlServerConnection;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate as GateFacade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

if (!function_exists('allTableNames')) {
    function allTableNames(?string $connection = null): array
    {
        $schema = Schema::connection($connection = ($connection ?: config('database.default')));

        /** @noinspection PhpVoidFunctionResultUsedInspection */
        /** @var array $tables */
        $tables = $schema->getAllTables();

        return match ($cType = get_class($schema->getConnection())) {
            SQLiteConnection::class, SqlServerConnection::class => Arr::pluck($tables, 'name'),
            PostgresConnection::class => $tables['tablename'],
            MySqlConnection::class => array_reduce($tables, function ($carry, $item) use ($connection) {
                foreach (array_keys((array)$item) as $key) {
                    if (str_ends_with(strtolower($key), "in_{$connection}")) {
                        $carry[] = $item[$key];
                    }
                }

                return $carry;
            }),
            default => throw new \InvalidArgumentException(
                "Unknown connection type when getting table listing: [{$cType}]"
            )
        };
    }
}

if (!function_exists('carbon')) {
    /**
     * Carbon instance helper
     *
     * @param null $parseString
     * @param null $tz
     *
     * @return Carbon
     */
    function carbon($parseString = null, $tz = null): Carbon
    {
        return new Carbon($parseString, $tz);
    }
}

if (!function_exists('classUsesTrait')) {
    function classUsesTrait($class): bool
    {
        $uses = array_flip(class_uses_recursive(is_string($class) ? app($class) : $class));

        return isset($uses[$class]);
    }
}

if (!function_exists('currentPageTitle')) {
    function currentPageTitle(): string
    {
        $title = str(router()->currentRouteName())->endsWith('.index')
            ? str(router()->currentRouteName())->before('.index')
            : str(router()->currentRouteName())->after('.');

        return (string)$title->title();
    }
}

if (!function_exists('dbListen')) {
    function dbListen(): void
    {
        DB::listen(function ($query) {
            return dump([
                'sql' => $query->sql,
                'bindings' => $query->bindings,
            ]);
        });
    }
}

if (!function_exists('enforceString')) {
    function enforceString($input, bool $asBoolean = false, $errorMessage = null): bool
    {
        if (!is_string($input)) {
            if ($asBoolean) {
                return false;
            }

            throw new \InvalidArgumentException(
                $errorMessage ?? 'Argument must be a string, received: '.get_debug_type($input)
            );
        }

        return true;
    }
}

if (!function_exists('gate')) {
    function gate(): Gate
    {
        return GateFacade::getFacadeRoot();
    }
}

if (!function_exists('getRandomModel')) {
    /** @deprecated - use [Model::query()->rand(...);] instead */
    function getRandomModel(Model $class, int $count = 1)
    {
        return $class::query()
            ->inRandomOrder()
            ->when(
                $count !== 1,
                fn($q) => $q->limit($count)->get(),
                fn($q) => $q->first()
            );
    }
}

if (!function_exists('isSeeding')) {
    function isSeeding(): bool
    {
        return app()['seeding'] ?? false;
    }
}

if (class_exists('\Laravel\Nova\Http\Requests\NovaRequest') && !function_exists('novaRequest')) {
    /** @return \Laravel\Nova\Http\Requests\NovaRequest */
    function novaRequest()
    {
        return \Laravel\Nova\Http\Requests\NovaRequest::createFrom(request());
    }
}

if (!function_exists('rescueQuietly')) {
    function rescueQuietly(callable $try, ?callable $catch = null)
    {
        return rescue($try, $catch, false);
    }
}

if (!function_exists('router')) {
    function router(): Router
    {
        return Route::getFacadeRoot();
    }
}

if (!function_exists('setIsSeeding')) {
    function setIsSeeding(bool $seeding = true): void
    {
        app()->singleton('isSeeding', function () use ($seeding) {
            return $seeding;
        });
    }
}

if (!function_exists('tableColumns')) {
    function tableColumns(string $tableName, ?string $connection = null): array
    {
        $connection = $connection ?? config('database.default');

        return Schema::connection($connection)->getColumnListing($tableName);
    }
}

if (!function_exists('tableHasColumn')) {
    function tableHasColumn(string $tableName, string $columnName, ?string $connection = null): bool
    {
        return in_array($columnName, tableColumns($tableName, $connection), true);
    }
}

if (!function_exists('toIterable')) {
    function toIterable(mixed $value): iterable
    {
        return match (true) {
            is_iterable($value) => $value,
            default => [$value],
        };
    }
}

if (!function_exists('when')) {
    /**
     * Can pass an iterable of predicates to $when
     */
    function when(bool|iterable|Closure $when, mixed $then = null, mixed $else = null): mixed
    {
        if (count(array_filter(func_get_args())) === 1) {
            $then = true;
            $else = false;
        }

        if (!is_iterable($when)) {
            $when = [$when];
        }

        foreach ($when as $predicate) {
            if (false === value($predicate)) {
                return is_null($else) ? false : value($else);
            }

            return value($then);
        }
    }
}
