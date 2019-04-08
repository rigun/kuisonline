<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizCompetition extends Model
{

    public function option(){
        return $this->hasMany('App\OptionsQuiz','quiz_id','id');
    }
}
