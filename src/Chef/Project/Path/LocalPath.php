<?php

namespace Chef\Project\Path;

use Chef\Project\ProjectPathProviderInterface;

/**
 * Class LocalPath
 *
 * @package Chef\Project\Path
 */
class LocalPath implements ProjectPathProviderInterface
{
    /** @var string */
    private $path;

    /**
     * @inheritdoc
     */
    public function setPath($path)
    {
        if (!is_writable($path)) {
            throw new \RuntimeException(sprintf('Project Provider Path is not writeable, path: %s', $this->path));
        }

        $this->path = $path;
    }

    /**
     * @inheritDoc
     */
    public function exists($name)
    {
        return is_dir($this->getProjectPath($name));
    }

    /**
     * @inheritdoc
     */
    public function getProjectPath($name)
    {
        return sprintf('%s/%s', $this->path, $name);
    }
}
