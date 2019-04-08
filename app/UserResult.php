<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserResult extends Model
{
    public function quiz(){
        return $this->hasOne('App\QuizCompetition','id','quiz_id');
    }
    public function option(){
        return $this->hasOne('App\OptionsQuiz','id','option_id');
    }
}
