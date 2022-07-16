<?php

namespace Jhavenz\LaravelHelpers\Traits;

use Illuminate\Support\Facades\Hash;

trait HasHashedPassword
{
    abstract public function password(): ?string;
    
    abstract public function passwordAttribute(): string;

    public function hashPassword(): static
    {
        if (!$this->passwordIsHashed()) {
            $this->{$this->passwordAttribute()} = Hash::make($this->password());
        }

        return $this;
    }

    public function passwordIsHashed(): bool
    {
        return is_string($pwd = $this->password()) &&
            strlen($pwd) >= 6 &&
            substr($pwd, 0, 5) === substr(Hash::make('foobar'), 0, 5);
    }
}
