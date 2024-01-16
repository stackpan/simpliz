<?php

namespace App\Data;

interface ValidatedCreatable
{
    public static function createFromValidated(array $validated);
}
