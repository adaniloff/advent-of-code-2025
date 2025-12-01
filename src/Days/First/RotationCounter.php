<?php

namespace App\Days\First;

final class RotationCounter
{
    private int $count = 0;

    public function __construct()
    {
    }

    public function increment(int $value = 1): self
    {
        $this->count += $value;

        return $this;
    }

    public function rotations(): int
    {
        return $this->count;
    }
}
