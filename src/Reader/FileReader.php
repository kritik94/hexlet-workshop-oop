<?php

namespace Converter\Reader;

use League\Flysystem\FilesystemInterface;

class FileReader implements ReaderInterface
{
    /**
     * @var FilesystemInterface
     */
    private $filesystem;

    public function __construct(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function read($path)
    {
        return $this->filesystem->read($path);
    }
}