<?php

require_once __DIR__ . '/vendor/autoload.php';

use Kafka\Producer;
use Kafka\ProducerConfig;
use Kafka\Consumer;
use Kafka\ConsumerConfig;

class QueueManager 
{
    private $topic = 'lab7_orders';

    public function __construct()
    {
        error_reporting(error_reporting() & ~E_DEPRECATED);
        
        $producerConfig = ProducerConfig::getInstance();
        $producerConfig->setMetadataBrokerList('kafka:9092');
        
        $consumerConfig = ConsumerConfig::getInstance();
        $consumerConfig->setMetadataBrokerList('kafka:9092');
        $consumerConfig->setGroupId('lab7_consumer_group');
    }

    public function publish($data)
    {
        try {
            ob_start();
            
            $config = ProducerConfig::getInstance();
            $config->setMetadataBrokerList('kafka:9092');

            $producer = new Producer(function() use ($data) {
                return [
                    [
                        'topic' => $this->topic,
                        'value' => json_encode($data),
                        'key' => uniqid(),
                    ]
                ];
            });

            $result = $producer->send(true);
            
            ob_end_clean();
            
            return true;
            
        } catch (Exception $e) {
            ob_end_clean();
            error_log("Kafka publish error: " . $e->getMessage());
            return false;
        }
    }

    public function consume(callable $callback)
    {
        try {
            error_reporting(error_reporting() & ~E_DEPRECATED);
            
            $config = ConsumerConfig::getInstance();
            $config->setMetadataBrokerList('kafka:9092');
            $config->setGroupId('lab7_consumer_group');
            $config->setTopics([$this->topic]);
            $config->setOffsetReset('earliest');

            $consumer = new Consumer();
            $consumer->start(function($topic, $part, $message) use ($callback) {
                try {
                    $data = json_decode($message['message']['value'], true);
                    if ($data) {
                        $callback($data);
                    }
                } catch (Exception $e) {
                    error_log("Message processing error: " . $e->getMessage());
                }
            });
            
        } catch (Exception $e) {
            error_log("Kafka consume error: " . $e->getMessage());
            throw $e;
        }
    }

    public function createTopic()
    {
        return "Топик будет создан автоматически при первой отправке сообщения";
    }
}