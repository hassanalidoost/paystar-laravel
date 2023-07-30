##ER Diagram

![paystar](https://github.com/hassanalidoost/paystar/assets/46284743/43681c7b-ca6c-4482-b644-e80b1a3720f6)


## Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/hassanalidoost/paystar.git

2. Navigate to the project directory:
   
   ```bash
   cd paystar

3. Install dependencies:

    ```bash
    composer install

4. Copy the example environment file and configure the necessary details:

    ```bash
    cp .env.example .env
    
5. Generate a new application key:
     ```bash
     php artisan key:generate
6. Create a database and update the .env file with the database details.
7. Run the database migrations:
    ```bash
    php artisan migrate
8. Seed database
    ```bash
    php artisan db:seed
9. Serve the project
    ```bash
    php artisan serve

10. Now you can send requests to http://127.0.0.1:8000/api
