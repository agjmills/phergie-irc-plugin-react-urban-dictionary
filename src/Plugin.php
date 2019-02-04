<?php

namespace Asdfx\Phergie\Plugin\UrbanDictionary;

use Phergie\Irc\Bot\React\AbstractPlugin;
use Phergie\Irc\Bot\React\EventQueueInterface as Queue;
use Phergie\Irc\Plugin\React\Command\CommandEvent as Event;
use Zttp\Zttp;

class Plugin extends AbstractPlugin {

    private $apiKey = '';

    public function __construct(array $config = [])
    {
        if (isset($config['apiKey'])) {
            $this->apiKey = $config['apiKey'];
        }
    }

    public function getSubscribedEvents()
    {
        return ['command.ud' => 'handleCommand'];
    }

    public function handleCommand(Event $event, Queue $queue)
    {
    }

    protected function sendIrcResponse(Event $event, Queue $queue, array $ircResponse)
    {
        foreach ($ircResponse as $ircResponseLine) {
            $this->sendIrcResponseLine($event, $queue, $ircResponseLine);
        }
    }

    protected function sendIrcResponseLine(Event $event, Queue $queue, $ircResponseLine)
    {
        $queue->ircPrivmsg($event->getSource(), $ircResponseLine);
    }
}
