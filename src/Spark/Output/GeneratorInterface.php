<?php namespace ITC\Spark\Output;

use Iterator;
use Illuminate\Contracts\Filesystem\Filesystem as StorageInterface;

interface GeneratorInterface
{
    /**
     * @param League\Flysystem\FilesystemInterface $storage
     * @return void
     */
    public function setStorage(StorageInterface $storage);

    /**
     * @param void
     * @return League\Flysystem\FilesystemInterface
     */
    public function getStorage();

    /**
     * Render all generable routes and write output to the filesystem
     * @param \Iterator $urls
     * @return array
     */
    public function generate(Iterator $urls);
}
