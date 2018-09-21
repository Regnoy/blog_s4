<?php

namespace Uv\File\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Class File
 * @package Uv\File\Entity
 * @ORM\Table(name="file_manager")
 * @ORM\Entity
 */
class File implements FileBaseInterface
{
  use FileTrait;
}
