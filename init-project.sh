#!/bin/sh

set -e

sh update-tools.sh


echo "Configuration de l'environnement frontend local"

cd frontend/src/environments/

cp -f environment.local.ts environment.ts

cd -

echo "Configuration de l'environnement backend local"

cd backend/src/

cp -f .env.local .env

cd -

echo "Installation des hooks GIT"

git config core.hooksPath .githooks

echo "Génération des APIs"

makemyapi --sourcesPath src/ -O --skip-validate-spec
