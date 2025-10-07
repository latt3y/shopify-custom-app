# shopify-custom-app
To be added

## Git clone this repo

#### Don't change the name of the folder when cloning if you want to use the specific envs for the store which is tested

### Install packages
``` composer install ```

### Generate Key
``` php artisan key:generate ```

### Postgres is used:
DB_CONNECTION=pgsql
DB_PORT=5432
DB_DATABASE=product_sync
DB_USERNAME=postgres // adjust it to your needs
DB_PASSWORD=product_sync_password

create database manually: (could be automated ofc)
```
  # in my case
  $ psql -U postgres 
  $ CREATE DATABASE product_sync; # change it if you want
  $ \l
  $ \q
```

### Migrate
``` php artisan migrate ```

### Install Node modules
``` npm install ```

### Local Setup
We're using Valet to test it locally!

In the working dir:
```
  >$ valet park

  >$ valet link

  >$ valet secure
  
  >$ valet restart
```

### ENVIRONMENT VARIABLES
```
/* scopes defined in the cusom app */
SHOPIFY_API_SCOPES=read_products,write_products

/* base url of the app, change it if needed */
SHOPIFY_APP_URL=https://shopify-custom-app.test

SHOPIFY_MYSHOPIFY_DOMAIN=[your domain].myshopify.com

SHOPIFY_APP_NAME=Product-Sync

SHOPIFY_API_SECRET=[SECRET KEY HERE, taken from dev dashboard->settings in partners account]

SHOPIFY_API_KEY=[CLIENT_ID HERE, taken from dev dashboard->settings in partners account]

SHOPIFY_API_VERSION=2025-10

SHOPIFY_API_REDIRECT=/auth/shopify/handle

SHOPIFY_WEBHOOK_TOPICS=product/create,product/update,product/delete

/* base url of the app, change it if needed */
VITE_API_URL=https://shopify-custom-app.test

/* Endpoints that use {api_version} are encoded in the client side */
VITE_API_VERSION=2025-10
```

### Start the server locally
``` composer run dev ```

### If this app needs to be created and be wired with this repo then:
- Create a partners acoount

- Create a custom app (in order to have full control over the OAuth flow), chose custom distribution not public (public apps need shopify review and might take to long)

- give it a name, untick "embed in shopify admin", set scopes "write_products,read_products"

- Since we are using Laravel Valet (follow steps above) get the URL provided when you hit "composer run dev"

- In our case "https://shopify-custom-app.test/" (or if you can host it even better) and set it as the "App URL"

- White list our endpoints "https://{base_url}/auth/shopify/handle" and "https://{base_url}/auth/shopify/redirect" and save it.

- Then in dev dashboard go to setting and grab Client ID and SECRET and add them in your .env (I'll show in a bit)

- Then go back to Partners dashboard -> Apps -> click your app (not dev dashboard) -> go to distribution, add your store and generate install link

- Install via Link

- Go to Admin Main dashboard -> Apps -> should see your app there and click it, but make sure that your local dev is running

### How to Auth
Login to shopify,
Go to apps and click the "product-sync" app or if you create a new one then click yours.

### Artisan Command
After setting up you can run the command to synch all products
```
  php artisan sync-products [shop-name]
```
