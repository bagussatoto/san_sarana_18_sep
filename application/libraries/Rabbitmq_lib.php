<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//require_once FCPATH . 'vendor/autoload.php';
require_once 'vendor/autoload.php';
//require_once('../.../vendor/autoload.php');

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Rabbitmq_lib {

    private $connection;
    private $channel;

    public function __construct() {
        // Load konfigurasi RabbitMQ dari file config
        $this->CI =& get_instance();
        $this->CI->config->load('rabbitmq');
        $config = $this->CI->config->item('rabbitmq');

        // Buat koneksi ke RabbitMQ
        $this->connection = new AMQPStreamConnection(
            $config['host'],
            $config['port'],
            $config['user'],
            $config['pass']
        );
        $this->channel = $this->connection->channel();
    }

    /**
     * Deklarasikan queue
     *
     * @param string $queue_name Nama queue
     */
    public function declare_queue($queue_name) {
        $this->channel->queue_declare($queue_name, false, true, false, false);
    }

    /**
     * Kirim pesan ke queue
     *
     * @param string $queue_name Nama queue
     * @param string $message Pesan yang akan dikirim
     * @param string $message Pesan ada api_domain, untuk ekskusi api
     */
    public function publish($queue_name, $message) {
        $msg = new AMQPMessage($message, ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
        $this->channel->queue_declare($queue_name, false, true, false, false);
        $this->channel->basic_publish($msg, '', $queue_name);
        echo " [x] Pesan terkirim: '$message' ke queue '$queue_name'\n";
        return array("status"=>200);//untuk dibaca oleh eksekutor
    }

    /**
     * Konsumsi pesan dari queue
     *
     * @param string $queue_name Nama queue
     * @param callable $callback Fungsi yang akan dipanggil saat pesan diterima
     */
    public function consume($queue_name, $callback) {
        echo " [*] Menunggu pesan dari queue '$queue_name'. Untuk keluar tekan CTRL+C\n";

        $this->channel->basic_consume($queue_name, '', false, false, false, false, $callback);

        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
    }

    /**
     * Tutup koneksi
     */
    public function close() {
        $this->channel->close();
        $this->connection->close();
    }
}
