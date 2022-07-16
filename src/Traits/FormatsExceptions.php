<?php

namespace Jhavenz\LaravelHelpers\Traits;

use Illuminate\Support\Arr;
use Spatie\Backtrace\Backtrace;
use Spatie\Backtrace\Frame;
use Throwable;

class FormatsExceptions
{
    /**
     * Provides a nicer, readable stack trace that allows method arguments to be seen while in array format.
     */
    public function convertExceptionToArray(Throwable $e, int $maxFrames = 20): array
    {
        return [
            'class' => get_class($e),
            'code' => $e->getCode(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'message' => str_contains($msg = $e->getMessage(), PHP_EOL) ? explode(PHP_EOL, $msg) : $msg,
            'previous' => empty($e->getPrevious())
                ? null
                : $this->convertExceptionToArray(
                    $e->getPrevious(),
                    $maxFrames
                ),
            'trace' => collect(Backtrace::createForThrowable($e)->limit($maxFrames)->frames())
                ->map(function (Frame $frame, $idx) {
                    $displayArgs = (bool)config('jhavenz-laravel-helpers.exception_formatting.display_arguments', true);
                    
                    return array_filter([
                        'position' => $idx + 1,
                        'class' => $frame->class,
                        'method' => $frame->method,
                        'line' => $frame->lineNumber,
                        'args' => $displayArgs
                            ? array_map(fn($arg) => is_object($arg)
                                ? get_class($arg)
                                : $arg,
                                Arr::flatten($frame->arguments)
                            )
                            : [],
                    ]);
                })
                ->all(),
        ];
    }
}
