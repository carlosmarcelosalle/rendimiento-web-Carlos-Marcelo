<?php
declare(strict_types=1);

namespace App\Controller;


use Symfony\Component\HttpFoundation\Request;

final class UploadController
{
    public function upload(Request $request)
    {

        $tags        = $request->get('tags');
        $description = $request->get('description');
        $files = $request->files;

        foreach ($files as $file) {
            var_dump($file);die();
        }
    }
}
