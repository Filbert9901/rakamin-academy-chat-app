After you have clone the project. There are few commands that need to executed in your terminal. Before doing that, make sure you have composer installed in your device. If you dont have composer, you can get it from [here](https://getcomposer.org/) and you also need XAMPP for the Apache and MYSQL services.

## After Cloning the project
1. Open up your terminal on the project that you have clone and execute `composer install`
2. Create a new database in your phpMyAdmin with your own choice of name. Example database name : my-database.
3. Find and rename .env.example file to .env
4. Open .env file and change DB_DATABASE value to your new dataase name. Example DB_DATABASE = my-database
5. Open up your terminal on the project that you have clone and execute `php artisan migrate`
6. Open up your terminal on the project that you have clone and execute `php artisan db:seed --class=UserSeeder`
7. Open up your terminal on the project that you have clone and execute `php artisan key:generate`
8. Open up your terminal on the project that you have clone and execute `php artisan serve`
9. Now you can start testing the API using application like Postman which I used to build the API. The base URL for local is http://127.0.0.1:8000/api/

After executing `php artisan db:seed --class=UserSeeder`. Your database should contains 3 rows of data in the users table, which you can use to try out the API and all of the account's password is password.


## List of API
1. GET http://127.0.0.1:8000/api/login This API is used to authenticate a user and then return the access token needed to access the chat feature. After you have succesfully log in and acquire the token. In Postman. Go to the Authorization tab and choose Bearer Token for the Type and then paste the token that you get from successfully logging in. Now you are authorized to access the rest of the API. Here is an example that you can use to login
{
    "email": "john@john.com",
    "password": "password"
}
2. POST http://127.0.0.1:8000/api/chat/ This API is used to send a message to a specific user. There are 2 fields that you need to fill in order to use the API. The fields are message and receiver_id. Here is an example that you can use to send a message from user_id 1 to user_id 2. 
{
    "message":"Hello Filbert",
    "receiver_id" : 2
}    
3. GET http://127.0.0.1:8000/api/chat/2 This API is used to get a conversation from a specific user. You only need to fill the user_id at the end of the endpoint. In this example. I want to get my conversation with user that has the id of 2.
4. GET http://127.0.0.1:8000/api/chat/ This API is used to get all of your conversation with every user that you have previously interacted with.  This API will display all of the information that is based on the scenario.
