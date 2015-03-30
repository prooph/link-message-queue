<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 3/29/15 - 11:55 PM
 */
namespace Prooph\Link\MessageQueue\Producer;

use Bernard\Driver\DoctrineDriver;
use Bernard\QueueFactory\PersistentFactory;
use Doctrine\DBAL\DriverManager;
use Prooph\ServiceBus\Message\Bernard\BernardSerializer;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class PersistentFactoryFactory
 *
 * Creates a bernard persistent factory
 *
 * @package Prooph\Link\MessageQueue\Producer
 * @author Alexander Miertsch <contact@prooph.de>
 */
final class PersistentFactoryFactory implements FactoryInterface
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

        if (! isset($config['db'])) {
            throw new \RuntimeException('Missing db settings for prooph.link.message_queue in application config');
        }

        if ($config['db']['use_application_db']) {
            $connection = $serviceLocator->get('prooph.link.app.db');
        } else {
            if (! isset($config['db']['connection'])) {
                throw new \RuntimeException('Missing db connection settings for prooph.link.message_queue in application config');
            }

            $connection = DriverManager::getConnection($config['db']['connection']);
        }

        return new PersistentFactory(new DoctrineDriver($connection), new BernardSerializer());
    }
}