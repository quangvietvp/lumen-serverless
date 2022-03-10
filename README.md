# Lumen - Serverless
### Git hub repository
https://github.com/quangvietvp/lumen-serverless.git

### Install locally
- Clone source code from repo
- Install all dependencies following https://lumen.laravel.com/docs/9.x#server-requirements
- Run composer update
- Copy .env.example to .env file
  + Change configuration for database
  + Run php artisan migrate to migrate database
  + Create firebase configuration following https://firebase.google.com/docs/admin/setup
  + Add
    - FIREBASE_CREDENTIALS="path_to_json"
      
    - FIREBASE_DATABASE_URL="URL to database"
 - Run php -S localhost:8000 -t public to test
 - Using this for posman : https://www.getpostman.com/collections/378ea6fe99210732463d
### For serverless

- Setup following this : https://bref.sh/docs/frameworks/laravel.html  

