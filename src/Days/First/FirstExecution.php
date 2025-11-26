<?php

namespace App\Days\First;

use App\Runner\Execution;

final readonly class FirstExecution extends Execution
{
    public function __toString(): string
    {
        return 'done';
    }
}
