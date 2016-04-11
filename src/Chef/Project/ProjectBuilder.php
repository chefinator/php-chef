<?php

namespace Chef\Project;

use Chef\Knife\NodeProvider;
use Chef\Knife\Parser\Knife as KnifeParser;
use Chef\Model\Knife;
use Chef\Model\Project;
use Chef\Utils\FileSystem\KnifeResolver;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class ProjectBuilder
{
    /** @var NullLogger */
    private $logger;

    /** @var KnifeResolver */
    private $knifeFileResolver;

    /**
     * ProjectBuilder constructor.
     *
     * @param LoggerInterface|null $logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger ?: new NullLogger();
        $this->knifeFileResolver = new KnifeResolver();
    }

    /**
     * @param $path
     *
     * @return Project
     */
    public function build($path)
    {
        $knifePath = $this->knifeFileResolver->resolve($path);
        $knife = (new KnifeParser($this->logger))->parse($knifePath);

        // Assume .chef/knife.rb
        $path = str_replace('/.chef/knife.rb', null, $knifePath->getRealPath());

        $nodeProvider = new NodeProvider($knife->getNodes(), $path);
        $nodes = $nodeProvider->getNodes();

        $knife = new Knife($nodes);

        return new Project($path, $knife);
    }
}
