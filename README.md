# rtCamp Twitter Assignment

Live Demo : http://rt-camp.herokuapp.com/

---
+ Login using your twitter account
+ Pulls tweets from your feed
+ Search for friends and their tweets
+ Download option for CSV and XLS file formats

---

## Creating your app
Create your own app on https://apps.twitter.com/

Now replace the consumer key and secret from you app to the following **config.php** file:

```
define('CONSUMER_KEY', 'Your Consumer Key');
define('CONSUMER_SECRET', 'Your Consumer Secret');
define('OAUTH_CALLBACK', 'Your callback url');
``` 

## PHPunit test
Create a **composer.json** file in your project directory and edit as follows:
```
{
	"require" : {
		"phpunit/phpunit": "^4.8"
	}
}

```
 Now from commandline run **composer require phpunit/phpunit** and let it install phpunit and its dependencies.

### Third party libraries
1. Twitter API
-https://dev.twitter.com/overview/documentation
2. Foundation CSS
-http://foundation.zurb.com/sites/docs/
3. Modernizer JS
-https://modernizr.com/docs
4. jQuery
-https://api.jquery.com/
5. Bootstrap
-http://getbootstrap.com/javascript/
6. PHPExcel
-https://phpexcel.codeplex.com/

## Author

**Tarang Dave**
###[Github Profile](https://github.com/tarangdave)
