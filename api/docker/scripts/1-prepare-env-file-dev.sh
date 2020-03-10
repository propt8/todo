#!/usr/bin/env bash

if [ "$APP_ENV" = "development" ]; then
    cp -f /app/.env.development /app/.env
fi
