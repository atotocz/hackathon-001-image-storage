<?php
namespace Hackaton\ImageStorage\Controllers;

use Hackaton\ImageStorage\Container\ContainerAwareTrait;
use Hackaton\ImageStorage\FileNotFoundException;
use Hackaton\ImageStorage\Image\Manager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class InfoController {
  use ContainerAwareTrait;

  public function readAction(Request $request, $key = null) {
    if ($key === null) {
      return JsonResponse::create(['error' => 'Expected ID!'], 500);
    }

    try {
      /** @var Manager $image_manager */
      $image_manager = $this->container->getService('imageManager');
      $stored_file = $image_manager->loadImageFile($key, 'original');

      return JsonResponse::create(
        [
          'id'       => $stored_file->getKey(),
          'filesize' => $stored_file->getFilesize(),
          'updated'  => $stored_file->getUpdatedTime()
        ],
        200
      );
    }
    catch (FileNotFoundException $e) {
      return JsonResponse::create(['error' => $e->getMessage()], 404);
    }
    catch (\Exception $e) {
      return JsonResponse::create(['error' => $e->getMessage()], 500);
    }
  }
}
