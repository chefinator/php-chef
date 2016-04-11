<?php

namespace Chef\Utils\Github;

use Chef\Utils\Github\Exception\NoGitInstalledException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\Process;

class RepositoryManager
{
    /** @var LoggerInterface */
    private $logger;
    private $path;
    private $username;
    private $token;
    private $name;

    /**
     * RepositoryManager constructor.
     *
     * @param LoggerInterface $logger
     * @param                 $path
     * @param                 $name
     * @param                 $username
     * @param                 $token
     */
    public function __construct(LoggerInterface $logger, $path, $name, $username, $token)
    {
        $this->path = $path;
        $this->name = $name;
        $this->username = $username;
        $this->token = $token;
        $this->logger = $logger ?: new NullLogger();
    }

    /**
     * @param $revision
     *
     * @return bool
     */
    public function checkout($revision)
    {
        $this->isGitInstalled();
        $git = new Git($this->logger);

        // Clone & Checkout
        if (!is_dir($this->path)) {
            $this->logger->info(sprintf('%s: Git directory does not exist, cloning a new one', __NAMESPACE__));

            try {
                return $git->cloner($this->name, $this->path, 'https', $revision, $this->username, $this->token);
            } catch (ProcessTimedOutException $e) {
                $this->logger->error('%s: Git clone timed out for %s', __NAMESPACE__, $this->name);
                throw $e;
            }
        }

        $this->logger->info(sprintf('%s: Checking out git at %s', __NAMESPACE__, $revision));

        try {
            return $git->checkout($this->path, $revision);
        } catch (ProcessTimedOutException $e) {
            $this->logger->error('%s: Git checkout timed out for %s', __NAMESPACE__, $this->name);
            throw $e;
        }

    }

    /**
     * @return bool
     */
    private function isGitInstalled()
    {
        $process = new Process('which git');
        $process->run();

        if ($process->isSuccessful()) {
            return true;
        }

        throw new NoGitInstalledException(NoGitInstalledException::MESSAGE);
    }
}
