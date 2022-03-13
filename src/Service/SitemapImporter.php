<?php

namespace Varmeusz\CacheWarmerSitemap\Service;

use Exception;

class SitemapImporter
{
    /**
     * @param $path
     * @return array
     * @throws Exception
     */
    public function parseSitemap($path): array
    {
        $fileInfo = pathinfo($path);
        if($fileInfo["extension"] != "xml") {
            throw new Exception("File type not supported");
        }
        $xmlData = simplexml_load_file($path);
        if(!$xmlData) {
            throw new Exception("Failed to load XML data");
        }
        $jsonData = json_encode($xmlData);
        $xmlArray = json_decode($jsonData, true);
        if(!isset($xmlArray["url"])) {
            throw new Exception("File does not match sitemap specification");
        }
        if(count($xmlArray["url"]) == 0){
            throw new Exception("XML File has no urls");
        }
        $urls = [];
        foreach($xmlArray["url"] as $url) {
            if(!isset($url["loc"])){
                continue;
            }
            $urlInfo = parse_url($url["loc"]);
            $urls[] = [
                "website" => $urlInfo["host"],
                "page" => $urlInfo["path"]
            ];
        }
        return $urls;
    }
}