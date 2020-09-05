<?php

namespace App\Http\Controllers;

use App\BudgetModel;
use App\UsersModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OpenBankController extends Controller
{
    //
    public function login(Request $request){
        $response = Http::withHeaders([
            'Authorization' => $request->header('Authorization'),
            'Content-Type' => 'application/json'
        ])->post('https://apisandbox.openbankproject.com/my/logins/direct');
        $username = explode('"', $request->header('Authorization'))[1];
        $userInfo = UsersModel::where('username', $username)->first();
        if ($userInfo == null){
            $userInfo = UsersModel::create([
                'username' => $username,
                'token' => $response->json()['token']
            ]);
            return response(([
                'token' => $response->json()['token'],
                'user_id' => $userInfo->user_id
            ]));
        }
        else{
            $userInfo->token = $response->json()['token'];
            $userInfo->save();
            return response(([
                'token' => $response->json()['token'],
                'user_id' => $userInfo->user_id
            ]));
        }
    }

    private function _getTransactionValue($transaction){
        return $transaction['details']['value']['amount'];
    }

    private function _getAccountTransactions($authHeader, $bankID, $accountID){
        $response = Http::withHeaders([
            'Authorization' => $authHeader,
            'Content-Type' => 'application/json'
        ])->get('https://apisandbox.openbankproject.com/obp/v4.0.0/my/banks/'. $bankID. '/accounts/'. $accountID .'/transactions');
        return $response->json();
    }

    private function _getBankAccounts($authHeader, $bankName){
        $response = Http::withHeaders([
            'Authorization' => $authHeader,
            'Content-Type' => 'application/json'
        ])->get('https://apisandbox.openbankproject.com/obp/v4.0.0/banks/'. $bankName.'/accounts');
        return $response->json();
    }

    public function getBankAccounts(Request $request, $bankName){
        $authHeader = $request->header('Authorization');
        return response($this->_getBankAccounts($authHeader, $bankName));
    }

    private function _getBalance($authHeader, $bankName){
        $income = 0;
        $spending = 0;
        $bankAccounts = $this->_getBankAccounts($authHeader, $bankName);
        for ($i=0; $i < count($bankAccounts); $i++) {
            $bankTransactions = $this->_getAccountTransactions($authHeader, $bankAccounts[$i]['bank_id'], $bankAccounts[$i]['id'])['transactions'];
            for ($j=0; $j < count($bankTransactions); $j++) {
                $txValue = intval($this->_getTransactionValue($bankTransactions[$j]));
                if($txValue > 0){
                    $income += $txValue;
                }
                else{
                    $spending += $txValue;
                }
            }
        }
        return (([
            'income' => $income,
            'spending' => $spending
        ]));
    }

    public function getBalance(Request $request, $bankName){
        $authHeader = $request->header('Authorization');
        return response($this->_getBalance($authHeader, $bankName));
    }

    public function createBudget(Request $request, $userID, $bankName){
        $authHeader = $request->header('Authorization');
        $balance = $this->_getBalance($authHeader, $bankName);
        //calculate disposable
        $disposable = intval($balance['income']) + intval($balance['spending']); //+ because spending is negative already
        $budget = BudgetModel::create([
            'user_id' => $userID,
            'food' => 0.25*$disposable,
            'clothes' => 0.10*$disposable,
            'housing' => 0.2*$disposable,
            'transport' => 0.2*$disposable,
            'goals' => 0.25*$disposable
        ]);
        return response($budget);
    }

}
