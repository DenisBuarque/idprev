<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Testimonie;

class TestimonieController extends Controller
{
    private $testimonie;

    public function __construct (Testimonie $testimonie)
    {
        $this->middleware('auth');
        $this->testimonie = $testimonie;
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
            $query = $this->testimonie;

            $columns = ['title'];
            foreach($columns as $key => $value):
                $query = $query->orWhere($value, 'LIKE', '%'.$search.'%');
            endforeach;

            $testimonies = $query->orderBy('id','DESC')->get();

        } else {
            $testimonies = $this->testimonie->orderBy('id','DESC')->paginate(10);
        }
        
        return view('admin.testimonies.index',['testimonies' => $testimonies, 'search' => $search]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.testimonies.create');
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
            'description' => 'required',
            'image' => 'sometimes|required|max:50000|mimes:jpg,jpeg,gif,png',
        ])->validate();

        if($request->hasFile('image') && $request->file('image')->isValid())
        {
            $file = $request->image->store('testimonies','public');
            $data['image'] = $file;
        }

        $testimonie = $this->testimonie->create($data);
        if($testimonie)
        {
            return redirect('admin/testimonies')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/testimonie/create')->with('error', 'Erro ao inserir o registro!');
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
        $testimonie = $this->testimonie->find($id);
        if($testimonie){
            return view('admin.testimonies.edit',['testimonie' => $testimonie]);
        } else {
            return redirect('admin/testimonies')->with('alert', 'Desculpe! Não encontramos o registro!');
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
        $record = $this->testimonie->find($id);

        Validator::make($data, [
            'name' => 'required|string|min:3|max:100',
            'description' => 'required',
        ])->validate();

        if($request->hasFile('image') && $request->file('image')->isValid())
        {
            if(Storage::disk('public')->exists($record['image'])){
                Storage::disk('public')->delete($record['image']);
            } 

            $new_file = $request->image->store('testimonies','public');
            $data['image'] = $new_file;
        }

        if($record->update($data))
        {
            return redirect('admin/testimonies')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/testimonies')->with('error', 'Erro ao alterar o registro!');
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
        $data = $this->testimonie->find($id);
        if($data->delete())
        {
            if(Storage::disk('public')->exists($data['image'])){
                Storage::disk('public')->delete($data['image']);
            }

            return redirect('admin/testimonies')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/testimonies')->with('alert', 'Erro ao excluir o registro!');
        }
    }
}
