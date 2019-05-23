<?php
/**
 * Created by PhpStorm.
 * User: doctor
 * Date: 21.05.19
 * Time: 16:36
 */


interface IDownloader
{
    /**
     * @param string $url
     * @return string
     */
    public function download(string $url): string;
}

class SimpleDownloader implements IDownloader
{
    /**
     * @inheritdoc
     */
    public function download(string $url): string
    {
        // Downloading a file from the Internet.
        $result = file_get_contents($url);
        return $result;
    }
}

/**
 * @param IDownloader $downloader
 */
function clientCode(IDownloader $downloader)
{
    // ...
    $downloader->download("http://example.com/");
    $downloader->download("http://example2.com/");
    $downloader->download("http://example.com/");
    $downloader->download("http://example3.com/");
    // ...
}



















class CachingDownloader implements IDownloader
{
    /**
     * @var SimpleDownloader
     */
    protected $downloader;

    /**
     * @var string[]
     */
    private $cache = [];

    public function __construct(SimpleDownloader $downloader)
    {
        $this->downloader = $downloader;
    }

    public function download(string $url): string
    {
        if (!isset($this->cache[$url])) {
            $this->cache[$url] = $this->downloader->download($url);
        }
        return $this->cache[$url];
    }
}


$simpleDownloader = new SimpleDownloader();
$downloader = new CachingDownloader($simpleDownloader);
clientCode($downloader);
