<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\NewsGrabber;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'parse:crawler',
    description: 'Add a short description for your command',
)]
class ParseCommand extends Command
{
    use LockableTrait;

    public function __construct(private readonly NewsGrabber $grabber)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('count', InputArgument::OPTIONAL)
            ->addOption('dryRun', null, InputOption::VALUE_OPTIONAL);
    }

    /**
     * @throws GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($output->isVerbose()) {
            $logger = new ConsoleLogger($output);
            $this->grabber->setLogger($logger);
        }

        $this->grabber->importNews();

        $this->release();

        return Command::SUCCESS;
    }
}
