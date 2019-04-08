<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CaseCompetition;
use Purifier;

class CaseCompetitionController extends Controller
{
    public function index(){
        return CaseCompetition::all();
    }
    public function dashboard(){
        return CaseCompetition::where('status',1)->first();
    }
    public function updateStatus($id){
        $userController = new UserController();
        if($userController->getRole() == 'admin'){
            if($case = CaseCompetition::find($id)){
                if($oldCase = CaseCompetition::where('status',1)->first()){
                    $oldCase->status = 0;
                    $oldCase->save();
                }
                $case->status = 1;
                $case->save();
                return 'Sukses';
            }
            return 'Id tidak ditemukan';
        }
        return 'Mohon maaf, anda tidak diijinkan untuk mengaktifkan kasus ini.';
    }
    public function store(Request $request){
        $this->validateWith([
            'title' => 'required',
            'body' => 'required'
        ]);
        $userController = new UserController();
        if($userController->getRole() == 'admin'){
            $case = new CaseCompetition();
            $case->title = $request->title;
            $case->body = Purifier::clean($request->body);
            $case->save();
            return 'Sukses';
        }
        return 'Mohon maaf, anda tidak diijinkan untuk mengelolah kasus ini.';
    }
    public function update(Request $request,$id){
        $this->validateWith([
            'title' => 'required',
            'body' => 'required'
        ]);
        if($userController->getRole() == 'admin'){
            $case = CaseCompetition::find($id);
            $case->title = $request->title;
            $case->body = Purifier::clean($request->body);
            $case->save();
            return 'Sukses';
        }
        return 'Mohon maaf, anda tidak diijinkan untuk mengelolah kasus ini.';
    }
    public function destroy($id){
        if($userController->getRole() == 'admin'){
            $case = CaseCompetition::find($id);
            $case->delete();
            return 'Sukses';
        }
        return 'Mohon maaf, anda tidak diijinkan untuk mengelolah kasus ini.';
    }
}
