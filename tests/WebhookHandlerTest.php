<?php

namespace BushlanovDev\MaxMessengerBot\Tests;

use BushlanovDev\MaxMessengerBot\Api;
use BushlanovDev\MaxMessengerBot\Enums\ChatType;
use BushlanovDev\MaxMessengerBot\Enums\UpdateType;
use BushlanovDev\MaxMessengerBot\Exceptions\SecurityException;
use BushlanovDev\MaxMessengerBot\Exceptions\SerializationException;
use BushlanovDev\MaxMessengerBot\ModelFactory;
use BushlanovDev\MaxMessengerBot\Models\Message;
use BushlanovDev\MaxMessengerBot\Models\MessageBody;
use BushlanovDev\MaxMessengerBot\Models\Recipient;
use BushlanovDev\MaxMessengerBot\Models\Updates\AbstractUpdate;
use BushlanovDev\MaxMessengerBot\Models\Updates\MessageCreatedUpdate;
use BushlanovDev\MaxMessengerBot\UpdateDispatcher;
use BushlanovDev\MaxMessengerBot\WebhookHandler;
use LogicException;
use phpmock\phpunit\PHPMock;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Log\LoggerInterface;

final class WebhookHandlerTest extends TestCase
{
    use PHPMock;
    const SECRET = 'my-secret-key';
    /**
     * @var (\BushlanovDev\MaxMessengerBot\Api & \PHPUnit\Framework\MockObject\MockObject)
     */
    private $apiMock;
    /**
     * @var (\BushlanovDev\MaxMessengerBot\ModelFactory & \PHPUnit\Framework\MockObject\MockObject)
     */
    private $modelFactoryMock;
    /**
     * @var \BushlanovDev\MaxMessengerBot\UpdateDispatcher
     */
    private $dispatcher;
    /**
     * @var (\PHPUnit\Framework\MockObject\MockObject & \Psr\Log\LoggerInterface)
     */
    private $loggerMock;
    /**
     * @return void
     */
    protected function setUp()
    {
        $this->apiMock = $this->createMock(Api::class);
        $this->modelFactoryMock = $this->createMock(ModelFactory::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);
        $this->dispatcher = new UpdateDispatcher($this->apiMock);
    }
    /**
     * @return \BushlanovDev\MaxMessengerBot\Models\Updates\MessageCreatedUpdate
     */
    private function createValidUpdate()
    {
        $messageBody = new MessageBody('m.1', 1, 'Hi', null, null);
        $recipient = new Recipient(ChatType::Dialog, 1, null);
        $message = new Message(time(), $recipient, $messageBody, null, null, null, null);

        return new MessageCreatedUpdate(time(), $message, 'ru-RU');
    }
    /**
     * @param string $body
     * @param string $signature
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    private function createMockRequest($body, $signature)
    {
        $body = (string) $body;
        $signature = (string) $signature;
        $streamMock = $this->createMock(StreamInterface::class);
        $streamMock->method('__toString')->willReturn($body);

        $requestMock = $this->createMock(ServerRequestInterface::class);
        $requestMock->method('getBody')->willReturn($streamMock);
        $requestMock->method('getHeaderLine')->with('X-Max-Bot-Api-Secret')->willReturn($signature);

        return $requestMock;
    }
    /**
     * @param string|null $secret
     * @param string $signatureHeader
     * @return void
     */
    public function handleSuccessfulRequest($secret, $signatureHeader)
    {
        $payload = '{"update_type":"message_created","timestamp":123}';
        $updateData = json_decode($payload, true);
        $expectedUpdate = $this->createValidUpdate();
        $request = $this->createMockRequest($payload, $signatureHeader);
        $this->modelFactoryMock->expects($this->once())
            ->method('createUpdate')
            ->with($updateData)
            ->willReturn($expectedUpdate);
        $handlerWasCalled = false;
        $this->dispatcher->addHandler(UpdateType::MessageCreated, function () use (&$handlerWasCalled) {
            $handlerWasCalled = true;
        });
        $handler = new WebhookHandler($this->dispatcher, $this->modelFactoryMock, $this->loggerMock, $secret);
        $handler->handle($request);
        $this->assertTrue($handlerWasCalled, 'Dispatcher was not called on successful request.');
    }
    /**
     * @return mixed[]
     */
    public static function successfulRequestProvider()
    {
        return [
            'with correct secret' => [self::SECRET, self::SECRET],
            'with no secret configured' => [null, 'any-signature'],
        ];
    }
    /**
     * @return void
     */
    public function handleThrowsSecurityExceptionOnInvalidSignature()
    {
        $this->expectException(SecurityException::class);
        $request = $this->createMockRequest('{}', 'wrong-signature');
        $handler = new WebhookHandler($this->dispatcher, $this->modelFactoryMock, $this->loggerMock, self::SECRET);
        $handler->handle($request);
    }
    /**
     * @return void
     */
    public function handleLogsWarningOnSignatureFailure()
    {
        $this->loggerMock->expects($this->once())
            ->method('warning')
            ->with('Webhook signature verification failed', ['received_signature' => 'wrong-signature']);
        $request = $this->createMockRequest('{}', 'wrong-signature');
        $handler = new WebhookHandler($this->dispatcher, $this->modelFactoryMock, $this->loggerMock, self::SECRET);
        try {
            $handler->handle($request);
        } catch (SecurityException $exception) {
            // Expected
        }
    }
    /**
     * @return void
     */
    public function handleThrowsSerializationExceptionOnEmptyBody()
    {
        $this->expectException(SerializationException::class);
        $this->expectExceptionMessage('Webhook body is empty.');
        $request = $this->createMockRequest('', self::SECRET);
        $handler = new WebhookHandler($this->dispatcher, $this->modelFactoryMock, $this->loggerMock, self::SECRET);
        $handler->handle($request);
    }
    /**
     * @return void
     */
    public function handleThrowsSerializationExceptionOnInvalidJson()
    {
        $this->expectException(SerializationException::class);
        $this->expectExceptionMessage('Failed to decode webhook body as JSON.');
        $request = $this->createMockRequest('{invalid-json', self::SECRET);
        $handler = new WebhookHandler($this->dispatcher, $this->modelFactoryMock, $this->loggerMock, self::SECRET);
        $handler->handle($request);
    }
    /**
     * @return void
     */
    public function handleWithoutRequestThrowsLogicExceptionWhenGuzzleIsMissing()
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessageMatches('/No ServerRequest was provided and "guzzlehttp\/psr7" is not found/');
        $classExistsMock = $this->getFunctionMock('BushlanovDev\MaxMessengerBot', 'class_exists');
        $classExistsMock->expects($this->once())
            ->with(\GuzzleHttp\Psr7\ServerRequest::class)
            ->willReturn(false);
        $handler = new WebhookHandler($this->dispatcher, $this->modelFactoryMock, $this->loggerMock, null);
        $handler->handle(null);
    }
    /**
     * @return void
     */
    public function handleWithoutRequestWhenGuzzleIsPresent()
    {
        if (!class_exists(\GuzzleHttp\Psr7\ServerRequest::class)) {
            $this->markTestSkipped('guzzlehttp/psr7 is not installed, cannot run this test.');
        }
        $this->expectException(SerializationException::class);
        $this->expectExceptionMessage('Webhook body is empty.');
        $handler = new WebhookHandler($this->dispatcher, $this->modelFactoryMock, $this->loggerMock, null);
        $handler->handle(null);
    }
    /**
     * @return void
     */
    public function handleCatchesAndLogsLogicExceptionFromModelFactory()
    {
        $payload = '{"update_type":"unknown_type","timestamp":123}';
        $updateData = json_decode($payload, true);
        $exception = new LogicException('Unknown or unsupported update type received: unknown_type');
        $request = $this->createMockRequest($payload, self::SECRET);
        $this->modelFactoryMock->expects($this->once())
            ->method('createUpdate')
            ->with($updateData)
            ->willThrowException($exception);
        $callIndex = 0;
        $this->loggerMock->expects($this->exactly(2))
            ->method('debug')
            ->willReturnCallback(
                function ($message, array $context = []) use (&$callIndex, $payload, $exception) {
                    $message = (string) $message;
                    if ($callIndex === 0) {
                        $this->assertSame('Received webhook payload', $message);
                        $this->assertArrayHasKey('body', $context);
                        $this->assertSame($payload, $context['body']);
                    } elseif ($callIndex === 1) {
                        $this->assertSame('Unknown or unsupported update type received: unknown_type', $message);
                        $this->assertArrayHasKey('payload', $context);
                        $this->assertArrayHasKey('exception', $context);
                        $this->assertSame($payload, $context['payload']);
                        $this->assertSame($exception, $context['exception']);
                    }
                    $callIndex++;
                }
            );
        $handler = new WebhookHandler($this->dispatcher, $this->modelFactoryMock, $this->loggerMock, self::SECRET);
        $handler->handle($request);
    }
}
