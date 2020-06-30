<?php
declare(strict_types=1);

namespace App\Controller;


use claviska\SimpleImage;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\HttpFoundation\Request;

final class UploadController
{

    public function upload(Request $request)
    {

        $tags        = $request->get('tags');
        $description = $request->get('description');
        $files = $request->files;

        $transformations = ['blur', 'sepia', 'invert', 'emboss', 'sketch'];

        $connection = new AMQPStreamConnection('rabbitmq', 5672, $_ENV['RABBIT_USER'], $_ENV['RABBIT_USER']);

        if ($connection) {

            $channel = $connection->channel();
            $channel->queue_declare($_ENV['RABBIT_QUEUE'], false, true, false, false);


            foreach ($files->all()['file'] as $file) {

                $messageBody = [];

                $messageBody['file_path'] = $file->getPathname();
                $messageBody['description'] = $description;
                $messageBody['tags'] = $tags;

                foreach ($transformations as $key => $transformation ) {
                    $messageBody['transformation'] = $transformations;
                    $msg = new AMQPMessage(json_encode($messageBody));
                    $channel->basic_publish($msg, $_ENV['RABBIT_EXCHANGE'], '');
                }

            }

            $channel->close();
            $connection->close();
        }

    }

//    private function prepareMessages($file)
//    {
//
//        $image = new SimpleImage();
//        $image->fromFile($file->getPathname())->sepia()->toScreen();
//    }
}
