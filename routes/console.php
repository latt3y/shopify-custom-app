<?php

use Illuminate\Support\Facades\Artisan;

/* TODO:
*
*  fetch all products and store them in a Database
*
*  Required fields: {
*   title,
*   description,
*   variants,
*   prices,
*   inventory,
*   images
*  }
*
*  Correctly handle pagination and Shopify rate limiting.
* */
Artisan::command('sync-products', function () {
    $this->comment("Starting the sync process");
})->purpose('Sync Shopify products to database');
