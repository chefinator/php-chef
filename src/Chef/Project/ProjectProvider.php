<?php

namespace Chef\Project;

use Chef\Project\Path\LocalPath;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class ProjectProvider implements ProjectProviderInterface
{
    /** @var ProjectProviderInterface */
    private $projectPathProvider;

    /** @var LoggerInterface */
    private $logger;

    /** @var string */
    private $path;

    /** @var ProjectBuilder */
    private $projectBuilder;

    /**
     * ProjectProvider constructor.
     *
     * @param ProjectPathProviderInterface|null $projectPathProvider
     * @param LoggerInterface|null              $logger
     * @param null                              $path
     */
    public function __construct(
        ProjectPathProviderInterface $projectPathProvider = null,
        LoggerInterface $logger = null,
        $path = null
    ) {
        $this->path = $path ?: sprintf('%s/projects/', sys_get_temp_dir());
        $this->logger = $logger ?: new NullLogger();
        $this->projectPathProvider = $projectPathProvider ?: new LocalPath();
        $this->projectBuilder = new ProjectBuilder($this->logger);

        if (!is_dir($this->path)) {
            $this->logger->info(
                sprintf('%s: Base directory for projects does not exist, creating at %s', __NAMESPACE__, $this->path)
            );

            mkdir($this->path, 0774, true);
        }
    }

    /**
     * @param $name
     *
     * @return \Chef\Model\Project
     * @throws \Exception
     */
    public function getProject($name)
    {
        $this->projectPathProvider->setPath($this->path);

        if (!$this->projectPathProvider->exists($name)) {
            throw new \Exception(
                sprintf(
                    'No project exists for %s path provider at %s with the name of %s',
                    get_class($this->projectPathProvider),
                    $this->path,
                    $name
                )
            );
        }

        $path = $this->projectPathProvider->getProjectPath($name);

        return $this->projectBuilder->build($path);
    }
}
