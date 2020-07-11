<?php
declare(strict_types=1);

namespace App\Controller;


use App\Entity\ElasticSearchModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class SearcherController extends AbstractController
{
    public function __invoke()
    {
        return $this->render('search.html');
    }

    public function searchSubmit (Request $request)
    {
        $tags = $request->get('value');

        $response = ElasticSearchModel::getInfo($tags);

        return JsonResponse::create($response);
    }
}
