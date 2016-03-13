<?php
namespace Hackaton\ImageStorage\Image;

use Hackaton\ImageStorage\FileCouldNotBeDeletedException;
use Hackaton\ImageStorage\FileNotFoundException;
use Hackaton\ImageStorage\Image\Processor\Processor;
use Hackaton\ImageStorage\Image\Providers\IProvider;
use Hackaton\ImageStorage\Image\Storages\IStorage;
use Hackaton\ImageStorage\ProfileNotFoundException;
use Nette\Utils\Image;

class Manager {
  /** @var Processor */
  protected $processor;

  /** @var array */
  protected $profiles;

  /** @var IStorage */
  protected $storage;

  public function __construct(Processor $processor, IStorage $storage, array $profiles) {
    $this->storage = $storage;
    $this->profiles = $profiles;
    $this->processor = $processor;
  }

  public function storeFromProvider(IProvider $provider) {
    $file = $provider->createFile();
    $stored_file = $this->storage->save('original', $file);

    foreach ($this->profiles as $profile => $_) {
      $this->generateProfileImage($stored_file, $profile);
    }

    return $stored_file;
  }

  public function generateProfileImage(StoredFile $stored_file, $profile) {
    if (!isset($this->profiles[$profile])) {
      throw new ProfileNotFoundException(sprintf('Profile "%s" not found', $profile));
    }

    $image = Image::fromString($stored_file->getContent());
    $this->processor->applyCommands($image, $this->profiles[$profile]);

    return $this->storage->save($profile, new File($stored_file->getKey(), $image->toString()));
  }

  public function loadImageFile($key, $profile = null) {
    $profile = $profile ?: 'original';

    if (!isset($this->profiles[$profile]) && $profile != 'original') {
      throw new ProfileNotFoundException(sprintf('Profile "%s" not found', $profile));
    }

    $stored_file = $this->storage->load($profile, $key);

    if (!$stored_file && $profile != 'original') {
      $stored_file = $this->storage->load('original', $key);

      if ($stored_file) {
        $this->generateProfileImage($stored_file, $profile);
        $stored_file = $this->storage->load($profile, $key);
      }
    }

    if (!$stored_file) {
      throw new FileNotFoundException(sprintf('File with key "%s" and profile "%s" not found', $key, $profile));
    }

    return $stored_file;
  }

  public function deleteImageFile($key, $profile = null) {
    $profile = $profile ?: 'original';

    if (!isset($this->profiles[$profile]) && $profile != 'original') {
      throw new ProfileNotFoundException(sprintf('Profile "%s" not found', $profile));
    }

    if (!$this->storage->exists($profile, $key)) {
      throw new FileNotFoundException(sprintf('File with key "%s" and profile "%s" not found', $key, $profile));
    }

    if (!$this->storage->delete($profile, $key)) {
      throw new FileCouldNotBeDeletedException(sprintf('File with key "%s" and profile "%s" could not be deleted', $key, $profile));
    }

    if ($profile == 'original') {
      foreach ($this->profiles as $x_profile => $_) {
        $this->storage->delete($x_profile, $key);
      }
    }

    return true;
  }
}
