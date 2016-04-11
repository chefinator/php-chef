<?php

namespace Chef\Knife\Parser;

use Chef\Utils\Stdlib\AllArrayKeysExist;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use SplFileObject;

class Knife
{
    /** @var NullLogger */
    private $logger;

    /** @var array */
    private $filters;

    /** @var AllArrayKeysExist */
    private $allArrayKeysExist;

    /**
     * KnifeFile constructor.
     *
     * @param LoggerInterface|null $logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger ?: new NullLogger();
        $this->allArrayKeysExist = new AllArrayKeysExist();

        $this->filters = [
            'node_path',
            'role_path',
            'cookbook_path',
        ];
    }

    /**
     * @param \SplFileInfo $file
     *
     * @return KnifeResult
     */
    public function parse(\SplFileInfo $file)
    {
        $file = new SplFileObject($file->getPathname());
        $configuration = [];

        while (!$file->eof()) {
            $line = $file->fgets();

            if ($this->hasConfigurationValue($line)) {
                $keyValue = explode(' ', preg_replace('/\s\s+/', ' ', $line), 2);

                if (count($keyValue) === 2) {
                    $configurationOption = $this->sanitizeKeyValue($keyValue);

                    if ($configurationOption !== false) {
                        $configuration = array_merge($configuration, $configurationOption);
                    } else {
                        $this->logger->info(
                            sprintf('%s: Could not parse key/value', __NAMESPACE__),
                            [
                                'value' => print_r($keyValue, true),
                            ]
                        );
                    }
                }

                unset($keyValue);
                unset($configurationOption);
            }
        }

        call_user_func_array($this->allArrayKeysExist, [$this->filters, $configuration]);

        return new KnifeResult(
            $configuration['role_path'],
            $configuration['node_path'],
            $configuration['cookbook_path']
        );
    }

    /**
     * @param $keyValue
     *
     * @return array|bool
     */
    private function sanitizeKeyValue($keyValue)
    {
        if (!in_array($keyValue[0], $this->filters)) {
            return false;
        }

        $keyValue[0] = preg_replace('/[^A-Z_]/i', null, $keyValue[0]);
        $keyValue[1] = preg_replace('/[^\,A-Z_\-0-9]/i', null, $keyValue[1]);

        if (stripos($keyValue[1], ',')) {
            $keyValue[1] = explode(',', $keyValue[1]);
        } else {
            $keyValue[1] = [$keyValue[1]];
        }

        return [$keyValue[0] => $keyValue[1]];
    }

    /**
     * @param $value
     *
     * @return bool
     */
    private function hasConfigurationValue($value)
    {
        foreach ($this->filters as $needle) {
            if (stripos($value, $needle) !== false) {
                return true;
            }
        }

        return false;
    }
}
