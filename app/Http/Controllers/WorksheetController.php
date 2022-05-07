<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Worksheet;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WorksheetController extends Controller
{
    private $worksheet;

    public function __construct(Worksheet $worksheet)
    {
        $this->worksheet = $worksheet;
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
            $query = $this->worksheet;

            $columns = ['title'];
            foreach($columns as $key => $value):
                $query = $query->orWhere($value, 'LIKE', '%'.$search.'%');
            endforeach;

            $worksheets = $query->orderBy('id','DESC')->get();

        } else {
            $worksheets = $this->worksheet->orderBy('id','DESC')->paginate(5);
        }
        
        return view('admin.worksheets.index',['worksheets' => $worksheets, 'search' => $search]);
    }

    public function download($id)
    {
        $record = $this->worksheet->find($id);

        if(Storage::exists($record['arquivo'])){
            return Storage::download($record['arquivo']);
        } 

        return redirect('admin/worksheets')->with('alert', 'Desculpe! Não encontramos o arquivo!');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.worksheets.create');
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
            'arquivo' => 'required|max:50000|mimes:xls,xlsm,xlsb,xlsx,xlm',
        ])->validate();

        $data['slug'] = Str::slug($data['title'], '-');

        if($request->hasFile('arquivo') && $request->file('arquivo')->isValid())
        {
            $worksheet = $request->arquivo->store('worksheets');
            $data['arquivo'] = $worksheet;
        }

        $material = $this->worksheet->create($data);
        if($material)
        {
            return redirect('admin/worksheets')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/worksheet/create')->with('error', 'Erro ao inserir o registro!');
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
        $worksheet = $this->worksheet->find($id);
        if($worksheet){
            return view('admin.worksheets.edit',['worksheet' => $worksheet]);
        } else {
            return redirect('admin/worksheets')->with('alert', 'Desculpe! Não encontramos o registro!');
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
        $record = $this->worksheet->find($id);

        Validator::make($data, [
            'title' => 'required|string|min:3|max:100',
            'arquivo' => 'sometimes|required|max:50000|mimes:xls,xlsm,xlsb,xlsx,xlm',
        ])->validate();

        $data['slug'] = Str::slug($data['title'], '-');

        if($request->hasFile('arquivo') && $request->file('arquivo')->isValid())
        {
            if(Storage::exists($record['arquivo'])){
                Storage::delete($record['arquivo']);
            } 

            $new_worksheet = $request->arquivo->store('worksheets');
            $data['arquivo'] = $new_worksheet;
        }

        if($record->update($data)){
            return redirect('admin/worksheets')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/worksheets')->with('error', 'Erro ao alterar o registro!');
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
        $data = $this->worksheet->find($id);
        
        if($data->delete())
        {
            if(Storage::exists($data['arquivo'])){
                Storage::delete($data['arquivo']);
            } 
            return redirect('admin/worksheets')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/worksheets')->with('alert', 'Erro ao excluir o registro!');
        }
    }
}
