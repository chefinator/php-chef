<?php

namespace Chef\Project\Path;

use Chef\Project\ProjectPathProviderInterface;
use Chef\Utils\Github\RepositoryManager;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Class GitHubRepositoryProjectPathProvider
 *
 * @package Chef\Project\Path
 */
class GitHubRepositoryProjectPathProvider implements ProjectPathProviderInterface
{
    /** @var LoggerInterface */
    private $logger;

    /** @var string */
    private $username;

    /** @var string */
    private $token;

    /** @var string */
    private $path;

    /** @var string */
    private $revision;

    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger ?: new NullLogger();
    }

    /**
     * @param      $token
     * @param null $username
     */
    public function setAuthentication($token, $username = null)
    {
        $this->username = $username ?: get_current_user();
        $this->token = $token;
        $this->revision = 'master';
    }

    /**
     * @inheritdoc
     */
    public function exists($name)
    {
        $path = $this->getProjectPath($name);

        $this->logger->info(sprintf('%s: Project Path %s', __NAMESPACE__, $path));

        // Fetch & Clone the repo
        $git = new RepositoryManager($this->logger, $path, $name, $this->username, $this->token);
        $git->checkout($this->revision);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function getProjectPath($name)
    {
        return sprintf('%s/%s', $this->path, str_replace('/', '-', $name));
    }

    /**
     * @param $revision
     */
    public function setRevision($revision)
    {
        $this->revision = $revision;
    }

    /**
     * @inheritdoc
     */
    public function setPath($path)
    {
        if (!is_writable($path)) {
            throw new \RuntimeException(sprintf('Project Provider Path is not writeable, path: %s', $this->path));
        }

        $this->path = $path;
    }
}
