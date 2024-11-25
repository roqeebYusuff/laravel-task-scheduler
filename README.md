<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Setting Up Redis for Your Laravel Project

## Introduction

Redis is an open-source, in-memory data structure store that can be used as a database, cache, and message broker. It supports data structures such as strings, hashes, lists, sets, sorted sets with range queries, bitmaps, hyperloglogs, geospatial indexes with radius queries, and streams. This guide will show you how to set up Redis for your Laravel project.

## Prerequisites

Before you begin, you will need the following:

-   A Laravel project
-   Composer installed on your local machine
-   Redis installed on your local machine
-   The `predis/predis` package installed in your Laravel project

## Step 1 — Installing the `predis/predis` Package

The `predis/predis` package is a PHP client library for Redis. It provides a flexible and easy-to-use API for accessing Redis data structures. To install the package, run the following command in your Laravel project directory:

```bash
composer require predis/predis
```

## Step 2 — Configuring the Redis Connection

Next, you need to configure the Redis connection in your Laravel project. Open the `.env` file in your project directory and add the following lines:

```bash
REDIS_CLIENT=predis
REDIS_HOST=
REDIS_PASSWORD=
REDIS_PORT=
REDIS_URL=
CACHE_STORE=redis
```

Replace the values with the appropriate Redis connection details. If you are using the default Redis configuration, you can leave the values empty.

## Step 3 — Using Redis in Your Laravel Project

You can now use Redis in your Laravel project. Here is an example of how to use Redis to store and retrieve data:

```php

use Illuminate\Support\Facades\Cache;

// Store data in Redis
Cache::set('name', 'John Doe');

// Retrieve data from Redis
$name = Cache::get('name');

echo $name; // Output: John Doe
```

This example demonstrates how to store and retrieve data using Redis in Laravel. You can use Redis to cache data, store session information, and more.

## Resources

-   [Redis Documentation](https://redis.io/documentation)
-   [Laravel Documentation](https://laravel.com/docs)
