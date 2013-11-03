<?php

require_once dirname(__FILE__).'/config.php';
require_once dirname(__FILE__).'/../lib/amatools.php';

// 必要なパラメータを設定
$keywords = '互換';
$search_index = 'Electronics';
$browse_node = '3210991';
$item_page = '1';

// BrowseNodeをいれる場合はSearchIndexはBlended以外に
// $search_index = 'VideoGames';
// $browse_node = '637394';
// $sort = 'salesrank';

// 初期化
AmaTools::init();
AmaTools::$keywords      = $keywords;
AmaTools::$search_index  = $search_index;
AmaTools::$browse_node   = $browse_node;
AmaTools::$item_page     = $item_page;

$resItems = AmaTools::salesRankItems();
$item_num = count($resItems);

for($i=0;$i<$item_num;$i++) {
    $item = $resItems[$i];
    echo AmaTools::getAsin($item)."\n";
    echo AmaTools::getUrl($item)."\n";
    echo AmaTools::getTitle($item)."\n";
    echo AmaTools::getPrice($item)."\n";
    echo AmaTools::getPercentage($item)."\n";
    echo AmaTools::getAvailability($item)."\n";
    echo Amatools::getCategory($item)."\n";
    echo Amatools::getSalesRank($item)."\n";
}
echo $item_num;