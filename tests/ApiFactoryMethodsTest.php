<?php

namespace BushlanovDev\MaxMessengerBot\Tests;

use BushlanovDev\MaxMessengerBot\Api;
use BushlanovDev\MaxMessengerBot\ClientApiInterface;
use BushlanovDev\MaxMessengerBot\LongPollingHandler;
use BushlanovDev\MaxMessengerBot\ModelFactory;
use BushlanovDev\MaxMessengerBot\UpdateDispatcher;
use BushlanovDev\MaxMessengerBot\WebhookHandler;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use ReflectionClass;

final class ApiFactoryMethodsTest extends TestCase
{
    /**
     * @var (\BushlanovDev\MaxMessengerBot\ClientApiInterface & \PHPUnit\Framework\MockObject\MockObject)
     */
    private $clientMock;
    /**
     * @var (\BushlanovDev\MaxMessengerBot\ModelFactory & \PHPUnit\Framework\MockObject\MockObject)
     */
    private $modelFactoryMock;
    /**
     * @var (\PHPUnit\Framework\MockObject\MockObject & \Psr\Log\LoggerInterface)
     */
    private $loggerMock;
    /**
     * @var \BushlanovDev\MaxMessengerBot\Api
     */
    private $api;
    /**
     * @return void
     */
    protected function setUp()
    {
        $this->clientMock = $this->createMock(ClientApiInterface::class);
        $this->modelFactoryMock = $this->createMock(ModelFactory::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);

        $this->api = new Api(
            'fake-token',
            $this->clientMock,
            $this->modelFactoryMock,
            $this->loggerMock
        );
    }
    /**
     * @return void
     */
    public function createWebhookHandlerReturnsCorrectlyConfiguredInstance()
    {
        $secret = 'my-test-secret';
        $webhookHandler = $this->api->createWebhookHandler($secret);
        $this->assertInstanceOf(WebhookHandler::class, $webhookHandler);
        $this->assertSame(
            $this->getPrivateProperty($this->api, 'updateDispatcher'),
            $this->getPrivateProperty($webhookHandler, 'dispatcher')
        );
        $this->assertSame(
            $this->getPrivateProperty($this->api, 'modelFactory'),
            $this->getPrivateProperty($webhookHandler, 'modelFactory')
        );
        $this->assertSame(
            $this->getPrivateProperty($this->api, 'logger'),
            $this->getPrivateProperty($webhookHandler, 'logger')
        );
        $this->assertSame(
            $secret,
            $this->getPrivateProperty($webhookHandler, 'secret')
        );
    }
    /**
     * @return void
     */
    public function createLongPollingHandlerReturnsCorrectlyConfiguredInstance()
    {
        $longPollingHandler = $this->api->createLongPollingHandler();
        $this->assertInstanceOf(LongPollingHandler::class, $longPollingHandler);
        $this->assertSame(
            $this->api,
            $this->getPrivateProperty($longPollingHandler, 'api')
        );
        $this->assertSame(
            $this->getPrivateProperty($this->api, 'updateDispatcher'),
            $this->getPrivateProperty($longPollingHandler, 'dispatcher')
        );
        $this->assertSame(
            $this->getPrivateProperty($this->api, 'logger'),
            $this->getPrivateProperty($longPollingHandler, 'logger')
        );
    }
    /**
     * @return mixed
     * @param object $object
     * @param string $propertyName
     */
    private function getPrivateProperty($object, $propertyName)
    {
        $propertyName = (string) $propertyName;
        $reflection = new ReflectionClass($object);
        $property = $reflection->getProperty($propertyName);

        return $property->getValue($object);
    }
}
