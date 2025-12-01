<?php

namespace App\Days\First\Listener;

use App\Days\First\Event\DialTurnedEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final readonly class DialListener
{
    #[AsEventListener]
    public function onAfterDialTurned(DialTurnedEvent $event): void
    {
        $rotations = $event->rotations;
        ($event->madeARotation() || $event->endedOnZero()) && $rotations++;
        $event->dial->counter->increment($rotations);
    }
}
