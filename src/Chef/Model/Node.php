<?php

namespace Chef\Model;

class Node
{
    /** @var string */
    private $name;

    /** @var string */
    private $file;

    /**
     * Node constructor.
     *
     * @param $name
     * @param $file
     */
    public function __construct($name, $file)
    {
        $this->name = $name;
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

}
