After you have cloned the project. There are few commands that need to be executed in your terminal. Before doing that, make sure you have composer installed on your device. If you don't  have composer, you can get it from [here](https://getcomposer.org/) and you also need XAMPP for the Apache and MYSQL services.

## After Cloning the project
1. Open up your terminal on the project that you have clone and execute `composer install`
2. Create a new database in your phpMyAdmin with your own choice of name. Example database name : my-database.
3. Find and rename .env.example file to .env
4. Open the .env file and change DB_DATABASE value to your new database name. Example DB_DATABASE = my-database
5. Open up your terminal on the project that you have cloned and execute `php artisan migrate`
6. Open up your terminal on the project that you have cloned and execute `php artisan db:seed --class=UserSeeder`
7. Open up your terminal on the project that you have cloned and execute `php artisan key:generate`
8. Open up your terminal on the project that you have cloned and execute `php artisan serve`
9. Now you can start testing the API using an application like Postman which I used to build the API. The base URL for local is http://127.0.0.1:8000/api/
10. Open up Postman and set the Headers value for Accept to application/json. If you can't change the value, just uncheck the Accept key and create a new one.
11. For starters you can try GET http://127.0.0.1:8000/api/chat and you will get {"message": "Unauthenticated."}.

After executing `php artisan db:seed --class=UserSeeder`. Your database should contain 3 rows of data in the users table, which you can use to try out the API and all of the account's password is password.


## List of API
1. GET http://127.0.0.1:8000/api/login This API is used to authenticate a user and then return the access token needed to access the chat feature. After you have successfully  logged in and acquire the token. In Postman. Go to the Authorization tab and choose Bearer Token for the Type and then paste the token that you get from successfully logging in. Now you are authorized to access the rest of the API. Here is an example that you can use to login
{
    "email": "john@john.com",
    "password": "password"
}
2. POST http://127.0.0.1:8000/api/chat/ This API is used to send a message to a specific user. There are 2 fields that you need to fill in order to use the API. The fields are message and receiver_id. Here is an example that you can use to send a message from user_id 1 to user_id 2. 
{
    "message":"Hello Filbert",
    "receiver_id" : 2
}    
3. GET http://127.0.0.1:8000/api/chat/2 This API is used to get a conversation from a specific user. You only need to fill the user_id at the end of the endpoint. In this example. I want to get my conversation with the user that has the id of 2.
4. GET http://127.0.0.1:8000/api/chat/ This API is used to get all of your conversation with every user that you have previously interacted with.  This API will display all of the information that is based on the scenario.


## Unit Testing
For unit testing you need to execute `php artisan test`
