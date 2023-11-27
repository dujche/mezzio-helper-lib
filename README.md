# Mezzio Helper Lib

This package provides helper classes and interfaces for Mezzio-based projects.

## Requirements

Designed for use with

- PHP 8.1+
- [Mezzio](https://docs.mezzio.dev/)

## Setup

1. Require the package in your composer.json file:
   ```json
   {
     "repositories": [
       {
         "type": "vcs",
         "url": "https://github.com/dujche/mezzio-helper-lib.git"
       }
     ],
     "require": {
       "dujche/mezzio-helper-lib": "dev-master"
     }
   }
   ```
2. Load this package as a module in the Mezzio application (optional):

   ```php
   // config/config.php

   $aggregator = new ConfigAggregator([
       // ...
       \Dujche\MezzioHelperLib\ConfigProvider::class,
       // ...
   ], $cacheConfig['config_cache_path']);

   // ...
   ```
