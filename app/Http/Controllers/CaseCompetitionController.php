<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\CaseClosed;

use App\CaseCompetition;
use Purifier;

class CaseCompetitionController extends Controller
{
    public function index()
    {
        return CaseCompetition::all();
    }
    public function onkasus()
    {
        return CaseCompetition::where('status','1')->first();
    }

    public function store(Request $request){
        $this->validateWith([
            'title' => 'required',
            'body' => 'required',
        ]);
        $item = new CaseCompetition();
        $item->title = $request->title;
        $item->body = Purifier::clean($request->body);
        $item->save();
        return response()->json(['status'=>'1','msg'=>'CaseCompetition '.$item->title.' berhasil dibuat','result' => $item]);
    }
    public function update(Request $request,$id){
        $this->validateWith([
            'title' => 'required',
            'body' => 'required',
        ]);
        if($item = CaseCompetition::where('id',$id)->first()){
            $item->title = $request->title;
            $item->body = Purifier::clean($request->body);
            $item->save();    
            return response()->json(['status'=>'1','msg'=>'CaseCompetition berhasil diubah menjadi '.$item->title,'result' => $item]);
        }
        return response()->json(['status'=>'0','msg'=>'CaseCompetition tidak ditemukan','result' => []]);
    }
    public function updateStatus(Request $request,$id){
        $this->validateWith([
            'status' => 'required',
        ]);
        if($item = CaseCompetition::where('id',$id)->first()){
            $item->status = $request->status;
            $item->save(); 
            return event(new CaseClosed());
            return response()->json(['status'=>'1','msg'=>'Status CaseCompetition berhasil diubah','result' => $item]);
        }
        return response()->json(['status'=>'0','msg'=>'CaseCompetition tidak ditemukan','result' => []]);
    }
    public function show($id){
        if($item = CaseCompetition::where('id',$id)->first()){
            return response()->json(['status'=>'1','msg'=>'CaseCompetition berhasil ditemukan','result' => $item]);
        }
        return response()->json(['status'=>'0','msg'=>'CaseCompetition tidak ditemukan','result' => []]);
    }
    public function destroy($id){
        if($item = CaseCompetition::where('id',$id)->first()){
            $item->delete();
            return response()->json(['status'=>'1','msg'=>'CaseCompetition berhasil dihapus','result' => $item]);
        }
        return response()->json(['status'=>'0','msg'=>'CaseCompetition tidak ditemukan','result' => []]);
    }
}
