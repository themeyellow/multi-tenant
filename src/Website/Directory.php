<?php

namespace Hyn\Tenancy\Website;

use Hyn\Tenancy\Models\Website;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Filesystem\Filesystem;

class Directory implements Filesystem
{
    /**
     * @var array
     */
    protected $folders;
    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var Website
     */
    protected $website;

    public function __construct(Filesystem $filesystem, Repository $config)
    {
        $this->filesystem = $filesystem;
        $this->folders = $config->get('tenancy.folders', []);
    }

    /**
     * @param string|null $path
     * @return bool
     */
    public function exists($path): bool
    {
        return $this->website && $this->filesystem->exists($this->path($path));
    }

    /**
     * @param Website $website
     * @return Directory
     */
    public function setWebsite(Website $website): Directory
    {
        $this->website = $website;
        return $this;
    }

    /**
     * @param string $path
     * @return string
     * @internal param string $folder
     */
    public function path(string $path = ''): string
    {
        return sprintf(
            "%s%s%s",
            $this->website->uuid,
            DIRECTORY_SEPARATOR,
            $path
        );
    }

    /**
     * Get the contents of a file.
     *
     * @param  string $path
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function get($path)
    {
        return $this->filesystem->get(
            $this->path($path)
        );
    }

    /**
     * Write the contents of a file.
     *
     * @param  string $path
     * @param  string|resource $contents
     * @param  string $visibility
     * @return bool
     */
    public function put($path, $contents, $visibility = null)
    {
        return $this->filesystem->put(
            $this->path($path),
            $contents,
            $visibility
        );
    }

    /**
     * Get the visibility for the given path.
     *
     * @param  string $path
     * @return string
     */
    public function getVisibility($path)
    {
        return $this->filesystem->getVisibility(
            $this->path($path)
        );
    }

    /**
     * Set the visibility for the given path.
     *
     * @param  string $path
     * @param  string $visibility
     * @return void
     */
    public function setVisibility($path, $visibility)
    {
        $this->filesystem->setVisibility(
            $this->path($path),
            $visibility
        );
    }

    /**
     * Prepend to a file.
     *
     * @param  string $path
     * @param  string $data
     * @return int
     */
    public function prepend($path, $data)
    {
        return $this->filesystem->prepend(
            $this->path($path),
            $data
        );
    }

    /**
     * Append to a file.
     *
     * @param  string $path
     * @param  string $data
     * @return int
     */
    public function append($path, $data)
    {
        return $this->filesystem->append(
            $this->path($path),
            $data
        );
    }

    /**
     * Delete the file at a given path.
     *
     * @param  string|array $paths
     * @return bool
     */
    public function delete($paths)
    {
        return $this->filesystem->delete(
            collect((array)$paths)
                ->map(function ($path) {
                    return $this->path($path);
                })
                ->values()
        );
    }

    /**
     * Copy a file to a new location.
     *
     * @param  string $from
     * @param  string $to
     * @return bool
     */
    public function copy($from, $to)
    {
        return $this->filesystem->copy(
            $this->path($from),
            $this->path($to)
        );
    }

    /**
     * Move a file to a new location.
     *
     * @param  string $from
     * @param  string $to
     * @return bool
     */
    public function move($from, $to)
    {
        return $this->filesystem->move(
            $this->path($from),
            $this->path($to)
        );
    }

    /**
     * Get the file size of a given file.
     *
     * @param  string $path
     * @return int
     */
    public function size($path)
    {
        return $this->filesystem->size(
            $this->path($path)
        );
    }

    /**
     * Get the file's last modification time.
     *
     * @param  string $path
     * @return int
     */
    public function lastModified($path)
    {
        return $this->filesystem->lastModified(
            $this->path($path)
        );
    }

    /**
     * Get an array of all files in a directory.
     *
     * @param  string|null $directory
     * @param  bool $recursive
     * @return array
     */
    public function files($directory = null, $recursive = false)
    {
        return $this->filesystem->files(
            $this->path($directory),
            $recursive
        );
    }

    /**
     * Get all of the files from the given directory (recursive).
     *
     * @param  string|null $directory
     * @return array
     */
    public function allFiles($directory = null)
    {
        return $this->filesystem->allFiles(
            $this->path($directory)
        );
    }

    /**
     * Get all of the directories within a given directory.
     *
     * @param  string|null $directory
     * @param  bool $recursive
     * @return array
     */
    public function directories($directory = null, $recursive = false)
    {
        return $this->filesystem->directories(
            $this->path($directory),
            $recursive
        );
    }

    /**
     * Get all (recursive) of the directories within a given directory.
     *
     * @param  string|null $directory
     * @return array
     */
    public function allDirectories($directory = null)
    {
        return $this->filesystem->allDirectories(
            $this->path($directory)
        );
    }

    /**
     * Create a directory.
     *
     * @param  string $path
     * @return bool
     */
    public function makeDirectory($path)
    {
        return $this->filesystem->makeDirectory(
            $this->path($path)
        );
    }

    /**
     * Recursively delete a directory.
     *
     * @param  string $directory
     * @return bool
     */
    public function deleteDirectory($directory)
    {
        return $this->filesystem->deleteDirectory(
            $this->path($directory)
        );
    }
}