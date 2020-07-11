<?php
declare(strict_types=1);

namespace App\Consumer;

use App\Entity\ElasticSearchModel;
use PhpAmqpLib\Message\AMQPMessage;
use claviska\SimpleImage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ConsumerController extends AbstractController
{

    public function __construct()
    {

    }

    public function execute(AMQPMessage $msg)
    {

        $path = $this->getParameter('uploads_folder');

        $message = json_decode($msg->body, true);

        $image = new SimpleImage();

        $image = $image->fromFile($path . 'original/' . $message['title']);

        switch ($message['transformation']) {
            case 'blur':
                $image = $image->blur();
                break;
            case 'sepia':
                $image = $image->sepia();
                break;
            case 'invert':
                $image = $image->invert();
                break;
            case 'emboss':
                $image = $image->emboss();
                break;
            case 'sketch':
                $image = $image->sketch();
                break;
        }

        $message['tags'] .= ','.$message['transformation'];

        $newTitle = $message['id']. '-' .$message['transformation']. '.png';

        $image->toFile($path . 'transformations/' . $newTitle);
        $mysql = new \PDO('mysql:dbname=db;host=mysql', 'user', 'password');

        $path = str_replace("/app/public/", "", $path);

        $sql = "INSERT INTO pictures (id_photo, path, title, description, tags, original) VALUES (:id, :path, :title, :description, :tags, 0)";

        $stmt =$mysql->prepare($sql);

        $stmt->execute([
            'id' => $message['id'],
            'path' => $path . 'transformations/',
            'title' => $newTitle,
            'description' => $message['description'],
            'tags' => $message['tags']
        ]);

        ElasticSearchModel::addElasticSearch($message['id'], $message['tags'], $path . 'transformations/', $newTitle, $message['description']);

        echo 'Image converted and Saved;'. PHP_EOL;

    }

}
