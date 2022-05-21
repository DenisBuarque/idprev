<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\ModelDoc;

class ModelController extends Controller
{
    private $model;

    public function __construct(ModelDoc $model)
    {
        $this->model = $model;
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
            $query = $this->model;

            $columns = ['title'];
            foreach($columns as $key => $value):
                $query = $query->orWhere($value, 'LIKE', '%'.$search.'%');
            endforeach;

            $models = $query->orderBy('id','DESC')->get();

        } else {
            $models = $this->model->orderBy('id','DESC')->paginate(5);
        }
        
        return view('admin.document.models.index',['models' => $models, 'search' => $search]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $actions = \App\Models\Action::all();
        return view('admin.document.models.create',['actions' => $actions]);
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
            'document' => 'required|max:50000|mimes:pdf,doc,docx',
        ])->validate();

        $data['slug'] = Str::slug($data['title'], '-');

        if($request->hasFile('document') && $request->file('document')->isValid())
        {
            $file = $request->document->store('public/model_docs');
            $data['document'] = $file;
        }

        $document = $this->model->create($data);
        if($document)
        {
            return redirect('admin/document/model/create')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/document/model/create')->with('error', 'Erro ao inserir o registro!');
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

    public function download($id)
    {
        $record = $this->model->find($id);

        if(Storage::exists($record['document'])){
            return Storage::download($record['document']);
        } 

        return redirect('admin/document/models')->with('alert', 'Desculpe! Não encontramos o arquivo!');
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
            return view('admin.document.models.edit',['model' => $model, 'actions' => $actions]);
        } else {
            return redirect('admin/document/models')->with('alert', 'Desculpe! Não encontramos o registro!');
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
            if(Storage::exists($record['document'])){
                Storage::delete($record['document']);
            } 

            $new_file = $request->document->store('public/model_docs');
            $data['document'] = $new_file;
        }

        if($record->update($data))
        {
            return redirect('admin/document/models')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/document/models')->with('error', 'Erro ao alterar o registro!');
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
            if(Storage::exists($data['document'])){
                Storage::delete($data['document']);
            } 
            return redirect('admin/document/models')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/document/models')->with('alert', 'Erro ao excluir o registro!');
        }
    }
}
