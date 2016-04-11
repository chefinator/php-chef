<?php

namespace Chef\Project;

interface ProjectPathProviderInterface
{
    /**
     * @param $name
     *
     * @return bool
     */
    public function exists($name);

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
