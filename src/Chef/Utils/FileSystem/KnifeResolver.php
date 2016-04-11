<?php

namespace Chef\Utils\FileSystem;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RecursiveCallbackFilterIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

class KnifeResolver
{
    /** @var NullLogger */
    private $logger;

    /** @var int */
    private $flags;

    /**
     * KnifeFileResolver constructor.
     *
     * @param LoggerInterface|null $logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger ?: new NullLogger();
        $this->flags = RecursiveDirectoryIterator::KEY_AS_FILENAME |
            RecursiveDirectoryIterator::CURRENT_AS_FILEINFO |
            RecursiveDirectoryIterator::SKIP_DOTS;
    }

    /**
     * @param $directory
     *
     * @return SplFileInfo
     */
    public function resolve($directory)
    {
        $directoryIterator = new RecursiveDirectoryIterator($directory, $this->flags);
        $directoryCallbackIterator = new RecursiveCallbackFilterIterator(
            $directoryIterator,
            [$this, 'recursiveDirectoryFilter']
        );

        $iterator = new RecursiveIteratorIterator($directoryCallbackIterator);

        if (iterator_count($iterator) !== 1) {
            return false;
        }

        $knifeFile = iterator_to_array($iterator);

        return array_shift($knifeFile);
    }

    /**
     * @param SplFileInfo $fileInfo
     *
     * @return bool
     */
    public function recursiveDirectoryFilter(SplFileInfo $fileInfo)
    {
        if ($fileInfo->isFile()) {
            return $fileInfo->getFilename() === 'knife.rb';
        }

        return true;
    }
}
