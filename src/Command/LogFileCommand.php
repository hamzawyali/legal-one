<?php

namespace App\Command;

use App\Message\LogFileMessage;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:import-logfile',
    description: 'Process the log file incrementally .',
    hidden: false,
    aliases: ['app:import-logfile']
)]
class LogFileCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'import:logfile';

    /**
     * @param MessageBusInterface $bus
     */
    public function __construct(private MessageBusInterface $bus)
    {
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Process the log file incrementally')
            ->addArgument('filePath', InputArgument::REQUIRED, 'The path to the log file');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filePath = $input->getArgument('filePath');

        $this->bus->dispatch(new LogFileMessage($filePath));

        $output->writeln('Log file processed successfully.');

        return Command::SUCCESS;
    }
}