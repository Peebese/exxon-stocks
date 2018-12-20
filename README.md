# Lumen PHP Framework

Author: Philip Brown \
Email: sir.philip.brown@gmail.com

PHP 7.1 is required 
You need to run composer install

PHPUnit is required to run tests

API endpoint:
 - Get Closing Stock Price Report of a given date range
  /api/v1/closing-prices-report/{from-date}/{to-date}/{currency-code}
  
 - Get Closing Stock Prices of a given date range
  /api/v1/closing-prices/{from-date}/{to-date}/{currency-code}
  
 - Example
  localhost:8000/api/v1/closing-prices/2000-03-03/2000-04-09/usd

# Instructions

Note: You will need a database configuired, this allows the app in import the data from the csv to the database
The database migration and csv import will happen automatically after you run Composer Install. ** You Need to update the `.env` with our database configuration in order for the migration and input to work. 

1) copy .env.example to .env
2) add database config details in .env
3) run `$ Composer Install`


