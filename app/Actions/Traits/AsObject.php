<?php

namespace App\Actions\Traits;

trait AsObject
{
    /**
     * @return static
     */
    public static function make()
    {
        return app(static::class);
    }

    /**
     * @see static::handle()
     *
     * @param  mixed  ...$arguments
     * @return mixed
     */
    public static function run(...$arguments)
    {
        return static::make()->handle(...$arguments);
    }
}
