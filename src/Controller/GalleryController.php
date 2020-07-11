<?php
declare(strict_types=1);

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class GalleryController extends AbstractController
{

    public function __invoke()
    {

        $redis = new \Redis();
        $redis->connect('redis');

        if ($redis->get('photos')) {
            $photosJson = $redis->get('photos');

            $photos = json_decode($photosJson, true);
        } else {

            $mysql = new \PDO('mysql:dbname=db;host=mysql', 'user', 'password');

            $stmt = $mysql->query("SELECT id_photo, path, title, description, tags, original FROM pictures");

            $photos = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $redis->set('photos', json_encode($photos));
        }

        return $this->render('gallery.html.twig', ['photos' => $photos]);
    }
}
