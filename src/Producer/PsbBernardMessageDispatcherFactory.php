<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 3/29/15 - 11:30 PM
 */
namespace Prooph\Link\MessageQueue\Producer;

use Bernard\Driver\DoctrineDriver;
use Bernard\Middleware\MiddlewareBuilder;
use Bernard\Producer;
use Bernard\QueueFactory\PersistentFactory;
use Prooph\ServiceBus\Message\Bernard\BernardSerializer;
use Prooph\ServiceBus\Message\Bernard\MessageDispatcher;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\DBAL\DriverManager;

/**
 * Class PsbBernardMessageDispatcherFactory
 *
 * @package Prooph\Link\MessageQueue\Producer
 * @author Alexander Miertsch <contact@prooph.de>
 */
final class PsbBernardMessageDispatcherFactory implements FactoryInterface
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

        $bernardProducer = new Producer($serviceLocator->get('prooph.link.message_queue.persistent_factory'), new MiddlewareBuilder());

        return new MessageDispatcher($bernardProducer, $config['queue_name']);
    }
}