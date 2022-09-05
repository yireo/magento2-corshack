# Yireo CorsHack
The new Magento 2 GraphQL system could be used with GraphQL clients
(like Apollo or even Axios) to fetch data from Magento. Most of these
clients use an HTTP request OPTIONS to see if CORS restrictions apply.
This module adds an OPTIONS check to the GraphQL API. Also, this module
adds Cross Origin headers (currently hard-coded to
`http://localhost:3000`).

### Installation
```
composer require yireo-training/magento2-corshack
./bin/magento module:enable Yireo_CorsHack
```

### Configuration
Navigate to **Advanced > Yireo CorsHack** and add the schema + domain URL to the **Origin Domain** option.

By default, a wildcard (`*`) is configured allowing all origin domains.

Examples of values that can be configured:
- *
- https://yireo.com
- http://localhost
- http://localhost:3000

In general the configuration value includes schema and domain name. It also includes the port number if it is not standard.
