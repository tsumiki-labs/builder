<?php

/**
 * @copyright  Copyright (c) 2026 Tsumiki. labs
 * @license    MIT License
 * @link       https://github.com/tsumiki-labs
 **/
declare(strict_types=1);

use Phing\Exception\BuildException;
use Phing\Task;

class ManifestParserTask extends Task
{
    private $path;
    private $extensions;
    private $includeFiles;

    public function main(): void
    {
        foreach (glob($this->path . '/*.xml') as $manifestFile) {
            if ($manifest = simplexml_load_file($manifestFile)) {
                if ($manifest->getName() == 'extension') {
                    $extensions = [];
                    $includeFiles = [];
                    $type = (string)$manifest->attributes()?->type;
                    if ($type == 'package') {
                        foreach ($manifest->files->file as $file) {
                            $project = dirname($manifestFile) . '/../' . pathinfo((string)$file, PATHINFO_FILENAME);
                            if (is_dir($project)) {
                                $extensions[] = realpath($project);
                                $includeFiles[] = basename((string)$file);
                            }
                        };
                    }
                    $extensions[] = $this->path;
                    $this->project->setUserProperty($this->extensions, implode(',', $extensions));
                    $this->project->setUserProperty($this->includeFiles, implode(',', $includeFiles));
                    return;
                }
            }
        }
        throw new BuildException("Manifest file not found");
    }

    public function setIncludefiles(string $includeFiles): void
    {
        $this->includeFiles = $includeFiles;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function setExtensions(string $extensions): void
    {
        $this->extensions = $extensions;
    }

}