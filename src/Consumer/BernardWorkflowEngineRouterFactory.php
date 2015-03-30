<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 3/30/15 - 12:14 AM
 */
namespace Prooph\Link\MessageQueue\Consumer;

use Prooph\Processing\Environment\Environment;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class BernardWorkflowEngineRouterFactory
 *
 * @package Prooph\Link\MessageQueue\Consumer
 * @author Alexander Miertsch <contact@prooph.de>
 */
final class BernardWorkflowEngineRouterFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var $env Environment */
        $env = $serviceLocator->get('processing_environment');

        return new BernardWorkflowEngineRouter($env->getWorkflowEngine());
    }
}