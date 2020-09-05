<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\GoalAccountsModel;
use App\UsersModel;

class GoalAccountsController extends Controller
{
    public function index(){
        return "index";
    }


    public function GetGoalAccountsById(Request $request, $userid){
      $GoalAccounts=GoalAccountsModel::where("user_id",$userid)->get();
      return $GoalAccounts;
    }

    public function calculateChances($avg_savings, $remaining_period, $remaining_amount,$importance){
        $im_score=0;
        switch ($importance) {
            case "High":
              $im_score=5;
              break;
            case "Medium":
                $im_score=3;
              break;
            case "Low":
                $im_score=1;
              break;
            default:
              $im_score=0;
          }

          $chances_ratio= ($avg_savings*$remaining_period)/$remaining_amount;

          switch ($chances_ratio) {
            case $chances_ratio>1.2:
              $chances_score=5;
              break;
            case $chances_ratio>=1 && $chances_ratio<1.2:
            $chances_score=4;
              break;
              case $chances_ratio>=0.8 && $chances_ratio<1:
                $chances_score=3;
              break;
              case $chances_ratio>=0.5 && $chances_ratio<0.8:
                $chances_score=2;
              break;
              case $chances_ratio>=0.3 && $chances_ratio<0.5:
                $chances_score=1;
              break;
              case $chances_ratio>=0.1 && $chances_ratio<0.3:
                $chances_score=0;
              break;
            default:
              $im_score=0;
          }
          

          return $chances_score+$im_score;





    }

    public function heytest(Request $request){

        return $request->input("id");
    }

    // $userid, $goalname,$target_amount,$currentamount,$deadline,$importance,$chances
    public function createGoal(Request $request, $userid){

        $users= UsersModel::find($userid);
        $goalname= $request->input('goalname');
        $target_amount= $request->input('target_amount');
        $deadline= $request->input('deadline');
        $currentamount= $request->input('current_amount');
        $importance= $request->input('importance');

        $GoalAccount= new GoalAccountsModel;
        $GoalAccount->user_id= $userid;
        $GoalAccount->goal_name= $goalname;
        $GoalAccount->target_amount=$target_amount ;
        $GoalAccount->current_amount= $currentamount;
        $GoalAccount->importance= $importance;
        $GoalAccount->deadline= $deadline;
        $GoalAccount->chances= $this->calculateChances($users->average_savings,3,$target_amount - $currentamount,$importance);

        $GoalAccount->save();

        return "Successful";







    }

    public function addToGoalAccount(Request $request, $goalaccID,$amount){
      $GoalAccount= GoalAccountsModel::find($goalaccID);

    if($GoalAccount) {
    $GoalAccount->current_amount += $amount;
    $GoalAccount->save();
}

    }

    public function Get_sum_of_chances($userid){
      $GoalAccount= GoalAccountsModel::where('user_id',$userid)->get();
      $sum_of_chances=0;
            if(count($GoalAccount)>0){
              foreach($GoalAccount as $goalaccount){
                $sum_of_chances+= $goalaccount->chances;

              }
              return $sum_of_chances;

    }else{
      return 0;
    }

  }

    public function allocateMoney(Request $request, $userid, $amount){
    $sum_of_chances= $this->Get_sum_of_chances(2);
        GoalAccountsModel::where('user_id',2)->update([
          "current_amount" => GoalAccountsModel::raw("`current_amount`+`chances`/".($sum_of_chances* 1/$amount))
      ]);
    }

    public function GoalAccountTransfer(Request $request, $goalaccountid1,$goalaccountid2,$amount){
      $GoalAccount1=GoalAccountsModel::find($goalaccountid1);
      if($GoalAccount1->current_amount> $amount){
        GoalAccountsModel::where('goal_acc_id',$goalaccountid1)->update(["current_amount"
        =>GoalAccountsModel::raw("`current_amount`-".($amount))]);
        GoalAccountsModel::where('goal_acc_id',$goalaccountid2)->update(["current_amount"
        =>GoalAccountsModel::raw("`current_amount`+".($amount))]);

      }else{
        return "CANT MAKE THIS TRANSACTION";
      }

    }



}
