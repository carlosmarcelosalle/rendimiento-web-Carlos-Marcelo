<?php
declare(strict_types=1);

namespace App\Controller;

use claviska\SimpleImage;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class UploadController
{

    public function upload(Request $request) : Response
    {

        $tags        = $request->get('tags');
        $description = $request->get('description');
        $files = $request->files;

        $transformations = ['blur', 'sepia', 'invert', 'emboss', 'sketch'];

        $connection = new AMQPStreamConnection('rabbitmq', 5672, $_ENV['RABBIT_USER'], $_ENV['RABBIT_USER']);

        if ($connection) {

            $channel = $connection->channel();
            $channel->queue_declare($_ENV['RABBIT_QUEUE'], false, true, false, false);

            $mysql = new \PDO('mysql:dbname=db;host=mysql', 'user', 'password');

            foreach ($files->all()['file'] as $file) {

                $id = uniqid();
                $path = 'uploads/original/';
                $title = $id . '.png';

                $image = new SimpleImage();

                $image->fromFile($file->getPathName())->toFile($path . $title);

                $messageBody = [];

                $messageBody['id'] = $id;
                $messageBody['description'] = $description;
                $messageBody['tags'] = $tags;
                $messageBody['title'] = $title;

                foreach ($transformations as $key => $transformation ) {
                    $messageBody['transformation'] = $transformation;
                    $msg = new AMQPMessage(json_encode($messageBody));
                    $channel->basic_publish($msg, $_ENV['RABBIT_EXCHANGE'], '');
                }

                $sql = "INSERT INTO photos (id_photo, path, title, description, tags, original) VALUES (:id, :path, :title, :description, :tags, 1)";

                $stmt =$mysql->prepare($sql);

                $stmt->execute([
                    'id' => $id,
                    'path' => $path,
                    'title' => $title,
                    'description' => $description,
                    'tags' => $tags
                ]);

            }

            $channel->close();
            $connection->close();

        }

        $response = 'success';

        return Response::create($response, 200);

    }

}
