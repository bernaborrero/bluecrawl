<?php

require_once 'BlueCrawl.php';

class BlueParse extends Thread
{
    private $crawler;
    private $url;

    public function __construct($crawler, $url)
    {
        $this->crawler = $crawler;
        $this->url = $url;
    }

    public function run()
    {
        $this->crawler->crawl($this->url);
    }
}
