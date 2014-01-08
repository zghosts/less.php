[Less.php](http://lessphp.gpeasy.com)
========

The **dynamic** stylesheet language.

<http://lesscss.org>

about
-----

This is a PHP port of the official LESS processor <http://lesscss.org> and should produce the same results as LESS 1.4.2.

Most of the code structure remains the same, which should allow for fairly easy updates in the future.
Namespaces, anonymous functions and shorthand ternary operators - `?:` have been removed to make this package compatible with php 5.2+.

There are still a few unsupported LESS features:

- Evaluation of JavaScript expressions within back-ticks (for obvious reasons).
- Definition of custom functions - will be added to the `Less_Environment` class.


use
---

### Installation
[Download the latest release](https://github.com/oyejorge/less.php/releases) and upload the php files to your server.


### Parsing Strings

```php
<?php
$parser = new Less_Parser();
$parser->parse( '@color: #4D926F; #header { color: @color; } h2 { color: @color; }' );
$css = $parser->getCss();
```


### Parsing Less Files
The parseFile() function takes two arguments:

1. The absolute path of the .less file to be parsed
2. The url root to prepend to any relative image or @import urls in the .less file.

```php
<?php
$parser = new Less_Parser();
$parser->parseFile( '/var/www/mysite/bootstrap.less', 'http://example.com/mysite/' );
$css = $parser->getCss();
```


### Handling Invalid Less
An exception will be thrown if the compiler encounters invalid less

```php
<?php
try{
	$parser = new Less_Parser();
	$parser->parseFile( '/var/www/mysite/bootstrap.less', 'http://example.com/mysite/' );
	$css = $parser->getCss();
}catch(Exception $e){
	$error_message = $e->getMessage();
}
```


### Parsing Multiple Sources
php.less can parse multiple sources to generate a single css file

```php
<?php
$parser = new Less_Parser();
$parser->parseFile( '/var/www/mysite/bootstrap.less', '/mysite/' );
$parser->parse( '@color: #4D926F; #header { color: @color; } h2 { color: @color; }' );
$css = $parser->getCss();
```


### Caching CSS
Use the Less_Cache class to save and reuse the results of compiled less files.
This method we check the modified time of each less file (including imported files) and regenerate when changes are found.
Note: When changes are found, this method will return a different file name for the new cached content.

```php
<?php
$to_cache = array( '/var/www/mysite/bootstrap.less' => '/mysite/' );
Less_Cache::$cache_dir = '/var/www/writable_folder';
$css_file_name = Less_Cache::Get( $to_cache );
$compiled = file_get_contents( '/var/www/writable_folder/'.$css_file_name );
```


### Getting Info About The Parsed Files
php.less can tell you which .less files were imported and parsed.

```php
<?php
$parser = new Less_Parser();
$parser->parseFile( '/var/www/mysite/bootstrap.less', '/mysite/' );
$css = $parser->getCss();
$imported_files = $parser->allParsedFiles();
```


### Compressing Output
You can tell less.php to remove comments and whitespace to generate minimized css files.

```php
<?php
$options = array( 'compress'=>true );
$parser = new Less_Parser( $options );
$parser->parseFile( '/var/www/mysite/bootstrap.less', '/mysite/' );
$css = $parser->getCss();
```


### Import Directories
By default, php.less will look for @imports in the directory of the file passed to parsefile().
If you're using parse() or if @imports reside in different directories, you can tell php.less where to look.

```php
<?php
$directories = array( '/var/www/mysite/bootstrap/' => '/mysite/bootstrap/' );
$parser = new Less_Parser();
$parser->SetImportDirs( $directories );
$parser->parseFile( '/var/www/mysite/theme.less', '/mysite/' );
$css = $parser->getCss();
```


### Parser Caching
php.less will save serialized parser data for each .less file if a writable folder is passed to the SetCacheDir() method.
Note: This feature only caches intermediate parsing results to improve the performance of repeated css generation.
Your application should cache any css generated by php.less.

```php
<?php
$parser = new Less_Parser();
$parser->SetCacheDir( '/var/www/writable_folder' );
$parser->parseFile( '/var/www/mysite/bootstrap.less', '/mysite/' );
$css = $parser->getCss();
```


credits
---
php.less was originally ported to php by [Matt Agar](https://github.com/agar) and then updated by [Martin Jantošovič](https://github.com/Mordred).

