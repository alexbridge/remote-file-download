<?php

/**
 * Controllable downloader
 */
class FilesDownloader
{
    /**
     * @var string
     */
    private $source;

    /**
     * @var string
     */
    private $destination;

    /**
     * @var int
     */
    private $chunkSize = 8096;

    /**
     * @var callable
     */
    private $chunkCallback;

    /**
     * FilesDownloader constructor.
     * @param string|resource $source
     * @param string|resource $destination
     */
    public function __construct($source, $destination)
    {
        $this->source = $source;
        $this->destination = $destination;
    }

    /**
     * @param int $chunkSize
     */
    public function setChunkSize($chunkSize)
    {
        $this->chunkSize = $chunkSize;
    }

    /**
     * @param callable $chunkCallback
     */
    public function setChunkCallback($chunkCallback)
    {
        $this->chunkCallback = $chunkCallback;
    }

    public function download()
    {
        $source = $this->source;
        if(!is_resource($this->source)) {
            $source = fopen($this->source, 'rb');
            if (!$source) {
                throw new Exception('Can not open source file for read');
            }
        }

        $destination = $this->destination;
        if(!is_resource($this->destination)) {
            if (!file_exists($this->destination)) {
                if (!touch($this->destination)) {
                    throw new Exception('Destination file is not reachable');
                }
            }

            $destination = fopen($this->destination, 'wb');
            if (!$destination) {
                throw new Exception('Can not open destination file for read');
            }
        }

        while (!feof($source)) {
            $chunk = fread($source, $this->chunkSize);
            fwrite($destination, $chunk);
            if(is_callable($this->chunkCallback)) {
                call_user_func($this->chunkCallback, $chunk);
            }
        }

        fclose($source);
        fclose($destination);
    }
}