#!/usr/bin/env bash

if [ "$APP_ENV" = "development" ]; then
    composer install --working-dir=/app
fi
