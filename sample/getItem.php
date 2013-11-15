<?php

require_once dirname(__FILE__).'/config.php';
require_once dirname(__FILE__).'/../lib/amatools.php';

// 必要なパラメータを設定
$item_id = array('B00FGL9QVW','B00G21S5SK');

// 初期化
AmaTools::init();
// 配列をカンマで分けてstringにする。(10個まで可)
AmaTools::$item_id      = implode(",",$item_id);

$resItems = AmaTools::getItems();
$item_num = count($resItems);

for($i=0;$i<$item_num;$i++) {
    $item = $resItems[$i];
    echo 'ASIN:'.AmaTools::getAsin($item)."\n";
    echo 'URL:'.AmaTools::getUrl($item)."\n";
    echo 'TITLE:'.AmaTools::getTitle($item)."\n";
    echo 'PRICE:'.AmaTools::getPrice($item)."\n";
    echo 'PERCENTAGE:'.AmaTools::getPercentage($item)."\n";
    echo 'AVAILABILITY:'.AmaTools::getAvailability($item)."\n";
    echo 'CATEGORY:'.Amatools::getCategory($item)."\n";
    echo 'SALES_RANK:'.Amatools::getSalesRank($item)."\n";
}
echo $item_num;
