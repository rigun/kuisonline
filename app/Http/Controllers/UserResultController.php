<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserResult;
use App\QuizCompetition;
use App\OptionsQuiz;
use App\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserResultController extends Controller
{
    public function show(){
        $user = JWTAuth::parseToken()->authenticate(); 
        $quiz = QuizCompetition::orderBy('value','desc')->with('option')->get();
        $result = UserResult::where('user_id',$user->id)->with(['quiz','option'])->get();
        $value = 0;
        if(sizeof($result) > 0){
            foreach($result as $rq){
                $items2[] = $rq->quiz->with('option')->get();
                $value = $value + $rq->value;
            }
            $q = $quiz->diffKeys($items2)->first();
        }else{
            $q = $quiz->first();
        }
        return response()->json(['Soal' => $q, 'Kesempatan' => $user->status, 'Nilai' => $value, 'Jawaban' => $result]);
    }
    public function store(Request $request){
        $this->validateWith([
            'quiz_id' => 'required',
            'option_id' => 'required'
          ]);
            $id = JWTAuth::parseToken()->authenticate()->id; 

          $result = new UserResult();
          $result->user_id = $id;
          $result->quiz_id = $request->quiz_id;
          $result->option_id = $request->option_id;
          $user = User::where('id',$id)->first();
          $quiz = QuizCompetition::find($request->quiz_id);
          $chooseOpsi = OptionsQuiz::where([['id', $request->option_id],['quiz_id',$request->quiz_id]])->first();
          if (($result->status = $chooseOpsi->status) == 0){
              $result->value = 0;
              $user->status = $user->status - 1;
              $user->save();
          } else {
              $result->value = $quiz->value;
          }
          $result->save();
        return 'Berhasil';
    }
}
