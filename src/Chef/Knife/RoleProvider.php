<?php

namespace Chef\Knife;

use Chef\Utils\FileSystem\JsonChefFileTrait;

class RoleProvider
{
    use JsonChefFileTrait;

    /** @var array */
    private $roles;

    /** @var string */
    private $path;

    /**
     * RoleProvider constructor.
     *
     * @param array  $roles
     * @param string $path
     */
    public function __construct(array $roles, $path = '/')
    {
        $this->roles = $roles;
        $this->path = $path;
    }

    public function getRoles()
    {
        return $this->getFiles($this->roles, $this->path);
    }
}
