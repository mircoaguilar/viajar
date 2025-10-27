<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Pusher\Pusher;

class ConexionPusher {
    private static $app_id = '2052804';
    private static $key = '33c3a03029c5c6bdab28';
    private static $secret = '8002a067b95150615fa7';
    private static $cluster = 'us2';

    public static function getPusher() {
        $options = [
            'cluster' => self::$cluster,
            'useTLS' => true
        ];

        return new Pusher(
            self::$key,
            self::$secret,
            self::$app_id,
            $options
        );
    }
}
