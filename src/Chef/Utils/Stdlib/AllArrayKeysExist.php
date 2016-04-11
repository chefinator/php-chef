<?php

namespace Chef\Utils\Stdlib;

class AllArrayKeysExist
{
    /**
     * @param $keys
     * @param $array
     *
     * @return bool
     */
    public function __invoke($keys, $array)
    {
        foreach ($keys as $key) {
            if (!array_key_exists($key, $array)) {
                throw new \RuntimeException(sprintf('Key %s does not exist within the array', $key));
            }
        }

        return true;
    }
}
