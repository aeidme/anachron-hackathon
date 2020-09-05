<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UsersModel;

class UsersController extends Controller
{


    public function createUser(Request $request,  $username,$pssword,$income,$average_savings){

        $users= New UsersModel;

        $users->usersame=$username;
        $users->psswrd=$pssword;
        $users->income=$income;
        $users->average_savings=$average_savings;
    

        $users->save();

        return "Successful";







    }

    public function getusers(){
        
        return UsersModel::all();

      
    }

    public function update_income(Request $request,$user_id, $amount){
        
        return UsersModel::where('user_id',$user_id)->update([
            "current_amount" =>$amount
        ]);

      
    }
}
