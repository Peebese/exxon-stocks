# Lumen PHP Framework

Author: Philip Brown \
Email: sir.philip.brown@gmail.com

PHP 7.1 is required 
You need to run composer install

PHPUnit is required to run tests

API endpoint:
# Get Closing Stock Price Report of a given date range
  /api/v1/closing-prices-report/{from-date}/{to-date}/{currency-code}
  
# Get Closing Stock Prices of a given date range
  /api/v1/closing-prices/{from-date}/{to-date}/{currency-code}
  
 # Example
  http://localhost:8000/api/v1/closing-prices/2000-03-03/2000-04-09/usd
