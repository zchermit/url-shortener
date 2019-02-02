# Installation instruction
## Updating dependencies
``` 
composer update
```
## Creating database
``` 
php bin/console d:d:c
```
## Creating schema
```
php bin/console d:s:c
```
## Creating index
```
php bin/console d:q:s "ALTER TABLE `url` ADD INDEX( `status`, `url`(100));"
```
