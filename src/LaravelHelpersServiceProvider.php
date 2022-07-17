<?php

namespace Jhavenz\LaravelHelpers;

use DeepCopy\DeepCopy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelHelpersServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('jh-laravel-helpers');
    }

    public function packageBooted()
    {
        $this->registerArrayMacros();
        $this->registerEloquentBuilderMacros();
    }

    /**
     * @return void
     */
    private function registerArrayMacros(): void
    {
        Arr::macro('remove', function (array $array, string|int|object|float|bool $target) {
            $copy = (new DeepCopy())->copy($array);

            foreach ($array as $key => $value) {
                if (Arr::wrap($value) == Arr::wrap($target)) {
                    unset($copy[$key]);
                }
            }

            return $copy;
        });

        Arr::macro('whereNotEmpty', function (array $array, bool $checkForEmptyKeys = true) {
            return Arr::where($array, function ($item, $key) use ($checkForEmptyKeys) {
                if ($checkForEmptyKeys && 0 !== $key && empty($key)) {
                    return false;
                }

                return !empty($item);
            });
        });

        Arr::macro('isEmpties', function (array $array, bool $checkForEmptyKeys = true) {
            return 0 === count(Arr::whereNotEmpty(...func_get_args()));
        });
    }

    /**
     * @return void
     */
    private function registerEloquentBuilderMacros(): void
    {
        Builder::macro('rand', function (int $count = 1) {
            /** @var Builder $this */
            return $this
                ->inRandomOrder()
                ->when(
                    $count !== 1,
                    fn($q) => $q->limit($count)->get(),
                    fn($q) => $q->first()
                );
        });
    }
}
