<?php

namespace Chef\Utils\Github;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\Process\Process;

class Git
{
    const CLONE_DEFAULT = 'git clone https://github.com/%s.git -q -b %s %s';
    const CLONE_AUTH    = 'git clone https://%s:%s@github.com/%s.git -q -b %s %s';
    const CHECKOUT      = 'git fetch && git checkout origin/%s';

    /** @var LoggerInterface */
    private $logger;

    /**
     * Git constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger ?: new NullLogger();
    }

    /**
     * @param        $name
     * @param        $path
     * @param string $schema
     * @param string $revision
     * @param null   $username
     * @param null   $token
     *
     * @return bool
     */
    public function cloner($name, $path, $schema = 'https', $revision = 'master', $username = null, $token = null)
    {
        if ($schema !== 'https') {
            throw new \RuntimeException(sprintf('No support for anything but https right now'));
        }

        if ($username !== null && $token !== null) {
            $command = sprintf(
                self::CLONE_AUTH,
                $username,
                $token,
                $name,
                $revision,
                $path
            );
        } else {
            $command = sprintf(
                self::CLONE_DEFAULT,
                $name,
                $revision,
                $path
            );
        }

        $process = new Process($command);
        $process->run();

        foreach (explode(PHP_EOL, $process->getErrorOutput()) as $line) {
            if (strlen($line) <= 0) {
                continue;
            }
            $this->logger->info(sprintf('Git: %s', $line));
        }

        return $process->isSuccessful();
    }

    /**
     * @param $path
     * @param $revision
     *
     * @return bool
     */
    public function checkout($path, $revision)
    {
        $process = new Process(
            sprintf(self::CHECKOUT, $revision),
            $path
        );

        $process->run();

        foreach (explode(PHP_EOL, $process->getErrorOutput()) as $line) {
            if (strlen($line) <= 0) {
                continue;
            }
            $this->logger->info(sprintf('Git: %s', $line));
        }

        return $process->isSuccessful();
    }
}
