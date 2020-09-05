<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UsersModel;
use App\DapiInfo;
use Illuminate\Support\Facades\Http;
class DapiInfoController extends Controller
{
    //
    const appSecret = '';
    public function saveToken(Request $request, $userid){

        $dapiInfo = DapiInfo::where('user_id', $userid)->first();

        //Exchange Access Code to Access Token via DAPI
        $response = Http::post('https://api.dapi.co/v1/auth/ExchangeToken', [
            'connectionID' => $request->input('connectionID'),
            'appSecret' => $this->appSecret,
            'accessCode' => $request->input('accessCode')
        ]);
        $token = $response['accessToken'];
        $userSecret = $response['userSecret'];

        //Token entry doesn't exist
        if ($dapiInfo == null) {
            // exists
            $dapiInfo = new DapiInfo();
            $dapiInfo->user_id = $userid;
            $dapiInfo->token = $token;
            $dapiInfo->userSecret = $userSecret;
            $dapiInfo->save();
        }
        else
        {
            //Update Token
            $dapiInfo->token = $token;
            $dapiInfo->save();
        }
        return "Successful";
    }

    public function getAccounts(Request $request, $userid){
        $dapiInfo = DapiInfo::where('user_id', $userid)->first();
        if ($dapiInfo == null){
            return "Unsuccessful";
        }
        else{
            $response = Http::withToken($dapiInfo->token)->post('https://api.dapi.co/v1/data/accounts/get', [
                'appSecret' => $this->appSecret,
                'userSecret' => $dapiInfo->userSecret
            ]);
            return $response->json();
        }
    }

    public function getAccountBalance(Request $request, $userid, $accountID){
        $dapiInfo = DapiInfo::where('user_id', $userid)->first();
        if ($dapiInfo == null){
            return "Unsuccessful";
        }
        else{
            $response = Http::withToken($dapiInfo->token)->post('https://api.dapi.co/v1/data/balance/get', [
                'appSecret' => $this->appSecret,
                'userSecret' => $dapiInfo->userSecret,
                'accountID' => $accountID
            ]);
            return $response->json();
        }
    }

    public function getTransactions(Request $request, $userid, $accountID){
        $dapiInfo = DapiInfo::where('user_id', $userid)->first();
        if ($dapiInfo == null){
            return "Unsuccessful";
        }
        else{
            $response = Http::withToken($dapiInfo->token)->post('https://api.dapi.co/v1/data/transactions/get', [
                'appSecret' => $this->appSecret,
                'userSecret' => $dapiInfo->userSecret,
                'accountID' => $accountID,
                'toDate' => $request->input('toDate'),
                'fromDate' => $request->input('fromDate')
            ]);
            return $response->json();
        }
    }
}
