<?php

namespace Chef\Model;

class Project
{
    /** @var string */
    private $path;

    /** @var Knife */
    private $knife;

    /**
     * Project constructor.
     *
     * @param       $path
     * @param Knife $knife
     */
    public function __construct($path, Knife $knife)
    {
        $this->path = $path;
        $this->knife = $knife;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return array
     */
    public function getNodes()
    {
        return $this->knife->getNodes();
    }
}
