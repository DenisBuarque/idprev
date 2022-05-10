<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Lawyer;
use App\Models\User;

class LawyerController extends Controller
{
    private $lawyer;
    private $user;

    public function __construct(Lawyer $lawyer, User $user)
    {
        $this->lawyer = $lawyer;
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = "";
        if(isset($request->search))
        {
            $search = $request->search;
            $query = $this->lawyer;

            $columns = ['name','oab'];
            foreach($columns as $key => $value):
                $query = $query->orWhere($value, 'LIKE', '%'.$search.'%');
            endforeach;

            $lawyers = $query->orderBy('id','DESC')->get();

        } else {
            $lawyers = $this->lawyer->orderBy('id','DESC')->paginate(10);
        }
        
        return view('admin.lawyers.index',['lawyers' => $lawyers, 'search' => $search]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = $this->user->all();
        return view('admin.lawyers.create',['users' => $users]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        Validator::make($data, [
            'name' => 'required|string|min:3|max:100',
            'oab' => 'required|string|max:20|min:3',
        ])->validate();

        $lawyer = $this->lawyer->create($data);
        if($lawyer)
        {
            return redirect('admin/lawyer/create')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/lawyer/create')->with('error', 'Erro ao inserir o registro!');
        }
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
        $users = $this->user->all();
        $lawyer = $this->lawyer->find($id);
        if($lawyer){
            return view('admin.lawyers.edit',['lawyer' => $lawyer, 'users' => $users]);
        } else {
            return redirect('admin/lawyers')->with('alert', 'Desculpe! Não encontramos o registro!');
        }
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
        $data = $request->all();
        $record = $this->lawyer->find($id);

        Validator::make($data, [
            'name' => 'required|string|min:3|max:100',
            'oab' => 'required|string|max:20|min:3',
        ])->validate();

        if($record->update($data)){
            return redirect('admin/lawyers')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/lawyers')->with('error', 'Erro ao alterar o registro!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->lawyer->find($id);
        if($data->delete()){
            return redirect('admin/lawyers')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/lawyers')->with('alert', 'Erro ao excluir o registro!');
        }
    }
}
