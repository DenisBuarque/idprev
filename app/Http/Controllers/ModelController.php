<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\ModelDoc;
use App\Models\Action;

class ModelController extends Controller
{
    private $model;
    private $action;

    public function __construct(ModelDoc $model, Action $action)
    {
        $this->middleware('auth');
        
        $this->model = $model;
        $this->action = $action;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //$search = $request->search;

        $query = $this->model->query();

        if(isset($request->search)){
            $query->orWhere('title', 'LIKE', '%' . $request->search . '%');
        }

        if($request->has('action')){
            $query->orWhere('action_id', $request->action);
        }

        $models = $query->orderBy('id','DESC')->paginate(10);

        $actions = $this->action->all();
        
        return view('admin.models.index',['models' => $models, 'actions' => $actions]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $actions = \App\Models\Action::all();
        return view('admin.models.create',['actions' => $actions]);
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
            'title' => 'required|string|min:3|max:100',
            'document' => 'required|mimes:pdf,doc,docx',
        ])->validate();

        $data['slug'] = Str::slug($data['title'], '-');

        if($request->hasFile('document') && $request->file('document')->isValid())
        {
            $file = $request->document->store('model_docs','public');
            $data['document'] = $file;
        }

        $document = $this->model->create($data);
        if($document)
        {
            return redirect('admin/model/create')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/model/create')->with('error', 'Erro ao inserir o registro!');
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

    public function download($slug)
    {
        $record = $this->model->where('slug',$slug)->first();
        if($record){
            if(Storage::exists($record['document'])){
                return Storage::download($record['document']);
            } else {
                return redirect('admin/models')->with('alert', 'Desculpe! Arquivo n??o encontrado.');
            }
        } else {
            return redirect('admin/models')->with('alert', 'Arquivo n??o existe!');
        }
    }
 
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $actions = \App\Models\Action::all();
        $model = $this->model->find($id);
        if($model){
            return view('admin.models.edit',['model' => $model, 'actions' => $actions]);
        } else {
            return redirect('admin/models')->with('alert', 'Desculpe! N??o encontramos o registro!');
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
        $record = $this->model->find($id);

        Validator::make($data, [
            'title' => 'required|string|min:3|max:100',
            'document' => 'sometimes|required|max:50000|mimes:pdf,doc,docx',
        ])->validate();

        $data['slug'] = Str::slug($data['title'], '-');

        if($request->hasFile('document') && $request->file('document')->isValid())
        {
            if(Storage::disk('public')->exists($record['document'])){
                Storage::disk('public')->delete($record['document']);
            } 

            $new_file = $request->document->store('model_docs','public');
            $data['document'] = $new_file;
        }

        if($record->update($data))
        {
            return redirect('admin/models')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/models')->with('error', 'Erro ao alterar o registro!');
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
        $data = $this->model->find($id);
        if($data->delete())
        {
            if(Storage::disk('public')->exists($data['document'])){
                Storage::disk('public')->delete($data['document']);
            }

            return redirect('admin/models')->with('success', 'Registro exclu??do com sucesso!');
        } else {
            return redirect('admin/models')->with('alert', 'Erro ao excluir o registro!');
        }
    }
}
