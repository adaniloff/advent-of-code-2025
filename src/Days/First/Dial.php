<?php

namespace App\Days\First;

use App\Days\First\Event\DialTurnedEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class Dial
{
    public readonly RotationCounter $counter;

    private const int MIN_POSITION = 0;
    private const int MAX_POSITION = 99;

    private int $position = 0;

    public function __construct(private readonly EventDispatcherInterface $dispatcher)
    {
        $this->counter = new RotationCounter();
    }

    public function left(int $steps = 1): self
    {
        $steps = abs($steps);
        $rotations = (int) ($steps / (self::MAX_POSITION + 1));

        if ($steps > self::MAX_POSITION) {
            $steps -= (self::MAX_POSITION + 1) * $rotations;
        }

        $nextPosition = $this->position - $steps;
        if ($nextPosition < self::MIN_POSITION) {
            $nextPosition = self::MAX_POSITION + 1 - abs($nextPosition);
        }
        $this->dispatcher->dispatch(event: DialTurnedEvent::left(
            dial: $this,
            from: $this->position,
            to: $nextPosition,
            rotations: $rotations,
        ));
        $this->position = $nextPosition;

        return $this;
    }

    public function right(int $steps = 1): self
    {
        $steps = abs($steps);
        $rotations = (int) ($steps / (self::MAX_POSITION + 1));

        if ($steps > self::MAX_POSITION) {
            $steps -= (self::MAX_POSITION + 1) * $rotations;
        }

        $nextPosition = $this->position + $steps;
        if ($nextPosition > self::MAX_POSITION) {
            $nextPosition -= self::MAX_POSITION + 1;
        }

        $this->dispatcher->dispatch(event: DialTurnedEvent::right(
            dial: $this,
            from: $this->position,
            to: $nextPosition,
            rotations: $rotations,
        ));
        $this->position = $nextPosition;

        return $this;
    }

    public function position(): int
    {
        return $this->position;
    }

    public function rotations(): int
    {
        return $this->counter->rotations();
    }

    public function play(array $instructions, int $startingPosition): self
    {
        $this->position = $startingPosition;

        foreach ($instructions as $instruction) {
            $direction = substr($instruction, 0, 1);
            $steps = substr($instruction, 1);
            match ($direction) {
                'L' => $this->left((int) $steps),
                'R' => $this->right((int) $steps),
            };
        }

        return $this;
    }
}
