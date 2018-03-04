# JSON encode and decode PSR-7 requests and responses

[![Linux Build Status](https://travis-ci.org/WyriHaximus/php-json-psr7.png)](https://travis-ci.org/WyriHaximus/php-json-psr7)
[![Windows Build status](https://ci.appveyor.com/api/projects/status/1sfdh9g2pvbuw4pp?svg=true)](https://ci.appveyor.com/project/WyriHaximus/php-json-psr7)
[![Latest Stable Version](https://poser.pugx.org/WyriHaximus/json-psr7/v/stable.png)](https://packagist.org/packages/WyriHaximus/json-psr7)
[![Total Downloads](https://poser.pugx.org/WyriHaximus/json-psr7/downloads.png)](https://packagist.org/packages/WyriHaximus/json-psr7)
[![Code Coverage](https://scrutinizer-ci.com/g/WyriHaximus/php-json-psr7/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/WyriHaximus/php-json-psr7/?branch=master)
[![License](https://poser.pugx.org/WyriHaximus/json-psr7/license.png)](https://packagist.org/packages/wyrihaximus/json-psr7)
[![PHP 7 ready](http://php7ready.timesplinter.ch/WyriHaximus/php-json-psr7/badge.svg)](https://travis-ci.org/WyriHaximus/php-json-psr7)

### Installation ###

To install via [Composer](http://getcomposer.org/), use the command below, it will automatically detect the latest version and bind it with `~`.

```
composer require wyrihaximus/json-psr7 
```

# Requirements

All encoding functions assume al streams are fully read and `getContents()` calls will return all contents for that 
stream/body failure will result in missing stream/body parts. 

# Available functions

All `*_decode` functions throw when there are items missing in the array. All functions throw when there is an error 
encoding/decoding to/from JSON.

* `psr7_response_json_encode` - Encodes a PSR-7 Response to a JSON string
* `psr7_response_encode` - Encodes a PSR-7 Response to an array
* `psr7_response_json_decode` - Decodes a JSON string encoded with `psr7_response_json_encode` back into a PSR-7 Response
* `psr7_response_decode` - Decodes an array encoded with `psr7_response_encode` back into a PSR-7 Response
* `psr7_request_json_encode` - Encodes a PSR-7 Request to a JSON string
* `psr7_request_encode` - Encodes a PSR-7 Request to an array
* `psr7_request_json_decode` - Decodes a JSON string encoded with `psr7_request_json_encode` back into a PSR-7 Request
* `psr7_request_decode` - Decodes an array encoded with `psr7_request_encode` back into a PSR-7 Request
* `psr7_uploaded_file_json_encode` - Encodes a PSR-7 Uploaded File to a JSON string
* `psr7_uploaded_file_encode` - Encodes a PSR-7 Uploaded File to an array
* `psr7_uploaded_file_json_decode` - Decodes a JSON string encoded with `psr7_uploaded_file_json_encode` back into a PSR-7 Uploaded File
* `psr7_uploaded_file_decode` - Decodes an array encoded with `psr7_uploaded_file_encode` back into a PSR-7 Uploaded File
* `psr7_server_request_json_encode` - Encodes a PSR-7 Server Request to a JSON string
* `psr7_server_request_encode` - Encodes a PSR-7 Server Request to an array
* `psr7_server_request_json_decode` - Decodes a JSON string encoded with `psr7_server_request_json_encode` back into a PSR-7 Server Request
* `psr7_server_request_decode` - Decodes an array encoded with `psr7_server_request_encode` back into a PSR-7 Server Request

## Contributing ##

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License ##

Copyright 2018 [Cees-Jan Kiewiet](http://wyrihaximus.net/)

Permission is hereby granted, free of charge, to any person
obtaining a copy of this software and associated documentation
files (the "Software"), to deal in the Software without
restriction, including without limitation the rights to use,
copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the
Software is furnished to do so, subject to the following
conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
OTHER DEALINGS IN THE SOFTWARE.
