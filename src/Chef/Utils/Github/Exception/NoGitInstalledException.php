<?php

namespace Chef\Utils\Github\Exception;

class NoGitInstalledException extends \RuntimeException
{
    const MESSAGE = 'No git installation could be found on the system';
}
