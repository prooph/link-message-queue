<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 3/29/15 - 10:42 PM
 */
return [
    'prooph.link.message_queue' => [
        'queue_name' => 'link_processing_queue',
        'db' => [
            //The module uses the prooph.link.app.db connection by default
            'use_application_db' => true,
            //If you set this flag to false you have to provide a doctrine dbal connection configuration
            //'connection' => [/* connection settings go here */]
        ],
    ],
    'service_manager' => [
        'factories' => [
            'prooph.link.message_queue.persistent_factory' => \Prooph\Link\MessageQueue\Producer\PersistentFactoryFactory::class,
            'prooph.link.message_queue.producer' => \Prooph\Link\MessageQueue\Producer\PsbBernardMessageDispatcherFactory::class,
            'prooph.link.message_queue.bernard_workflow_engine_router' => \Prooph\Link\MessageQueue\Consumer\BernardWorkflowEngineRouterFactory::class,
            'prooph.link.message_queue.consumer' => \Prooph\Link\MessageQueue\Consumer\MessageQueueConsumerFactory::class,

        ],
        'aliases' => [
            \Prooph\Link\Application\Definition::APP_SERVICE_WORKFLOW_PROCESSOR_MESSAGE_QUEUE => 'prooph.link.message_queue.producer',
        ]
    ],
    'prooph.psb' => [
        'event_router_map' => [
            \Prooph\Link\Application\Event\TickOccurred::class => [
                'prooph.link.message_queue.consumer'
            ]
        ]
    ],
];