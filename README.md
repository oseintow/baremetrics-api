# Baremetrics Api

Baremetrics-api is a simple package which helps to integrate baremetrics into your app.

## Installation

Add package to composer.json

    composer require oseintow/baremetrics
    
**NB**: Create account and Obtain your api_key from baremetrics    
    
# For Laravel users

Add the service provider to config/app.php in the providers array.

```php5
<?php

'providers' => [
    ...
    Oseintow\Baremetrics\BaremetricsServiceProvider::class,
],
```

Setup alias for the Facade

```php5
<?php

'aliases' => [
    ...
    'Baremetrics' => Oseintow\Baremetrics\Facades\Baremetrics::class,
],
```

## Usage

This process will enable us to obtain the baremetrics sources

```php5
use Oseintow\Baremetrics\Facades\Baremetrics;

Route::get("baremetric_sources",function()
{
    // If you have "BAREMETRICS_API_KEY" set in your env file then the will be no need to set `setApiKey`
    $apiKey = "xx-xx-x-xx-xx-xx"
    $response = Baremetrics::setApiKey($apikey)->get("sources");
    
    $sourceId = "";
    
    foreach($response['sources] as $sourece){
        if($source['provider'] == "baremetrics"){
            $sourceId = $source['id'];
        }
    }
});
```


To access API resource use

```php5
Baremetrics::get("resource uri", ["query string params"]);
Baremetrics::post("resource uri", ["post body"]);
Baremetrics::put("resource uri", ["put body"]);
Baremetrics::delete("resource uri");
```

Let use our access token to get products from baremetrics.

**NB:** You can use this to access any resource on baremetrics (be it plans, subscription, customers, etc)

```php5
$sourceId = "1233243";
$products = Baremetrics::setApiKey("xx-xxx-xx-xx-xx")->get("{$sourceId}/plans");
```

To pass query params

```php5
// returns Collection
$sourceId = "1233243";
$baremetrics = Baremetrics::setApiKey("xx-xxx-xx-xx-xx");
$plans = $baremetrics->get(""{$sourceId}/plans", ["search"=> "xxx-xxxx-xxx"]);
```

## Controller Example

If you prefer to use dependency injection over facades like me, then you can inject the Class:

```php5
use Illuminate\Http\Request;
use Oseintow\Baremetrics\Baremetrics;

class Foo
{
    protected $baremetrics;

    public function __construct(Baremetrics $baremetrics)
    {
        $this->baremetrics = $baremetrics;
    }

    /*
    * returns Collection
    */
    public function getPlans(Request $request)
    {
        $sourceId = "xxxx-xx-xx";
        
        $plans = $this->baremetrics->setApiKey("xx-xxx-xx-xx-xx")
            ->get('{$sourceId}/plans');
    }
}
```

## Miscellaneous

To get Response headers

```php5
Baremetrics::getHeaders();
```

To get specific header
```php5
Baremetrics::getHeader("Content-Type");
```

Check if header exist
```php5
if(Baremetrics::hasHeader("Content-Type")){
    echo "Yes header exist";
}
```

To get response status code or status message
```php5
Baremetrics::getStatusCode(); // 200
Baremetrics::getReasonPhrase(); // ok
```

## For php users

```php5
$baremetrics = new Baremetrics();

$sources = $baremetrics->setApiKey("xxx-xxxx-xxxx-xxx-xx")->get("sources");

$sourceId= "123456";
$plans = $baremetrics->get("{$sourceid}/plans");
```
