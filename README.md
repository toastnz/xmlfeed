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

By default an ArrayList will be returned, which can be rendered directed into a template.

Note: you must check the raw contents of the XML feed to identify fields names.

## Example

XML:

```xml
<RSS>
  <Items>
    <Item>
      <Headline>Sed in viverra dui. Nullam vel congue massa.</Headline>
      <Summary>Ut id nisi vitae massa consectetur dictum quis sed sapien. At euismod turpis</Summary>
    </Item>
    <Item>
      <Headline>Aliquam dictum finibus magna</Headline>
      <Summary>Cras mattis non elit sit amet vulputate. Nunc at metus sed sapien eros.</Summary>
    </Item>
  </Items>
</RSS>

```

PHP:

```php
use Toast\XMLFeed;

...

class PageController extends ContentController 
{
  public function getNewsFeed() 
  {
    return Feed::get('https://newswebsite.url/news-feed.xml', 'RSS.Items');
  }  
}      
```

Template:

```html
<% loop $NewsFeed %> 
  <h1>$Headline.XML</h1>
  <p>$Summary.XML</p>
<% end_loop %>
```
