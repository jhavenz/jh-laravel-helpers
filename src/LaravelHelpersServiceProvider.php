<?php

namespace Jhavenz\LaravelHelpers;

use Illuminate\Database\Eloquent\Builder;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelHelpersServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('jhavenz-laravel-helpers');
    }

    public function packageBooted()
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
