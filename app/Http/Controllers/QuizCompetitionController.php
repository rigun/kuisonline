<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\QuizCompetition;

class QuizCompetitionController extends Controller
{
    public function index(Request $request){
        return QuizCompetition::orderBy('value','desc')->with('option')->get();
    }
    public function store(Request $request){
        $this->validateWith([
            'quiz' => 'required',
            'value' => 'required',
        ]);

        $quiz = new QuizCompetition();
        $quiz->quiz = $request->quiz;
        $quiz->value = $request->value;
        $quiz->save();
        return 'Berhasil';
    }

    public function update(Request $request, $id){
        $this->validateWith([
            'quiz' => 'required',
            'value' => 'required',
        ]);

        $quiz = QuizCompetition::find($id);
        $quiz->quiz = $request->quiz;
        $quiz->value = $request->value;
        $quiz->save();
        return 'Berhasil';
    }
    public function destroy($id){
        $quiz = QuizCompetition::find($id);
        $quiz->delete();
        return 'Berhasil';
    }

}
