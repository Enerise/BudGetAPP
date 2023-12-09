<?php

/**
 * Front controller
 *
 * PHP version 7.0
 */

/**
 * Composer
 */
require dirname(__DIR__) . '/vendor/autoload.php';


/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

session_start();
/**
 * Routing
 */
$router = new Core\Router();

// Add the routes
$router->add('api/limit/{category:[\wżźćńółęąśŻŹĆĄŚĘŁÓŃ \%\d\,]+}', ['controller' => 'Expense', 'action' => 'limit']);

$router->add('api/monthlyExpenses/{category:[\wżźćńółęąśŻŹĆĄŚĘŁÓŃ \%\d\,]+}/{date:[\da-f\-]+}', ['controller' => 'Expense', 'action' => 'monthlyExpenses']);

$router->add('api/particularIncomes/{period:[\wżźćńółęąśŻŹĆĄŚĘŁÓŃ ]+}/{datestart:[\da-f\-]+}/{dateend:[\da-f\-]+}', ['controller' => 'Balance', 'action' => 'getParticularIncomes']);

$router->add('api/particularExpenses/{period:[\wżźćńółęąśŻŹĆĄŚĘŁÓŃ ]+}/{datestart:[\da-f\-]+}/{dateend:[\da-f\-]+}', ['controller' => 'Balance', 'action' => 'getParticularExpenses']);

$router->add('api/sumOfAmountIncomes/{period:[\wżźćńółęąśŻŹĆĄŚĘŁÓŃ ]+}/{datestart:[\da-f\-]+}/{dateend:[\da-f\-]+}', ['controller' => 'Balance', 'action' => 'getSumAmountForCategoriesOfIncomes']);

$router->add('api/sumOfAmountExpenses/{period:[\wżźćńółęąśŻŹĆĄŚĘŁÓŃ ]+}/{datestart:[\da-f\-]+}/{dateend:[\da-f\-]+}', ['controller' => 'Balance', 'action' => 'getSumAmountForCategoriesOfExpenses']);

$router->add('api/balance/{period:[\wżźćńółęąśŻŹĆĄŚĘŁÓŃ ]+}/{datestart:[\da-f\-]+}/{dateend:[\da-f\-]+}', ['controller' => 'Balance', 'action' => 'getSumOfBalance']);


$router->add('api/updateParticularExpenses/{period:[\wżźćńółęąśŻŹĆĄŚĘŁÓŃ ]+}/{amount:[\da-f\-\.]+}/{date:[\da-f\-]+}/{payment:[\wżźćńółęąśŻŹĆĄŚĘŁÓŃ \%\d]+}/{category:[\wżźćńółęąśŻŹĆĄŚĘŁÓŃ \%\d\,]+}/{comment:[\wżźćńółęąśŻŹĆĄŚĘŁÓŃ \d\%\,]+}/{expenseid:[\da-f\-]+}/{datestart:[\da-f\-]+}/{dateend:[\da-f\-]+}', ['controller' => 'Balance', 'action' => 'updateParticularExpense']);

$router->add('api/deleteParticularExpenses/{period:[\wżźćńółęąśŻŹĆĄŚĘŁÓŃ ]+}/{expenseid:[\da-f\-]+}/{datestart:[\da-f\-]+}/{dateend:[\da-f\-]+}', ['controller' => 'Balance', 'action' => 'deleteParticularExpense']);

$router->add('api/updateParticularIncomes/{period:[\wżźćńółęąśŻŹĆĄŚĘŁÓŃ ]+}/{amount:[\da-f\-\.]+}/{date:[\da-f\-]+}/{category:[\wżźćńółęąśŻŹĆĄŚĘŁÓŃ \%\d\,]+}/{comment:[\wżźćńółęąśŻŹĆĄŚĘŁÓŃ \%\d\,]+}/{incomeid:[\da-f\-]+}/{datestart:[\da-f\-]+}/{dateend:[\da-f\-]+}', ['controller' => 'Balance', 'action' => 'updateParticularIncome']);

$router->add('api/deleteParticularIncomes/{period:[\wżźćńółęąśŻŹĆĄŚĘŁÓŃ ]+}/{incomeid:[\da-f\-]+}/{datestart:[\da-f\-]+}/{dateend:[\da-f\-]+}', ['controller' => 'Balance', 'action' => 'deleteParticularIncome']);


$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('login', ['controller' => 'Login', 'action' => 'new']);
$router->add('logout', ['controller' => 'Login', 'action' => 'destroy']);
$router->add('index', ['controller' => 'Items', 'action' => 'index']);
$router->add('password/reset/{token:[\da-f]+}', ['controller' => 'Password', 'action' => 'reset']);
$router->add('signup/activate/{token:[\da-f]+}', ['controller' => 'Signup', 'action' => 'activate']);
$router->add('{controller}/{action}');

$router->dispatch($_SERVER['QUERY_STRING']);
