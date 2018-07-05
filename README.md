# Yireo CorsHack
The new Magento 2.3 GraphQL system could be used with GraphQL clients
(like Apollo or even Axios) to fetch data from Magento. Most of these
clients use an HTTP request OPTIONS to see if CORS restrictions apply.
This module adds an OPTIONS check to the GraphQL API. Also, this module
adds Cross Origin headers (currently hard-coded to
`http://localhost:3000`).

### Installation
```
composer require yireo-training/magento2-corshack:dev-master
```

### Todo
- Add configuration option for Access-Control-Allow-Origin
- Add configuration option for Access-Control-Allow-Headers
- Add composer dependencies once 2.3 packages are there
- Remove this module because it is perhaps already fixed in Magento
