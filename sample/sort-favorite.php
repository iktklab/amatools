<?php

require_once dirname(__FILE__).'/config.php';
require_once dirname(__FILE__).'/../lib/amatools.php';


// 必要なパラメータを設定
$sort = 'salesrank';
$search_index = 'Music';
$browse_node = '562060';
$item_page = '1';

// 初期化
AmaTools::init();
AmaTools::$search_index  = $search_index;
AmaTools::$browse_node   = $browse_node;
AmaTools::$sort          = $sort;
AmaTools::$item_page     = $item_page;

$resItems = AmaTools::sortCategoryItems();
$item_num = count($resItems);

for($i=0;$i<$item_num;$i++) {
    $item = $resItems[$i];
    echo AmaTools::getAsin($item)."\n";
    echo AmaTools::getUrl($item)."\n";
    echo AmaTools::getTitle($item)."\n";
    echo AmaTools::getPrice($item)."\n";
    echo AmaTools::getPercentage($item)."\n";
    echo AmaTools::getAvailability($item)."\n";
}
