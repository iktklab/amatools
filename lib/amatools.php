<?php

class AmaTools {

    // 取得する国別でURLを変更する
    public static $baseurl = 'http://ecs.amazonaws.jp/onca/xml';
    public static $verison = '2011-08-01';
    public static $service      = 'AWSECommerceService';

    public static $aws_access_key_id = '';
    public static $secret_access_key = '';
    public static $associate_tag     = '';

    public static $operation    = '';
    public static $item_id      = '';
    public static $keywords     = '';
    public static $search_index = '';
    public static $browse_node  = '';
    public static $sort         = '';
    public static $item_page    = '';
    public static $time_stamp   = null;
    public static $response_group = 'Images,Large,OfferFull';

    private static $params = array();
    private static $canonical_string = '';
    private static $signature = '';

    public static function init() {
        // 初期化
        self::$operation    = '';
        self::$item_id      = '';
        self::$keywords     = '';
        self::$search_index = '';
        self::$browse_node  = '';
        self::$sort         = '';
        self::$item_page    = '';
        self::$time_stamp   = null;
        self::$response_group = 'Images,Large,OfferFull';

        self::$params = array();
        self::$canonical_string = '';
        self::$signature = '';
    }

    public static function createTimeStamp() {
        return gmdate('Y-m-d\TH:i:s\Z');
    }

    public static function checkKeys() {
        if (defined('AWS_ACCESS_KEY_ID') && defined('SECRET_ACCESS_KEY')) {
            self::$aws_access_key_id = AWS_ACCESS_KEY_ID;
            self::$secret_access_key = SECRET_ACCESS_KEY;
        } else {
            exit("AWSのアクセス鍵or秘密鍵が設定されていません。configファイルが読み込まれているかどうか確認してください。");
        }
    }

    public static function checkAssociateTag() {
        if (defined('ASSOCIATE_TAG')) {
            self::$associate_tag = ASSOCIATE_TAG;
        } else {
            exit("ASSOCIATE_TAGが設定されていません。configファイルが読み込まれているかどうか確認してください。");
        }
    }

    public static function sortCategoryItems() {
        self::checkInputParams();
        self::$operation  = 'ItemSearch';
        self::pakageParams();
        $url = self::createUrl();
        $xml = simplexml_load_file($url)
            or die("XMLパースエラー");
        if (!isset($xml->Items->Item)) {
            throw new Exception('XMLのエラー');
        }
        return $xml->Items->Item;
    }

    public static function checkInputParams() {
        self::checkKeys();
        self::checkAssociateTag();
        self::$time_stamp = self::createTimeStamp();
    }


    public static function pakageParams() {

        $params = array();
        $params['Service']        = self::$service;
        $params['AWSAccessKeyId'] = self::$aws_access_key_id;
        $params['Version']        = self::$verison;
        $params['Operation']      = self::$operation;
        $params['SearchIndex']    = self::$search_index;
        $params['BrowseNode']     = self::$browse_node;
        $params['Keywords']       = self::$keywords;
        $params['ItemId']         = self::$item_id;
        $params['ResponseGroup']  = self::$response_group;
        $params['AssociateTag']   = self::$associate_tag;
        $params['Sort']           = self::$sort;
        $params['ItemPage']       = self::$item_page;
        $params['Timestamp']      = self::$time_stamp;

        // $paramsを昇順でソートする
        ksort($params);

        self::$params             = $params;
    }

    public static function createUrl() {
        $url = '';
        self::convertStringParams();
        self::createSignature();
        $url = self::$baseurl.'?'.self::$canonical_string.'&Signature='.self::urlencodeRfc3986(self::$signature);
        return $url;
    }

    public static function convertStringParams() {
        $canonical_string = '';
        foreach (self::$params as $k => $v) {
            $canonical_string .= '&'.self::urlencodeRfc3986($k).'='.self::urlencodeRfc3986($v);
        }
        self::$canonical_string = substr($canonical_string, 1);
    }

    public static function createSignature() {
        $canonical_string = self::$canonical_string;
        $parsed_url = parse_url(self::$baseurl);
        $string_to_sign = "GET\n{$parsed_url['host']}\n{$parsed_url['path']}\n{$canonical_string}";
        self::$signature = base64_encode(hash_hmac('sha256', $string_to_sign, self::$secret_access_key, true));
    }

    public static function urlencodeRfc3986($str) {
        return str_replace('%7E', '~', rawurlencode($str));
    }

    public static function getAsin($xml) {
        return $xml->ASIN;
    }

    public static function getUrl($xml) {
        return 'http://amazon.jp/o/ASIN/'.$xml->ASIN;
    }

    public static function getAffiliateUrl($xml) {
        return 'http://amazon.jp/o/ASIN/'.$xml->ASIN.'/'.ASSOCIATE_TAG;
    }

    public static function getTitle($xml) {
        return $xml->ItemAttributes->Title;
    }

    public static function getPrice($xml) {
        if (!isset($xml->Offers->Offer->OfferListing->Price->Amount)) return '';
        return $xml->Offers->Offer->OfferListing->Price->Amount;
    }

    public static function getAvailability($xml) {
        if (!isset($xml->Offers->Offer->OfferListing->Availability)) return '';
        return $xml->Offers->Offer->OfferListing->Availability;
    }

    public static function getLargeImage($xml) {
        return $xml->LargeImage->URL;
    }

    public static function getSalesRank($xml) {
        return $xml->SalesRank;
    }

    public static function getCategory($xml) {
        return $xml->ItemAttributes->ProductGroup;
    }

    public static function getPercentage($xml) {
        if (!isset($xml->Offers->Offer->OfferListing->PercentageSaved)) return '0';
        if ($per = intval($xml->Offers->Offer->OfferListing->PercentageSaved)) {
            return $per;
        } else {
            return '0';
        }
    }
}
