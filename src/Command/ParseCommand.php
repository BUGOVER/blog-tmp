<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\NewsGrabber;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

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

    /**
     * @throws GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $logger = new ConsoleLogger($output);
        $io = new SymfonyStyle($input, $output);

        $this->grabber
            ->setLogger($logger)
            ->importNews();

        $io->info('SUCCESS');

        return Command::SUCCESS;
    }
}
