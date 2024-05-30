<?php

// tests/Command/LogFileCommandTest.php

namespace App\Tests\Command;

use App\Command\LogFileCommand;
use App\Message\LogFileMessage;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Messenger\MessageBusInterface;

class LogFileCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();
        $bus = $this->createMock(MessageBusInterface::class);

        $bus->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(LogFileMessage::class));

        $command = new LogFileCommand($bus);
        $commandTester = new CommandTester($command);
        $commandTester->execute(['filePath' => 'logs.log']);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Log file processed successfully.', $output);
    }
}