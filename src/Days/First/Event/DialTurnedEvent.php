<?php

namespace App\Days\First\Event;

use App\Days\First\Dial;

final readonly class DialTurnedEvent
{
    private function __construct(
        public Dial $dial,
        public int $from,
        public int $to,
        public int $rotations,
        public bool $clockwise,
    ) {
    }

    public static function left(
        Dial $dial,
        int $from,
        int $to,
        int $rotations,
    ): self {
        return new self(
            dial: $dial,
            from: $from,
            to: $to,
            rotations: $rotations,
            clockwise: false,
        );
    }

    public static function right(
        Dial $dial,
        int $from,
        int $to,
        int $rotations,
    ): self {
        return new self(
            dial: $dial,
            from: $from,
            to: $to,
            rotations: $rotations,
            clockwise: true,
        );
    }

    public function madeARotation(): bool
    {
        return match ($this->clockwise) {
            false => $this->to > $this->from && !$this->startedOnZero(),
            true => $this->to < $this->from && !$this->endedOnZero(),
        };
    }

    public function startedOnZero(): bool
    {
        return 0 === $this->from;
    }

    public function endedOnZero(): bool
    {
        return 0 === $this->to;
    }
}
