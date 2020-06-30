<?php
declare(strict_types=1);

namespace App\Consumer;

use PhpAmqpLib\Message\AMQPMessage;

final class ConsumerController
{

    public function __construct()
    {

    }

    public function execute(AMQPMessage $msg)
    {
        // Apply transformations
        var_dump($msg);die();
    }
}
