<?php

namespace Chef\Project;

interface ProjectProviderInterface
{
    /**
     * @param $name
     *
     * @return mixed
     */
    public function getProject($name);
}
