<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileController extends Controller
{
    private $file;

    public function __construct(File $file)
    {
        $this->file = $file;
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
            $query = $this->file;

            $columns = ['title'];
            foreach($columns as $key => $value):
                $query = $query->orWhere($value, 'LIKE', '%'.$search.'%');
            endforeach;

            $files = $query->orderBy('id','DESC')->get();

        } else {
            $files = $this->file->orderBy('id','DESC')->paginate(5);
        }
        
        return view('admin.training.files.index',['files' => $files, 'search' => $search]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.training.files.create');
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
            'arquivo' => 'required|max:50000|mimes:pdf,doc,docx',
        ])->validate();

        $data['slug'] = Str::slug($data['title'], '-');

        if($request->hasFile('arquivo') && $request->file('arquivo')->isValid())
        {
            $file = $request->arquivo->store('files');
            $data['arquivo'] = $file;
        }

        $material = $this->file->create($data);
        if($material)
        {
            return redirect('admin/training/files')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/training/file/create')->with('error', 'Erro ao inserir o registro!');
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
        $record = $this->file->find($id);

        if(Storage::exists($record['arquivo'])){
            return Storage::download($record['arquivo']);
        } 

        return redirect('admin/training/files')->with('alert', 'Desculpe! Não encontramos o arquivo!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $file = $this->file->find($id);
        if($file){
            return view('admin.training.files.edit',['file' => $file]);
        } else {
            return redirect('admin/training/files')->with('alert', 'Desculpe! Não encontramos o registro!');
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
        $record = $this->file->find($id);

        Validator::make($data, [
            'title' => 'required|string|min:3|max:100',
            'arquivo' => 'sometimes|required|max:50000|mimes:pdf,doc,docx',
        ])->validate();

        $data['slug'] = Str::slug($data['title'], '-');

        if($request->hasFile('arquivo') && $request->file('arquivo')->isValid())
        {
            if(Storage::exists($record['arquivo'])){
                Storage::delete($record['arquivo']);
                //Storage::download('tutorial.pdf');
            } 

            $new_file = $request->arquivo->store('files');
            $data['arquivo'] = $new_file;
        }

        if($record->update($data))
        {
            return redirect('admin/training/files')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/training/files')->with('error', 'Erro ao alterar o registro!');
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
        $data = $this->file->find($id);
        
        if($data->delete())
        {
            if(Storage::exists($data['arquivo'])){
                Storage::delete($data['arquivo']);
            } 
            return redirect('admin/training/files')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/training/files')->with('alert', 'Erro ao excluir o registro!');
        }
    }

}
