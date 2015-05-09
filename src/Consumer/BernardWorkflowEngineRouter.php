<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 3/30/15 - 12:05 AM
 */
namespace Prooph\Link\MessageQueue\Consumer;

use Bernard\Envelope;
use Bernard\Router;
use Prooph\Common\Messaging\RemoteMessage;
use Prooph\Processing\Processor\WorkflowEngine;

final class BernardWorkflowEngineRouter implements Router
{
    /**
     * @var WorkflowEngine
     */
    private $workflowEngine;

    /**
     * @param WorkflowEngine $workflowEngine
     */
    public function __construct(WorkflowEngine $workflowEngine)
    {
        $this->workflowEngine = $workflowEngine;
    }

    /**
     * Returns the right Receiver (callable) based on the Envelope.
     *
     * @param  Envelope $envelope
     * @throws \InvalidArgumentException
     * @return array
     */
    public function map(Envelope $envelope)
    {
        $message = $envelope->getMessage();

        if (! $message instanceof RemoteMessage) {
            throw new \InvalidArgumentException(sprintf(
                "Routing the message %s failed due to wrong message type",
                $envelope->getName()
            ));
        }

        return array($this, "routeMessage");
    }

    /**
     * @param RemoteMessage $message
     */
    public function routeMessage(RemoteMessage $message)
    {
        $this->workflowEngine->dispatch($message, 'prooph.link.message_queue.consumer');
    }
}