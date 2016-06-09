<?php

require_once 'classes/BlueCrawl.php';

if (isset($_GET['url'])) {
    $crawler = new BlueCrawl(urldecode($_GET['url']));
    $crawler->start();
}
