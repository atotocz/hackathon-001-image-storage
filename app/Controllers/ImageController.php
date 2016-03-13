<?php
namespace Hackaton\ImageStorage\Controllers;

use Hackaton\ImageStorage\Container\ContainerAwareTrait;
use Hackaton\ImageStorage\FileNotFoundException;
use Hackaton\ImageStorage\Image\Manager;
use Hackaton\ImageStorage\Image\Providers\SymfonyRequestProvider;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class ImageController {
  use ContainerAwareTrait;

  public function readAction(Request $request, $key = null, $profile = null) {
    if ($key === null) {
      return JsonResponse::create(['error' => 'Expected file key!'], 500);
    }

    try {
      /** @var Manager $image_manager */
      $image_manager = $this->container->getService('imageManager');
      $stored_file = $image_manager->loadImageFile($key, $profile);

      $response = new BinaryFileResponse($stored_file->getPath());
      $response->prepare($request);
      $response->headers->set('Content-Type', $stored_file->getMimeType());
    }
    catch (FileNotFoundException $e) {
      if ($this->container->getParameter('useNoImage')) {
        return RedirectResponse::create($this->container->getParameter('noImageUrl'));
      }

      return JsonResponse::create(['error' => $e->getMessage()], 404);
    }
    catch (\Exception $e) {
      return JsonResponse::create(['error' => $e->getMessage()], 500);
    }

    return $response;
  }

  public function createAction(Request $request) {
    try {
      /** @var Manager $image_manager */
      $image_manager = $this->container->getService('imageManager');

      /** @var SymfonyRequestProvider $provider */
      $provider = $this->container->getService('symfonyRequestProvider');
      $provider->setRequest($request);

      $stored_file = $image_manager->storeFromProvider($provider);

      return JsonResponse::create(
        [
          'key'      => $stored_file->getKey(),
          'filesize' => $stored_file->getFilesize(),
          'updated'  => $stored_file->getUpdatedTime()
        ],
        200
      );
    }
    catch (\Exception $e) {
      return JsonResponse::create(['error' => $e->getMessage()], 500);
    }
  }

  public function deleteAction(Request $request, $key = null, $profile = null) {
    if ($key === null) {
      return JsonResponse::create(['error' => 'Expected file key!'], 500);
    }

    try {
      /** @var Manager $image_manager */
      $image_manager = $this->container->getService('imageManager');
      $image_manager->deleteImageFile($key, $profile);

      return JsonResponse::create(sprintf('File with key "%s" was removed.', $key));
    }
    catch (FileNotFoundException $e) {
      return JsonResponse::create(['error' => $e->getMessage()], 404);
    }
    catch (\Exception $e) {
      return JsonResponse::create(['error' => $e->getMessage()], 500);
    }
  }
}
