<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OptionsQuiz;
class OptionsQuizController extends Controller
{
    public function index(){
        return OptionsQuiz::all();
    }
    public function show($id){
        return OptionsQuiz::where('quiz_id',$id)->get()->makeVisible('status')->toArray();
    }
    public function store(Request $request){
        $this->validateWith([
            'option' => 'required',
            'quiz_id' => 'required',
            'status' => 'required'
        ]);
        if(($oldOpsi = OptionsQuiz::where([['status',1],['quiz_id',$request->quiz_id]])->first()) && $request->status == 1){
            $oldOpsi->status = 0;
            $oldOpsi->save();
        }
        $opsi = new OptionsQuiz();
        $opsi->option = $request->option;
        $opsi->status = $request->status;
        $opsi->quiz_id = $request->quiz_id;
        $opsi->save();

        return 'Berhasil';
    }
    public function update(Request $request, $id){
        $this->validateWith([
            'option' => 'required',
            'status' => 'required'
        ]);
        if($oldOpsi = OptionsQuiz::where([['status',1],['quiz_id',$request->quiz_id],['id','!=',$id]])->first() && $request->status == 1){
            $oldOpsi->status = 0;
            $oldOpsi->save();
        }
        $opsi = OptionsQuiz::find($id);
        $opsi->option = $request->option;
        $opsi->status = $request->status;
        $opsi->save();

        return 'Berhasil';
    }
    
    public function destroy($id){
        $opsi = OptionsQuiz::find($id);
        $opsi->delete();
        return 'Berhasil';
    }

}
