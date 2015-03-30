Prooph\Link\MessageQueue
========================
Message Queue Module for [prooph LINK](https://github.com/prooph/link)

# Bernard Backend
This module provides a simple message queue for prooph LINK, so that workflow messages can be handled asynchronous.
The message queue is implemented with the help of [bernard](https://github.com/bernardphp/bernard) and the corresponding
[message dispatcher](https://github.com/prooph/psb-bernard-dispatcher) implementation for prooph service bus.
It uses the DoctrineDriver shipped with bernard and connects to the `prooph.link.app.db` database connection to manage the
message queue.

# Message Dispatcher
The module provides a ready to use message dispatcher that can be defined in a processing channel to push workflow messages
on the queue send over the channel. A channel config using the message dispatcher would look something like this:

```php
return [
    'processing' => [
        'channels' => [
            'message_queue' => [
                'targets' => ['Acme\WorkflowMessageHandler'],
                'sender'  => 'a_node_name',
                 'message_dispatcher' => 'prooph.link.message_queue.producer' //ServiceManager alias of the message dispatcher
            ],
            //...
        ],
        //...
    ],
    //...
];
```

# Workflow Processor Message Queue
The [app-core](https://github.com/prooph/link-app-core) module provides the possibility to activate a general message queue for
all messages sent by the workflow processor. The message-queue module provides the required implementation. It defines the
`\Prooph\Link\Application\Definition::APP_SERVICE_WORKFLOW_PROCESSOR_MESSAGE_QUEUE` as an alias of the `prooph.link.message_queue.producer`
so the message dispatcher handles all messages sent by the workflow processor.

# Message Consumer
To consume messages pushed to the queue a TickOccurred event needs to be published by the application. You can read about it in the
app-core module documentation. The message consumer shipped with the message-queue module listens on this TickOccurred event, performs
a bernard consumer tick (pulls the next pending message from the queue) and forwards the message to the processing workflow engine without
further routing. The workflow engine is responsible for routing the message to its target.

# Support

- Ask any questions on [prooph-users](https://groups.google.com/forum/?hl=de#!forum/prooph) google group.
- File issues at [https://github.com/prooph/link-app-core/issues](https://github.com/prooph/link-message-queue/issues).

# Contribution

You wanna help us? Great!
We appreciate any help, be it on implementation level, UI improvements, testing, donation or simply trying out the system and give us feedback.
Just leave us a note in our google group linked above and we can discuss further steps.

Thanks,
your prooph team
