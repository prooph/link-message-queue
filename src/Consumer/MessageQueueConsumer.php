<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 3/30/15 - 12:00 AM
 */
namespace Prooph\Link\MessageQueue\Consumer;
use Bernard\Consumer;
use Bernard\Queue;
use Prooph\Link\Application\Event\TickOccurred;

/**
 * Class MessageQueueConsumer
 *
 * The message queue consumer listens on the Prooph\Link\Application\Event\TickOccurred event and triggers
 * the psb bernard message consumer to pull the next pending message from the message queue.
 *
 * @package Prooph\Link\MessageQueue\Consumer
 * @author Alexander Miertsch <contact@prooph.de>
 */
final class MessageQueueConsumer 
{
    /**
     * @var Consumer
     */
    private $psbBernardMessageConsumer;

    /**
     * @var Queue
     */
    private $queue;

    /**
     * @param Consumer $consumer
     * @param Queue $queue
     */
    public function __construct(Consumer $consumer, Queue $queue)
    {
        $this->psbBernardMessageConsumer = $consumer;
        $this->queue = $queue;
    }

    /**
     * @param TickOccurred $event
     */
    public function onTickOccurred(TickOccurred $event)
    {
        $this->psbBernardMessageConsumer->tick($this->queue);
    }
} 