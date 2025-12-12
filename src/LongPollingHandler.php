<?php

namespace BushlanovDev\MaxMessengerBot;

use BushlanovDev\MaxMessengerBot\Exceptions\NetworkException;
use Psr\Log\LoggerInterface;

/**
 * Handles receiving updates via long polling.
 */
final class LongPollingHandler
{
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
     * @var LoggerInterface
     * @readonly
     */
    private $logger;
    /**
     * @param Api $api
     * @param UpdateDispatcher $dispatcher The update dispatcher.
     * @param LoggerInterface $logger PSR LoggerInterface.
     * @codeCoverageIgnore
     */
    public function __construct(
        Api $api,
        UpdateDispatcher $dispatcher,
        LoggerInterface $logger
    ) {
        $this->api = $api;
        $this->dispatcher = $dispatcher;
        $this->logger = $logger;
        if (!(\PHP_SAPI === 'cli')) {
            throw new \RuntimeException('LongPollingHandler can only be used in CLI mode.');
        }
    }

    /**
     * Processes a single batch of updates. Useful for custom loop implementations or for testing.
     *
     * @param int $timeout Timeout for the getUpdates call.
     * @param int|null $marker The marker for which updates to fetch.
     * @return int|null The new marker to be used for the next iteration.
     * @throws \Exception Re-throws exceptions from the API or dispatcher.
     */
    public function processUpdates($timeout, $marker)
    {
        $timeout = (int) $timeout;
        $updateList = $this->api->getUpdates(null, $timeout, $marker);

        foreach ($updateList->updates as $update) {
            try {
                $this->dispatcher->dispatch($update);
            } catch (\Exception $e) {
                $this->logger->error('Error dispatching update', [
                    'message' => $e->getMessage(),
                    'exception' => $e,
                ]);
            }
        }

        return $updateList->marker;
    }

    /**
     * Starts a long-polling loop to process updates.
     * This method will run indefinitely until the script is terminated.
     *
     * @param int $timeout Timeout in seconds for long polling (0-90).
     * @param int|null $marker Initial marker. Pass `null` to get updates you didn't get yet.
     * @return void
     */
    public function handle($timeout = 90, $marker = null)
    {
        $timeout = (int) $timeout;
        $this->listenSignals();
        // @phpstan-ignore-next-line
        while (true) {
            try {
                $marker = $this->processUpdates($timeout, $marker);
            } catch (NetworkException $e) {
                $this->logger->error(
                    'Long-polling network error: {message}',
                    ['message' => $e->getMessage(), 'exception' => $e]
                );
                sleep(5);
            } catch (\Exception $e) {
                $this->logger->error(
                    'An error occurred during long-polling: {message}',
                    ['message' => $e->getMessage(), 'exception' => $e]
                );
                sleep(1);
            }
        }
    }

    /**
     * @codeCoverageIgnore
     * @return void
     */
    protected function listenSignals()
    {

    }
}
