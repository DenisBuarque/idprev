<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    private $service;

    public function __construct(Service $service)
    {
        $this->middleware('auth');
        
        $this->service = $service;
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
            $services = $this->service->where('title','LIKE','%'.$search.'%')->get();
        } else {
            $services = $this->service->orderBy('id','DESC')->paginate(5);
        }
        
        return view('admin.services.index',['services' => $services, 'search' => $search]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.services.create');
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
            'title' => 'required|string|min:3|max:50',
            'description' => 'required|string',
        ])->validate();

        $service = $this->service->create($data);
        if($service) {
            return redirect('admin/service/create')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/services')->with('error', 'erro ao inserir o registro!');
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
        $service = $this->service->find($id);
        if($service){
            return view('admin.services.edit',['service' => $service]);
        } else {
            return redirect('admin/services')->with('alert', 'Desculpe! o registro não foi encontrado!');
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
        $record = $this->service->findOrFail($id);

        Validator::make($data, [
            'title' => 'required|string|min:3|max:50',
            'description' => 'required|string',
        ])->validate();

        if($record->update($data)):
            return redirect('admin/services')->with('success', 'Registro alterado com sucesso!');
        else:
            return redirect('admin/services')->with('error', 'Erro ao alterar o registro!');
        endif;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->service->find($id);
        if($data->delete()) {
            return redirect('admin/services')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/services')->with('error', 'Erro ao excluir o registro!');
        }
    }
}
