<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserResult;
use App\Role;
use App\QuizCompetition;
use App\OptionsQuiz;
use App\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserResultController extends Controller
{
    public function index(){
        $userController = new UserController();
        if($userController->getRole() != 'admin'){
            return response()->json(['Soal' => '', 'Kesempatan' => 0, 'Nilai' => 0, 'Jawaban' => '[]']);
        }
        $role_id = Role::where('name','admin')->first()->id;
        $allUser = User::where('role_id','!=',$role_id)->get();
        $i = 0;
        foreach($allUser as $us){
            $user = User::find($us->id);
            $result = UserResult::where('user_id',$us->id)->get();
            $value = 0;
            if(sizeof($result) > 0){
                foreach($result as $rq){
                    $value = $value + $rq->value;
                }
            }
            $data[$i]['user'] = $us;
            $data[$i]['nilai'] = $value;
            $i +=1;
        }
        return $data;
    }
    public function startquiz(){
        $userController = new UserController();
        if($userController->getRole() != 'admin'){
            return 'Gagal';
        }
        $user = User::all();
        foreach($user as $us){
            $setUser = User::find($us->id);
            $setUser->status = 3;
            $setUser->save();
        }
        return 'Berhasil';
    }
    public function endquiz(){
        $userController = new UserController();
        if($userController->getRole() != 'admin'){
            return 'Gagal';
        }
        $user = User::all();
        foreach($user as $us){
            $setUser = User::find($us->id);
            $setUser->status = 0;
            $setUser->save();
        }
        return 'Berhasil';
    }
    public function showQuiz($id){
        $userController = new UserController();
        if($userController->getRole() != 'admin'){
            return response()->json(['Soal' => '', 'Kesempatan' => 0, 'Nilai' => 0, 'Jawaban' => '[]']);
        }
        $user = User::find($id);
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
        return response()->json(['Soal' => $q, 'Kesempatan' => $user->status, 'Nilai' => $value, 'Jawaban' => $result, 'Nama' => $user->username]);
    }
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
