<?php

require_once 'BlueParse.php';

class BlueCrawl
{
    private $domain;
    private $parsedUrls;
    private $lock;

    /**
    * @param $domain string format: https://www.example.com
    */
    public function __construct($domain)
    {
        $this->domain = $domain;
        $this->parsedUrls = array();
        $this->lock = false;
    }

    public function start()
    {
        $this->crawl($this->domain);
    }

    public function crawl($url)
    {
        if (!$this->isParsedUrl($url)) {
            $html = $this->getHtml($url);
            $this->addUrl($url);

            $urls = $this->extractUrlsFrom($html);
            foreach ($urls as $url) {
                $parser = new BlueParse($this, $url);
                $parser->start();
            }
        }
    }

    private function getHtml($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $html = curl_exec($ch);
        curl_close($ch);

        return $html;
    }

    private function extractUrlsFrom($html)
    {
        if (preg_match_all('#(src|href)=["\'](' . $this->domain . '/[^"\']+)["\']#i', $html, $links)) {
            return $links[2];
        }

        return array();
    }

    private function isParsedUrl($url)
    {
        while ($this->lock) { }
        $this->lock = true;
        $result = in_array($url, $this->parsedUrls);
        $this->lock = false;
        return $result;
    }

    private function addUrl($url)
    {
        while ($this->lock) { }
        $this->lock = true;
        $this->parsedUrls[] = $url;
        $this->lock = false;
    }
}
