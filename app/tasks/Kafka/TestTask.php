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
        try {
            $rcf = new \RdKafka\Conf();
            $rcf->set('group.id', 'test');
            $cf = new \RdKafka\TopicConf();
            $cf->set('offset.store.method', 'broker');
            $cf->set('auto.offset.reset', 'smallest');

            $rk = new \RdKafka\Producer($rcf);
            $rk->setLogLevel(LOG_DEBUG);
            $rk->addBrokers(env('KAFKA_BROKER_LIST'));
            $topic = $rk->newTopic("test", $cf);
            for ($i = 0; $i < 1000; $i++) {
                $topic->produce(0, 0, 'test' . $i);
            }
        } catch (Exception $e) {
            dump('error' . $e->getMessage());
        }
    }

    public function consumeAction()
    {
        try {
            $rcf = new \RdKafka\Conf();
            $rcf->set('group.id', 'test');
            $cf = new \RdKafka\TopicConf();
            /*
                $cf->set('offset.store.method', 'file');
            */
            $cf->set('auto.offset.reset', 'smallest');
            $cf->set('auto.commit.enable', true);

            $rk = new \RdKafka\Consumer($rcf);
            $rk->setLogLevel(LOG_DEBUG);
            $rk->addBrokers("127.0.0.1");
            $topic = $rk->newTopic("test", $cf);
            //$topic->consumeStart(0, RD_KAFKA_OFFSET_BEGINNING);
            while (true) {
                $topic->consumeStart(0, RD_KAFKA_OFFSET_STORED);
                $msg = $topic->consume(0, 1000);
                dump($msg);
                if ($msg->err) {
                    echo $msg->errstr(), "\n";
                    break;
                } else {
                    echo $msg->payload, "\n";
                }
                $topic->consumeStop(0);
                sleep(1);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

}

