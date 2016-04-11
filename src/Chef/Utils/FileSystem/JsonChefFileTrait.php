<?php

namespace Chef\Utils\FileSystem;

trait JsonChefFileTrait
{
    /**
     * @param array $files
     * @param       $path
     *
     * @return array
     */
    protected function getFiles(array $files, $path)
    {
        $result = [];

        foreach ($files as $filename) {
            $iterator = new \GlobIterator(sprintf('%s/%s/*.json', $path, $filename));

            /** @var \SplFileInfo $file */
            foreach ($iterator as $file) {
                $name = str_replace(sprintf('.%s', $file->getExtension()), null, $file->getFilename());

                $result[$name] = $file->getPathname();
            }

            unset($iterator);
        }

        return $result;
    }
}
