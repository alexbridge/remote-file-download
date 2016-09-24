Remote files downloader

This is a helper to download remote files in a controllable way.

For example user provide XML data to be processed with Yours system, but You want control a file size, provided by users.  

Example:

```
$currentSize = 0;
$maxAllowedFileSize = 52428800; // 50M maximum files

$source = ''; // http file
$destination = tmpfile();
$fileDownloader = new FilesDownloader($source, $destination);
$fileDownloader->setChunkCallback(function ($chunk) use (&$currSize, $maxAllowedFileSize) {
    $currSize += mb_strlen($chunk);
    if ($currSize > $maxAllowedFileSize) {
        throw new \Exception('File size exceeded');
    }
});
$fileDownloader->download();
```

Enjoy!
