<?php

namespace Chef\Knife;

use Chef\Utils\FileSystem\JsonChefFileTrait;

class NodeProvider
{
    use JsonChefFileTrait;

    /** @var array */
    private $nodes;

    /** @var string */
    private $path;

    /**
     * RoleProvider constructor.
     *
     * @param array  $nodes
     * @param string $path
     */
    public function __construct(array $nodes, $path = '/')
    {
        $this->nodes = $nodes;
        $this->path = $path;
    }

    public function getNodes()
    {
        return $this->getFiles($this->nodes, $this->path);
    }
}
