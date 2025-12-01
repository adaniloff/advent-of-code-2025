<?php

namespace App\Days\First;

use App\Runner\Execution;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('day.executable')]
final readonly class FirstExecution extends Execution implements \Stringable
{
    public function __construct(private Dial $dial)
    {
    }

    public function __toString(): string
    {
        $content = file_get_contents(__DIR__.'/input.txt');
        $instructions = explode("\n", trim($content ?: ''));

        return $this->dial
            ->play(instructions: $instructions, startingPosition: 50)
            ->rotations();
    }
}
