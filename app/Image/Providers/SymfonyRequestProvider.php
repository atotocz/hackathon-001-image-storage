<?php
namespace Hackaton\ImageStorage\Image\Providers;

use Hackaton\ImageStorage\CantLoadContentException;
use Hackaton\ImageStorage\Image\File;
use Hackaton\ImageStorage\Loaders\ILoader;
use Hackaton\ImageStorage\NoContentSourceFoundException;
use Symfony\Component\HttpFoundation\Request;

class SymfonyRequestProvider implements IProvider {
  /** @var ILoader */
  protected $loader;

  /** @var Request */
  protected $request;

  public function __construct(ILoader $loader) {
    $this->loader = $loader;
  }

  public function setRequest(Request $request) {
    $this->request = $request;
  }

  public function createFile() {
    if (($url = $this->request->request->get('url')) !== null) {
      $content = $this->loader->load($url);

      if ($content === false) {
        throw new CantLoadContentException(sprintf('Can\'t load content from url "%s".', $url));
      }

      return new File(md5($url), $content);
    }

    if (($content = $this->request->request->get('content')) !== null) {
      $content = base64_decode($content);

      if ($content === false) {
        throw new CantLoadContentException(sprintf('Can\'t decode base64.', $url));
      }

      return new File(md5($content), $content);
    }

    throw new NoContentSourceFoundException('No source found.');
  }
}
