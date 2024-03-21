# ABP Backend Test

## Description

This appication is build for fullfiling the backend test at Amikom Bussiness Park talent recruitment. This application is build in top of Laravel 11. The server specifiations are explained below.

## Server Requirement

In order to use this application, you must meet this requirement:

1. PHP 8.2 or newer
2. Composer for package management
3. SQLite

## Installation

The step of installation are explained below:

1. Clone this repository into your local machine, please fullfil the requirement above.
2. Navigate into folder that you have been cloned.
3. Install all depedency by running command `composer install` on your terminal.
4. Copy the environment files using this command `cp .env.example .env`
5. Generate the application key using artisan command `php artisan key:generate`
6. Serve the application using command `php artisan serve`

## Testing

In order to test the application, you can use Postman or other tools for API testing. For further information about API specification please visit the home page `http://localhost:8000` at your browser.