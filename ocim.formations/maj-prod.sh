#!/bin/bash
app/console cache:clear --env=prod
chown -R www-data:www-data  app/cache/ app/logs/
app/console assets:install --env=prod
app/console assetic:dump --env=prod
