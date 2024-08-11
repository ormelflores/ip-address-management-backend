

### Clone Repo

```shell
https://github.com/ormelflores/ip-address-management-backend.git
cd ip-address-management-backend
```

### Install composer
```shell
comoposer install
```

### Local env
Copy your .env.example to .env and setup your desired database connection.

### Development Server
You can use any development server locally that is compatible with laravel.

```shell
php artisan key:generate
```
Migrate database files
```shell
php artisan migrate
```
### Account setup
To setup account, run seeder on your local. You can check the <b>UserSeeder.php</b> file if you want to modify the account.

```shell
php artisan db:seed
```
After you set this up, proceed to the front-end setup instructions from <a href="https://github.com/ormelflores/ip-address-management-frontend">IP Management Frontend</a>. Use the account below as a login credentials.
```shell
# Default email
admin@email.com

# Default password
password
```
