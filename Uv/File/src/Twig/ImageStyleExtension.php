<?php

namespace Uv\File\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Uv\File\Components\Image\ImageStyle;
use Uv\File\Entity\File;

class ImageStyleExtension extends AbstractExtension
{

  private $imageStyle;

  public function __construct(ImageStyle $image_style)
  {
    $this->imageStyle = $image_style;
  }

  public function getFilters()
  {
    return [
      new TwigFilter('image_style', [$this, 'formatImageStyle'], [
        'is_safe' => ['html']
      ]),
    ];
  }

  public function formatImageStyle(File $file, $image_style)
  {

    return "<img>".$image_style."</img>";
  }
}