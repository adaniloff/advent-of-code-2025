<?php

namespace App\Tests\Runner;

use App\Runner\DayRunner;
use App\Runner\Execution;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @coversDefaultClass \App\Runner\DayRunner
 */
final class DayRunnerTest extends KernelTestCase
{
    private DayRunner $dayRunner;

    protected function setUp(): void
    {
        $this->dayRunner = self::getContainer()->get(DayRunner::class);
    }

    public function testExecutionWithInvalidDayFailure(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid day: "0"');
        $this->dayRunner->exec(0);
    }

    public function testExecutionWithDayNotSupportedYetFailure(): void
    {
        // Arrange
        $mock = $this->getMockBuilder(DayRunner::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['isValid'])
            ->getMock();
        $mock->expects($this->any())
            ->method('isValid')
            ->willReturn(true);

        // Assert
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Valid day "30", but an unexpected exception occurred (no match for this day).');

        // Act
        $mock->exec(30);
    }

    public function testExecutionWithSupportedDay(): void
    {
        $this->assertInstanceOf(Execution::class, $this->dayRunner->exec(1));
    }

    #[DataProvider('days')]
    public function testValidity(mixed $day, bool $expected): void
    {
        $this->assertEquals($expected, $this->dayRunner->isValid(day: $day));
    }

    public static function days(): array
    {
        return [
            [-1, false],
            [0, false],
            [1, true],
            [2, true],
            [3, true],
            [4, true],
            [5, true],
            [6, true],
            [7, true],
            [8, true],
            [9, true],
            [10, true],
            [11, true],
            [12, true],
            [13, true],
            [14, true],
            [15, true],
            [16, true],
            [17, true],
            [18, true],
            [19, true],
            [20, true],
            [21, true],
            [22, true],
            [23, true],
            [24, true],
            [25, false],
        ];
    }
}
