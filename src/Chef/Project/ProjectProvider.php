<?php

namespace Chef\Project;

use Chef\Project\Path\LocalPath;

class ProjectProvider implements ProjectProviderInterface
{
    /** @var ProjectProviderInterface */
    private $projectPathProvider;

    private $path;

    /**
     * ProjectProvider constructor.
     *
     * @param ProjectPathProviderInterface $projectPathProvider
     * @param null                         $path
     */
    public function __construct(ProjectPathProviderInterface $projectPathProvider, $path = null)
    {
        $this->path = $path ?: sprintf('%s/projects/', sys_get_temp_dir());
        $this->projectPathProvider = $projectPathProvider ?: new LocalPath();

        if (!is_dir($this->path)) {
            mkdir($this->path, 0774, true);
        }
    }

    public function getProject($name)
    {
        $this->projectPathProvider->setPath($this->path);
        $path = $this->projectPathProvider->getProjectPath($name);
    }
}
