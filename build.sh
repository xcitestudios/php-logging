#!/bin/bash

git clone https://github.com/xcitestudios/php-logging.git
cd php-logging
composer install
phing docs
rm -rf ../docs
mv docs ../
cd ../
rm -rf php-logging
