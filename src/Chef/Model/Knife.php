<?php

namespace Chef\Model;

class Knife
{
    /** @var array */
    private $roles;

    /** @var array */
    private $nodes;

    /** @var array */
    private $cookbooks;

    /**
     * Knife constructor.
     *
     * @param array $roles
     * @param array $nodes
     * @param array $cookbooks
     */
    public function __construct(array $nodes, array $roles = null, array $cookbooks = null)
    {
        $this->roles = $roles;
        $this->nodes = $nodes;
        $this->cookbooks = $cookbooks;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @return array
     */
    public function getNodes()
    {
        return $this->nodes;
    }

    /**
     * @return array
     */
    public function getCookbooks()
    {
        return $this->cookbooks;
    }
}
