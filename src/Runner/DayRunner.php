<?php

namespace App\Runner;

use App\Days\First\FirstExecution;

readonly class DayRunner
{
    public function exec(int $day): Execution
    {
        $execution = match ($day) {
            1 => new FirstExecution(),
            default => $this->isValid($day)
                ? false : throw new \InvalidArgumentException(sprintf('Invalid day: "%s".', $day)),
        };

        $execution || throw new \RuntimeException(sprintf(
            'Valid day "%s", but an unexpected exception occurred (no match for this day).',
            $day
        ));

        return $execution;
    }

    public function isValid(int $day): bool
    {
        return in_array($day, array_keys(array_fill(1, 24, '~')));
    }
}
