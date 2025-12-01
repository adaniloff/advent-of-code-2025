<?php

namespace App\Runner;

use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

readonly class DayRunner
{
    private array $days;

    public function __construct(#[AutowireIterator('day.executable')] iterable $days)
    {
        $this->days = iterator_to_array($days);
    }

    public function exec(int $day): Execution
    {
        $execution = match ($day) {
            1, 2 => $this->days[$day - 1],
            default => $this->isValid($day)
                ? false : throw new \InvalidArgumentException(sprintf('Invalid day: "%s".', $day)),
        };

        $execution || throw new \RuntimeException(sprintf('Valid day "%s", but an unexpected exception occurred (no match for this day).', $day));

        return $execution;
    }

    public function isValid(int $day): bool
    {
        return in_array($day, array_keys(array_fill(1, 24, '~')));
    }
}
