<?php
declare(strict_types=1);

namespace App\Entity;
use Elasticsearch\ClientBuilder;

final class ElasticSearchModel
{

    const INDEX_NAME = 'pictures';

    public static function addElasticSearch (string $id, string $tags, string $path, string $title, string $description)
    {
        $elasticSearch = ClientBuilder::create()->setHosts(["elasticsearch:9200"])->build();

        $params =  [
            'index' => self::INDEX_NAME,
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

    public static function getInfo(string $tags) : array
    {
        $elasticSearch = ClientBuilder::create()->setHosts(["elasticsearch:9200"])->build();

        $params = [
            'index' => self::INDEX_NAME,
            'body'  => [
                'query' => [
                    'match' => [
                        'tags' => $tags
                    ]
                ]
            ]
        ];

        $response = $elasticSearch->search($params);

        if ($response['hits']['total']['value'] == 0) {
            return [];
        }

        return $response['hits']['hits'];
    }

}
