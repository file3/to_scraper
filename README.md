## Sainsbury’s Scraper

## System Requirement

Server running PHP 5.0 or higher with cURL extension and composer installed

## Installation

$ git clone https://github.com/file3/to_scraper.git

$ cd to_scraper

$ composer update

## Running

$ php index.php

## Test

$ vendor/bin/phpunit tests/GetDataTest

## Demo

http://www.humankraft.hu/~fattila/trafficoptimiser/scraper/

## TODO and future consideration

Parse pages with DOMDocument class - the target pages are currently not valid/well-formed for this

Keep more formatting in description field

Handle pager

Add more error handling

Use spl_autoload_register() to load main class

Add more unit tests
