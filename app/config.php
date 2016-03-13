<?php
use Nette\Utils\Image;

return [
  'storageDir' => __DIR__ . '/../storage',
  'useNoImage' => true,
  'noImageUrl' => 'https://atoto.cz/images/no-product.png',
  'profiles'   => [
    'detail'     => [
      ['resize', 300, 300, Image::SHRINK_ONLY]
    ],
    'list'       => [
      ['resize', 150, 150, Image::SHRINK_ONLY]
    ],
    'large'      => [
      ['resize', 1000, 1000, Image::SHRINK_ONLY]
    ],
    'bw-640x360' => [
      ['resize', 320, 180, Image::SHRINK_ONLY],
      ['filter', IMG_FILTER_GRAYSCALE]
    ],
  ]
];
