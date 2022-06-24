<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todolist;

class DashboardController extends Controller
{
    public function index()
    {
        if(Auth()->user()->level == 1){
            $tugas = Todolist::where('upload', 0)->orwhere('finalize', 0)->count();
            $upload = Todolist::where('upload', 0)->count();
            $finalize = Todolist::where('finalize', 0)->count();

        }else{
            $tugas = Todolist::where(function($query){
                $query->where('upload', '=', 0)
                ->orWhere('finalize', '=', 0);
            })->where('id_user', Auth()->user()->id )->count();
            $upload = Todolist::where('upload', 0)->where('id_user', Auth()->user()->id)->count();
            $finalize = Todolist::where('finalize', 0)->where('id_user', Auth()->user()->id)->count();
        }
        
        return view('admin.dashboard', compact('upload', 'finalize', 'tugas'));
       
        
    }
}
