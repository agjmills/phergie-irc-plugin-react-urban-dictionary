<?php

namespace Asdfx\Phergie\Plugin\UrbanDictionary;

use Asdfx\UrbanDictionary\UrbanDictionary;
use Phergie\Irc\Bot\React\AbstractPlugin;
use Phergie\Irc\Bot\React\EventQueueInterface as Queue;
use Phergie\Irc\Plugin\React\Command\CommandEvent as Event;

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
        $lookup = $event->getCustomParams();
        if (count($lookup) > 0) {
            $urbanDictionary = new UrbanDictionary();
            try {
                $definitions = $urbanDictionary->lookup($lookup);
                if (count($definitions) > 0) {
                    $this->sendIrcResponse($event, $queue, [sprintf('%s: %s', $lookup, $definitions[0])]);
                }
            } catch (\Throwable $exception) {
                // squelch
            }
        }
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
