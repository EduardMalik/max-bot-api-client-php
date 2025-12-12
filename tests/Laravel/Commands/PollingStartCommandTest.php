<?php

namespace BushlanovDev\MaxMessengerBot\Tests\Laravel\Commands;

use BushlanovDev\MaxMessengerBot\Laravel\Commands\PollingStartCommand;
use BushlanovDev\MaxMessengerBot\Laravel\MaxBotManager;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Tester\CommandTester;

final class PollingStartCommandTest extends TestCase
{
    /**
     * @var (\BushlanovDev\MaxMessengerBot\Laravel\MaxBotManager & \PHPUnit\Framework\MockObject\MockObject)
     */
    private $botManagerMock;
    /**
     * @var \BushlanovDev\MaxMessengerBot\Laravel\Commands\PollingStartCommand
     */
    private $command;
    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->botManagerMock = $this->createMock(MaxBotManager::class);
        $this->container->instance(MaxBotManager::class, $this->botManagerMock);
        $this->container->alias(MaxBotManager::class, 'maxbot.manager');

        $this->command = new PollingStartCommand();
        $this->command->setLaravel($this->container);

        $application = new ConsoleApplication();
        $application->add($this->command);
        $commandInApp = $application->find('maxbot:polling:start');
        $this->tester = new CommandTester($commandInApp);
    }
    /**
     * @return void
     */
    public function handleSuccessfullyCallsManagerWithCustomTimeout()
    {
        $timeout = 60;
        $this->botManagerMock
            ->expects($this->once())
            ->method('startLongPolling')
            ->with($timeout);
        $this->tester->execute(['--timeout' => $timeout]);
        $this->tester->assertCommandIsSuccessful();
        $output = $this->tester->getDisplay();
        $this->assertStringContainsString("Starting long polling with a timeout of $timeout seconds...", $output);
    }
    /**
     * @return void
     */
    public function handleSuccessfullyUsesDefaultTimeout()
    {
        $defaultTimeout = 90;
        $this->botManagerMock
            ->expects($this->once())
            ->method('startLongPolling')
            ->with($defaultTimeout);
        $this->tester->execute([]);
        $this->tester->assertCommandIsSuccessful();
        $output = $this->tester->getDisplay();
        $this->assertStringContainsString(
            "Starting long polling with a timeout of $defaultTimeout seconds...",
            $output
        );
    }
    /**
     * @return void
     */
    public function handleCatchesExceptionAndLogsError()
    {
        $exceptionMessage = 'Something went wrong';
        $exception = new \RuntimeException($exceptionMessage);
        $this->botManagerMock
            ->expects($this->once())
            ->method('startLongPolling')
            ->willThrowException($exception);
        Log::shouldReceive('error')
            ->once()
            ->with(
                "Long polling failed to start or crashed: $exceptionMessage",
                ['exception' => $exception]
            );
        $statusCode = $this->tester->execute([]);
        $this->assertSame(1, $statusCode, 'Command should return a failure exit code.');
        $output = $this->tester->getDisplay();
        $this->assertStringContainsString("❌ Long polling failed: $exceptionMessage", $output);
    }
}
