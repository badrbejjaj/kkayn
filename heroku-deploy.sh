set -e

sh makeapi.sh

cd ./frontend

git add .
 
git commit -m 'heroku deploy'

echo "Deploy Frontend"

git push heroku master


cd -


cd ./backend

git add .
 
git commit -m 'heroku deploy'

echo "Deploy Backend"

git push heroku master