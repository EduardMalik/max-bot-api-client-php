<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Laravel;

use BushlanovDev\MaxMessengerBot\Api;
use BushlanovDev\MaxMessengerBot\Enums\UpdateType;
use BushlanovDev\MaxMessengerBot\Exceptions\SecurityException;
use BushlanovDev\MaxMessengerBot\Exceptions\SerializationException;
use BushlanovDev\MaxMessengerBot\Models\Updates\AbstractUpdate;
use BushlanovDev\MaxMessengerBot\UpdateDispatcher;
use BushlanovDev\MaxMessengerBot\WebhookHandler;
use BushlanovDev\MaxMessengerBot\LongPollingHandler;
use GuzzleHttp\Psr7\ServerRequest;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Max Bot Manager for Laravel integration.
 *
 * Provides convenient methods for integrating Max Bot with Laravel applications.
 * Handles webhook processing, long polling, and event dispatching within Laravel context.
 */
class MaxBotManager
{
    /**
     * @var Container
     * @readonly
     */
    private $container;
    /**
     * @var Api
     * @readonly
     */
    private $api;
    /**
     * @var UpdateDispatcher
     * @readonly
     */
    private $dispatcher;
    /**
     * @param Container $container
     * @param Api $api
     * @param UpdateDispatcher $dispatcher
     */
    public function __construct(Container $container, Api $api, UpdateDispatcher $dispatcher)
    {
        $this->container = $container;
        $this->api = $api;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Handle webhook request in Laravel controller.
     *
     * Example usage in a controller:
     * ```php
     * public function webhook(Request $request, MaxBotManager $botManager)
     * {
     *     return $botManager->handleWebhook($request);
     * }
     * ```
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function handleWebhook($request)
    {
        try {
            /** @var WebhookHandler $webhookHandler */
            $webhookHandler = $this->container->make(WebhookHandler::class);

            $headers = array_map(function ($values) {
                return array_filter($values, function ($value) {
                    return $value !== null;
                });
            }, $request->headers->all());

            $webhookHandler->handle(
                new ServerRequest(
                    $request->getMethod(),
                    $request->getUri(),
                    $headers,
                    $request->getContent(),
                    $request->getProtocolVersion() ?? '1.1'
                )
            );

            return new Response('', 200);
        } catch (SecurityException $e) {
            Log::warning("Webhook security error: {$e->getMessage()}", [
                'exception' => $e,
                'headers' => $request->headers->all(),
            ]);

            return new JsonResponse([
                'status' => 'error',
                'message' => 'Forbidden',
            ], 403);
        } catch (SerializationException $e) {
            Log::error("Webhook serialization error: {$e->getMessage()}", [
                'exception' => $e,
                'request_content' => $request->getContent(),
            ]);

            return new JsonResponse([
                'status' => 'error',
                'message' => 'Bad Request',
            ], 400);
        } catch (Throwable $e) {
            Log::error("Webhook processing error: {$e->getMessage()}", [
                'exception' => $e,
                'request_content' => $request->getContent(),
                'headers' => $request->headers->all(),
            ]);

            return new JsonResponse([
                'status' => 'error',
                'message' => 'Internal Server Error',
            ], 500);
        }
    }

    /**
     * Start long polling in Laravel context.
     * This method should be called from a Laravel command or job.
     * It will run indefinitely until stopped.
     *
     * @throws BindingResolutionException
     * @codeCoverageIgnore
     * @param int $timeout
     * @param int|null $marker
     */
    public function startLongPolling($timeout = 90, $marker = null): void
    {
        /** @var LongPollingHandler $longPolling */
        $longPolling = $this->container->make(LongPollingHandler::class);

        $longPolling->handle($timeout, $marker);
    }

    /**
     * Registers a handler for a specific update type.
     *
     * @param UpdateType $type The type of update to handle.
     * @param callable|string $handler Can be a closure, callable, or Laravel container binding.
     *
     * @throws BindingResolutionException
     * @codeCoverageIgnore
     */
    public function addHandler($type, $handler): void
    {
        $this->dispatcher->addHandler($type, $this->resolveHandler($handler));
    }

    /**
     * Register a command handler.
     *
     * @param string $command Command name (without slash)
     * @param callable|string $handler Can be a closure, callable, or Laravel container binding.
     *
     * @throws BindingResolutionException
     * @codeCoverageIgnore
     */
    public function onCommand($command, $handler): void
    {
        $this->dispatcher->onCommand($command, $this->resolveHandler($handler));
    }

    /**
     * Register a message created handler.
     *
     * @param callable|string $handler Can be a closure, callable, or Laravel container binding.
     *
     * @throws BindingResolutionException
     * @codeCoverageIgnore
     */
    public function onMessageCreated($handler): void
    {
        $this->dispatcher->onMessageCreated($this->resolveHandler($handler));
    }

    /**
     * Register a callback handler.
     *
     * @param callable|string $handler Can be a closure, callable, or Laravel container binding.
     *
     * @throws BindingResolutionException
     * @codeCoverageIgnore
     */
    public function onMessageCallback($handler): void
    {
        $this->dispatcher->onMessageCallback($this->resolveHandler($handler));
    }

    /**
     * Register a message edited handler.
     *
     * @param callable|string $handler Can be a closure, callable, or Laravel container binding.
     *
     * @throws BindingResolutionException
     * @codeCoverageIgnore
     */
    public function onMessageEdited($handler): void
    {
        $this->dispatcher->onMessageEdited($this->resolveHandler($handler));
    }

    /**
     * Register a message removed handler.
     *
     * @param callable|string $handler Can be a closure, callable, or Laravel container binding.
     *
     * @throws BindingResolutionException
     * @codeCoverageIgnore
     */
    public function onMessageRemoved($handler): void
    {
        $this->dispatcher->onMessageRemoved($this->resolveHandler($handler));
    }

    /**
     * Register a bot added handler.
     *
     * @param callable|string $handler Can be a closure, callable, or Laravel container binding.
     *
     * @throws BindingResolutionException
     * @codeCoverageIgnore
     */
    public function onBotAdded($handler): void
    {
        $this->dispatcher->onBotAdded($this->resolveHandler($handler));
    }

    /**
     * Register a bot removed handler.
     *
     * @param callable|string $handler Can be a closure, callable, or Laravel container binding.
     *
     * @throws BindingResolutionException
     * @codeCoverageIgnore
     */
    public function onBotRemoved($handler): void
    {
        $this->dispatcher->onBotRemoved($this->resolveHandler($handler));
    }

    /**
     * Register a dialog mute handler.
     *
     * @param callable|string $handler Can be a closure, callable, or Laravel container binding.
     *
     * @throws BindingResolutionException
     * @codeCoverageIgnore
     */
    public function onDialogMuted($handler): void
    {
        $this->dispatcher->onDialogMuted($this->resolveHandler($handler));
    }

    /**
     * Register a dialog unmute handler.
     *
     * @param callable|string $handler Can be a closure, callable, or Laravel container binding.
     *
     * @throws BindingResolutionException
     * @codeCoverageIgnore
     */
    public function onDialogUnmuted($handler): void
    {
        $this->dispatcher->onDialogUnmuted($this->resolveHandler($handler));
    }

    /**
     * Register a dialog cleared handler.
     *
     * @param callable|string $handler Can be a closure, callable, or Laravel container binding.
     *
     * @throws BindingResolutionException
     * @codeCoverageIgnore
     */
    public function onDialogCleared($handler): void
    {
        $this->dispatcher->onDialogCleared($this->resolveHandler($handler));
    }

    /**
     * Register a dialog removed handler.
     *
     * @param callable|string $handler Can be a closure, callable, or Laravel container binding.
     *
     * @throws BindingResolutionException
     * @codeCoverageIgnore
     */
    public function onDialogRemoved($handler): void
    {
        $this->dispatcher->onDialogRemoved($this->resolveHandler($handler));
    }

    /**
     * Register a user added handler.
     *
     * @param callable|string $handler Can be a closure, callable, or Laravel container binding.
     *
     * @throws BindingResolutionException
     * @codeCoverageIgnore
     */
    public function onUserAdded($handler): void
    {
        $this->dispatcher->onUserAdded($this->resolveHandler($handler));
    }

    /**
     * Register a user removed handler.
     *
     * @param callable|string $handler Can be a closure, callable, or Laravel container binding.
     *
     * @throws BindingResolutionException
     * @codeCoverageIgnore
     */
    public function onUserRemoved($handler): void
    {
        $this->dispatcher->onUserRemoved($this->resolveHandler($handler));
    }

    /**
     * Register a bot started handler.
     *
     * @param callable|string $handler Can be a closure, callable, or Laravel container binding.
     *
     * @throws BindingResolutionException
     * @codeCoverageIgnore
     */
    public function onBotStarted($handler): void
    {
        $this->dispatcher->onBotStarted($this->resolveHandler($handler));
    }

    /**
     * Register a bot stopped handler.
     *
     * @param callable|string $handler Can be a closure, callable, or Laravel container binding.
     *
     * @throws BindingResolutionException
     * @codeCoverageIgnore
     */
    public function onBotStopped($handler): void
    {
        $this->dispatcher->onBotStopped($this->resolveHandler($handler));
    }

    /**
     * Register a chat title changed handler.
     *
     * @param callable|string $handler Can be a closure, callable, or Laravel container binding.
     *
     * @throws BindingResolutionException
     * @codeCoverageIgnore
     */
    public function onChatTitleChanged($handler): void
    {
        $this->dispatcher->onChatTitleChanged($this->resolveHandler($handler));
    }

    /**
     * Register a message chat created handler.
     *
     * @param callable|string $handler Can be a closure, callable, or Laravel container binding.
     *
     * @throws BindingResolutionException
     * @codeCoverageIgnore
     */
    public function onMessageChatCreated($handler): void
    {
        $this->dispatcher->onMessageChatCreated($this->resolveHandler($handler));
    }

    /**
     * Get the API instance.
     * @codeCoverageIgnore
     */
    public function getApi(): Api
    {
        return $this->api;
    }

    /**
     * Get the update dispatcher.
     * @codeCoverageIgnore
     */
    public function getDispatcher(): UpdateDispatcher
    {
        return $this->dispatcher;
    }

    /**
     * Resolve a handler that might be a Laravel container binding.
     *
     * @param callable|string $handler
     * @return callable
     * @phpstan-return callable(AbstractUpdate, Api): void
     * @throws BindingResolutionException
     */
    private function resolveHandler($handler): callable
    {
        if (is_string($handler)) {
            if ($this->container->bound($handler)) {
                $resolved = $this->container->make($handler);

                if (is_callable($resolved)) {
                    return $resolved;
                }

                if (is_object($resolved) && method_exists($resolved, 'handle')) {
                    /** @var callable */
                    return [$resolved, 'handle'];
                }

                throw new \InvalidArgumentException(
                    "Handler class '$handler' is not callable and doesn't have a handle method."
                );
            }

            if (strpos($handler, '@') !== false) {
                [$class, $method] = explode('@', $handler, 2);
                $instance = $this->container->make($class);
                /** @var callable */
                return [$instance, $method];
            }

            if (class_exists($handler)) {
                $instance = $this->container->make($handler);
                if (method_exists($instance, 'handle')) {
                    /** @var callable */
                    return [$instance, 'handle'];
                }
            }

            throw new \InvalidArgumentException("Unable to resolve handler: $handler");
        }

        return $handler;
    }
}
