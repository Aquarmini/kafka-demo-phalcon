<?php

namespace App\Tasks\Kafka;

use App\Tasks\Task;
use Kafka\ConsumerConfig;
use Kafka\ProducerConfig;
use Xin\Phalcon\Logger\Factory;

class TestTask extends Task
{

    public function publishAction()
    {
        $config = ProducerConfig::getInstance();
        $config->setMetadataRefreshIntervalMs(10000);
        $config->setMetadataBrokerList(env('KAFKA_BROKER_LIST'));
        $config->setBrokerVersion('0.9.0.1');
        $config->setRequiredAck(1);
        $config->setIsAsyn(false);
        $config->setProduceInterval(500);
        $producer = new \Kafka\Producer(function () {
            return array(
                array(
                    'topic' => 'test',
                    'value' => 'test....message.',
                    'key' => 'testkey',
                ),
            );
        });
        // /** @var Factory $factory */
        // $factory = di('logger');
        // $logger = $factory->getLogger('kafka');
        // $producer->setLogger($logger);
        $producer->success(function ($result) {
            dump($result);
        });
        $producer->error(function ($errorCode) {
            dump('error:' . $errorCode);
        });
        $producer->send(true);
    }

    public function consumeAction()
    {
        $config = ConsumerConfig::getInstance();
        $config->setMetadataRefreshIntervalMs(10000);
        $config->setMetadataBrokerList(env('KAFKA_BROKER_LIST'));
        $config->setGroupId('test');
        $config->setBrokerVersion('0.9.0.1');
        $config->setTopics(array('test'));
        $consumer = new \Kafka\Consumer();
        $consumer->start(function ($topic, $part, $message) {
            dump($message);
        });
    }

}

