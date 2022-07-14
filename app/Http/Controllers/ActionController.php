<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Action;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ActionController extends Controller
{
    private $action;

    public function __construct(Action $action)
    {
        $this->middleware('auth');
        
        $this->action = $action;
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
            $query = $this->action;

            $columns = ['name'];
            foreach($columns as $key => $value):
                $query = $query->orWhere($value, 'LIKE', '%'.$search.'%');
            endforeach;

            $actions = $query->orderBy('id','DESC')->get();

        } else {
            $actions = $this->action->orderBy('id','DESC')->paginate(10);
        }
        
        return view('admin.actions.index',['actions' => $actions, 'search' => $search]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.actions.create');
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
            'name' => 'required|string|min:3|max:50',
        ])->validate();

        $action = $this->action->create($data);
        if($action) {
            return redirect('admin/action/create')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/action/create')->with('error', 'Erro ao inserir o registro!');
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
        $action = $this->action->find($id);
        if($action){
            return view('admin.actions.edit',['action' => $action]);
        } else {
            return redirect('admin/actions')->with('alert', 'Desculpe! Não encontramos o registro!');
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
        $record = $this->action->find($id);

        Validator::make($data, [
            'name' => ['required','string','min:3','max:50',Rule::unique('actions')->ignore($id)],
        ])->validate();

        if($record->update($data)){
            return redirect('admin/actions')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/actions')->with('error', 'Erro ao alterar o registro!');
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
        $data = $this->action->find($id);
        if($data->delete()){
            return redirect('admin/actions')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/actions')->with('alert', 'Erro ao excluir o registro!');
        }
    }
}
