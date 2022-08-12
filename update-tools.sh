#!/bin/sh

set -e

echo "Installation de l'outil makeapi"

npm install --global openapi-makeapi

echo "Installation de commitlint"

npm install --global @commitlint/{cli,config-conventional}