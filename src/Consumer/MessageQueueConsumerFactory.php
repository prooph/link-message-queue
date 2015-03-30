<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 3/30/15 - 12:17 AM
 */
namespace Prooph\Link\MessageQueue\Consumer;

use Bernard\Consumer;
use Bernard\Middleware\MiddlewareBuilder;
use Bernard\QueueFactory\PersistentFactory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class MessageQueueConsumerFactory
 *
 * @package Prooph\Link\MessageQueue\Consumer
 * @author Alexander Miertsch <contact@prooph.de>
 */
final class MessageQueueConsumerFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @throws \RuntimeException
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');

        if (! isset($config['prooph.link.message_queue'])) {
            throw new \RuntimeException('Missing prooph.link.message_queue key in application config.');
        }

        $config = $config['prooph.link.message_queue'];

        if (! isset($config['queue_name'])) {
            throw new \RuntimeException('Missing queue_name for prooph.link.message_queue in application config');
        }

        /** @var $persistentFactory PersistentFactory */
        $persistentFactory = $serviceLocator->get('prooph.link.message_queue.persistent_factory');

        return new MessageQueueConsumer(
            new Consumer($serviceLocator->get('prooph.link.message_queue.bernard_workflow_engine_router'), new MiddlewareBuilder()),
            $persistentFactory->create($config['queue_name'])
        );
    }
}