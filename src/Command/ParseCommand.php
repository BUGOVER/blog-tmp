<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\NewsGrabber;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'parse:crawler',
    description: 'Add a short description for your command',
)]
class ParseCommand extends Command
{
    public function __construct(private readonly NewsGrabber $grabber)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->grabber->importNews();

        return Command::SUCCESS;
    }
}
