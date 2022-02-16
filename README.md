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

By default an ArrayList will be returned, which can be rendered directed into a template:

Note: check the raw contents of the XML feed to identify fields names.

```html
<% loop $Feed %> 
  <h1>$Headline.XML</h1>
  <p>$Summary.XML</p>
<% end_loop %>
```
