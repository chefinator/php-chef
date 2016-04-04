<?php

namespace Chef\Project;

interface ProjectPathProviderInterface
{
    /**
     * @param $name
     *
     * @return mixed
     */
    public function getProjectPath($name);

    /**
     * @param string $path
     */
    public function setPath($path);
}
