<?php
declare(strict_types=1);

namespace App\Entity;
use Elasticsearch\ClientBuilder;

final class ElasticSearchModel
{
    public static function addElasticSearch (string $id, string $tags, string $path, string $title, string $description)
    {
        $elasticSearch = ClientBuilder::create()->setHosts(["elasticsearch:9200"])->build();

        $params =  [
            'index' => 'pictures',
            'body' => [
                'id' => $id,
                'tags'        => $tags,
                'path'        => $path,
                'title'       => $title,
                'description' => $description
            ]
        ];


        $elasticSearch->index($params);

    }

}
