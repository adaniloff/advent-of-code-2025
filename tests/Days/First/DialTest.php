<?php

namespace App\Tests\Days\First;

use App\Days\First\Dial;
use App\Days\First\FirstExecution;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @coversDefaultClass \App\Days\First\Dial
 */
final class DialTest extends KernelTestCase
{
    private const array EXAMPLE_INSTRUCTIONS = [
        'L68',
        'L30',
        'R48',
        'L5',
        'R60',
        'L55',
        'L1',
        'L99',
        'R14',
        'L82',
    ];

    private Dial $dial;

    protected function setUp(): void
    {
        $this->dial = self::getContainer()->get(Dial::class);
    }

    public function testRight(): void
    {
        $this->dial->right(3);

        $this->assertEquals(3, $this->dial->position());
        $this->assertEquals(0, $this->dial->rotations());
    }

    public function testRightOverMax(): void
    {
        $this->dial->play(['R5'], startingPosition: 95);

        $this->assertEquals(0, $this->dial->position());
        $this->assertEquals(1, $this->dial->rotations());
    }

    public function testRightExactlyMax(): void
    {
        $this->dial->right(steps: 99);
        $this->assertEquals(99, $this->dial->position());
    }

    public function testLeft(): void
    {
        $this->dial->play(['L1'], startingPosition: 5);

        $this->assertEquals(4, $this->dial->position());
        $this->assertEquals(0, $this->dial->rotations());
    }

    public function testLeftUnderMax(): void
    {
        $this->dial->play(['L10'], startingPosition: 5);

        $this->assertEquals(95, $this->dial->position());
        $this->assertEquals(1, $this->dial->rotations());
    }

    public function testLeftExactlyMax(): void
    {
        $this->dial->play(['L99'], startingPosition: 99);

        $this->assertEquals(0, $this->dial->position());
    }

    public function testHugeRightOverMaxFromZero(): void
    {
        $this->dial->right(steps: 198);

        $this->assertEquals(98, $this->dial->position());
    }

    public function testHugeLeftUnderMax(): void
    {
        $this->dial->play(['L297'], startingPosition: 5);

        $this->assertEquals(8, $this->dial->position());
    }

    public function testNonRegExample(): void
    {
        $dial = $this->dial->play(instructions: self::EXAMPLE_INSTRUCTIONS, startingPosition: 50);
        $this->assertEquals(6, $dial->counter->rotations());
    }

    public function testNonRegProduction(): void
    {
        $this->assertEquals(5933, (string) new FirstExecution($this->dial));
    }
}
