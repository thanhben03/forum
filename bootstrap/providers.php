<?php

use App\Api\Providers\RepositoryServiceProvider;
use App\Api\Providers\ServiceServiceProvider;

return [
    App\Providers\AppServiceProvider::class,
    RepositoryServiceProvider::class,
    ServiceServiceProvider::class,
];
