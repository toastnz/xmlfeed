<?php

namespace Toast\XMLFeed;

use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;
use Psr\SimpleCache\CacheInterface;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Injector\Injector;


class Feed 
{
    public static function get($url = null, $xmlPath = null, $cacheLifetime = 300, $asArray = false, $flushCache = false)
    {
        $url = $url ?: Config::inst()->get(self::class, 'URL');

        if (!$url) {
            throw new \InvalidArgumentException('No URL specified for XML Feed');
        }

        $cache = Injector::inst()->get(CacheInterface::class . '.toastXMLFeedCache');

        if ($flushCache) {
            $cache->clear();
        }

        $arrayOutput = null;
        
        if ($rawContents = $cache->get('XMLContent')) {
            $arrayOutput = self::xmlToArray($rawContents);
        }

        if (!$arrayOutput) {
            $rawContents = self::fetch($url);
            $cacheLifetime = $cacheLifetime ?: (int)(Config::inst()->get(self::class, 'CacheLifetime'));
            $cache->set('XMLContent', $rawContents, $cacheLifetime);
            $arrayOutput = self::xmlToArray($rawContents);
        }

        if (is_array($arrayOutput) && $xmlPath) {
            $pathParts = explode('.', $xmlPath);
            foreach($pathParts as $part) {
                if (isset($arrayOutput[$part])) {
                    $arrayOutput = $arrayOutput[$part];
                }
            }
        }

        if ($asArray) {
            return $arrayOutput;
        }

        $output = ArrayList::create();
        $output->merge(self::arrayToViewableData($arrayOutput));
        return $output;

    }

    private static function fetch($url)
    {        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $contents = curl_exec($ch);
        curl_close($ch);
        return $contents;
    }

    private static function xmlToArray($data)
    {
        if ($xml = @simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA)) {
            if ($json = @json_encode($xml)) {
                return @json_decode($json, true);
            }
        }
    }


	public static function arrayToViewableData($array) {
		if(is_array($array)) {
			$tmp = array_keys($array);
			$assoc = ($tmp != array_keys($tmp));
			switch($assoc) {
				case true:
					$dataObject = ArrayData::create();

					foreach($array as $k => $v) {
						if(is_array($v)) {
							$dataObject->setField($k, self::arrayToViewableData($v));
						} else {
							$dataObject->setField($k, $v);
						}
					}
					return $dataObject;
					break;

				case false:
					$list = ArrayList::create();
					foreach($array as $k => $v){
						$list->push(self::arrayToViewableData($v));
					}
					return $list;
					break;
			}
		}
		return $array;
	}

}
