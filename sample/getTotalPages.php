<?php

require_once dirname(__FILE__).'/config.php';
require_once dirname(__FILE__).'/../lib/amatools.php';

// 必要なパラメータを設定
$keywords = 'ノーブランド';
$search_index = 'Electronics';
$browse_node = '3477381';
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

$resItems = AmaTools::getTotalPages();
echo $resItems;
