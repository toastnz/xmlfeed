# SilverStripe XML Feed helper

Helper functions to retrieve external XML feed, cache and render into templates.

## Requirements

* silverstripe/cms ^4.3.x
* silverstripe/framework ^4.3.x

## Installation

```bash
composer require toastnz/xmlfeed
```

## Configuration

Configure the default Cache Lifetime or default feed URL:

```yaml
Toast\XMLFeed\Feed:
  CacheLifetime: 3600
  URL: "https://external.feed.url/feed.xml"
```

## Usage

```php
use Toast\XMLFeed;

$feed = Feed::get($url = null, $xmlPath = null, $cacheLifetime = 300, $asArray = false, $flushCache = false);
```
