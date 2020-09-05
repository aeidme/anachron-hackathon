<?php

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');

 });
Route::post('/creategoalaccount/{userid}','GoalAccountsController@createGoal');
Route::get('/getusers','UsersController@getusers');
Route::post('/update_income/{user_id}/{amount}','UsersController@update_income');
Route::post('/createaccount','GoalAccountsController@heytest');
Route::post('/addtogoalaccount/{goalaccID}/{amount}', 'GoalAccountsController@addToGoalAccount');
Route::post('/allocatemoney/{userid}/{amount}', 'GoalAccountsController@allocateMoney');
Route::post('/goalaccounttransfer/{accountid1}/{accountid2}/{amount}', 'GoalAccountsController@GoalAccountTransfer');
Route::get('/goalaccounts/{userid}', 'GoalAccountsController@GetGoalAccountsById');
Route::post('/login', 'OpenBankController@login');
Route::get('/getBankAccounts/{bankName}', 'OpenBankController@getBankAccounts');
Route::get('/getBalance/{bankName}', 'OpenBankController@getBalance');
Route::get('/{userID}/getBudget/{bankName}', 'OpenBankController@createBudget');
