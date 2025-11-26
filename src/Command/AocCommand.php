<?php

namespace App\Command;

use App\Runner\DayRunner;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'aoc:runner',
    description: 'Advent of Code Runner',
)]
final class AocCommand extends Command
{
    public function __construct(private DayRunner $runner)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument(
                name: 'day',
                mode: InputArgument::REQUIRED,
                description: 'The day of the month',
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $day = (int) $input->getArgument('day');

        if (!$this->runner->isValid(day: $day)) {
            $io->error('Invalid "day" argument.');

            return Command::FAILURE;
        }
        $io->note(sprintf('Now setting up the script for the day: %s ...', $day));

        $execution = $this->runner->exec(day: $day);

        $io->success(sprintf('Success: %s', $execution));

        return Command::SUCCESS;
    }
}
