<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Todolist;
use App\Models\User;

class TodolistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::all()->pluck('name', 'id');
        return view('todolist.index', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function data()
    {
        if(Auth()->user()->level == 1){
            $pegawai = Todolist::all();
        }else{
            $pegawai = Todolist::where('id_user', Auth()->user()->id);
        }
        return datatables()
            ->of($pegawai)
            ->addIndexColumn()
            ->addColumn('PIC', function ($pegawai) {
                return $pegawai->user->name;
            })
            ->addColumn('uploaded', function ($pegawai) {
                if($pegawai->upload == 0){
                    return '<button type="button" onclick="taskSelesai(`'. route('todolist.edit', $pegawai->id_todolist) .'`)" class="btn btn-xs btn-danger btn-flat">Dilaksanakan</button>';
                
                }else{
                    return '<span class="label label-success"><i class="fa fa-check" aria-hidden="true"></i></span>';
                }
            })
            ->addColumn('finalize', function ($pegawai) {
            if(Auth()->user()->level == 1){
                if($pegawai->upload == 1 && $pegawai->finalize == 0){
                    return '
                    <div>
                        <button type="button" onclick="finalize(`'. route('todolist.update', $pegawai->id_todolist) .'`)"" class="btn btn-xs btn-danger btn-flat">Finalize</button>
                    </div>
                    ';
                }else if ($pegawai->upload == 0 && $pegawai->finalize == 0 ){
                    return '';
                }else{
                    return '<span class="label label-success"><i class="fa fa-check" aria-hidden="true"></i></span>';
                }
            }else{
                if ($pegawai->upload == 1 && $pegawai->finalize == 0 OR $pegawai->upload == 0 && $pegawai->finalize == 0){
                    return '';
                }else{
                    return '<span class="label label-success"><i class="fa fa-check" aria-hidden="true"></i></span>';
                }
            }
            })
            ->addColumn('select_all', function ($pegawai) {
                return '
                    <input type="checkbox" name="id_pegawai[]" value="'. $pegawai->id.'">
                ';
            })
            ->addColumn('aksi', function ($pegawai) {
                if(Auth()->user()->level == 1){
                    return     '
                <div class="btn-group">
                    <button type="button" onclick="deleteData(`'. route('todolist.destroy', $pegawai->id_todolist) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
                }
            })
            ->rawColumns(['aksi', 'uploaded', 'PIC', 'finalize'])
            ->make(true);
    }
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $todolist = new Todolist();
        $todolist->nama_todolist = $request->nama_todolist;
        $todolist->id_user = $request->id_user;
        $todolist->upload = 0;
        $todolist->finalize = 0; 
        $todolist->save();
        
        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tugas = Todolist::find($id);
        $tugas->upload = 1;
        $tugas->update();

        return response()->json('Data berhasil diupdate', 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $tugas = Todolist::find($id);
        $tugas->finalize = 1;
        $tugas->update();

        return response()->json('Data berhasil diupdate', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $todolist = Todolist::find($id);
        $todolist->delete();

        return response(null, 204);
    }
}
