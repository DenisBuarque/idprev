<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Process;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Client;
use Barryvdh\DomPDF\Facade as PDF;

class ProcessController extends Controller
{
    private $process;
    private $client;

    public function __construct(Process $process, Client $client)
    {
        $this->process = $process;
        $this->client = $client;
    }

    public function pdf($id)
     {
        $process = $this->process->findOrFail($id);
        if($process){
            return PDF::loadView('admin.processes.pdf',['process' => $process])->stream();
        } else {
            return redirect('admin.processes.index')->with('error','Contrato não encontrado!');
        }
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
            $processes = $this->process->where('title','LIKE','%'.$search.'%')->get();
        } else {
            $processes = $this->process->orderBy('id','DESC')->paginate(7);
        }
        
        return view('admin.processes.index',['processes' => $processes, 'search' => $search]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = $this->client->orderBy('name')->get();
        return view('admin.processes.create',['clients' => $clients]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function formatMoney($value)
    {
        return str_replace(['.', ','], ['', '.'], $value);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        Validator::make($data, [
            'folder' => 'required|string',
            'client_id' => 'required|integer',
            'title' => 'required|string|max:250|unique:processes',
            'tag' => 'required|integer',
            'instance' => 'required|integer',
            'number_process' => 'required|string',
            'juizo' => 'required|string',
            'vara' => 'required|string',
            'foro' => 'required|string',
            'action' => 'required|string',
            'days' => 'required|integer',
            'description' => 'required|string',
            'valor_causa' => 'required',
            'data' => 'required|date',
            'valor_condenacao' => 'required',
            'detail' => 'required|string'
        ])->validate();

        $data['valor_causa'] = $this->formatMoney($data['valor_causa']);
        $data['valor_condenacao'] = $this->formatMoney($data['valor_condenacao']);

        if($this->process->create($data)){
            return redirect('admin/processes')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/process/create')->with('error', 'Erro ao inserido o registro!');
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
        $clients = $this->client->orderBy('name')->get();
        $process = $this->process->find($id);
        if($process){
            return view('admin.processes.edit',['process' => $process, 'clients' => $clients]);
        } else {
            return redirect('admin/processes')->with('alert', 'Não encontramos o registro, tente outra vez!');
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
        $record = $this->process->find($id);

        Validator::make($data, [
            'folder' => 'required|string',
            'client_id' => 'required|integer',
            'title' => ['required','string','max:250',Rule::unique('processes')->ignore($id)],
            'tag' => 'required|integer',
            'instance' => 'required|integer',
            'number_process' => 'required|string',
            'juizo' => 'required|string',
            'vara' => 'required|string',
            'foro' => 'required|string',
            'action' => 'required|string',
            'days' => 'required|integer',
            'description' => 'required|string',
            'valor_causa' => 'required',
            'data' => 'required|date',
            'valor_condenacao' => 'required',
            'detail' => 'required|string'
        ])->validate();

        $data['valor_causa'] = $this->formatMoney($data['valor_causa']);
        $data['valor_condenacao'] = $this->formatMoney($data['valor_condenacao']);

        if($record->update($data)):
            return redirect('admin/processes')->with('success', 'Registro alterado com sucesso!');
        else:
            return redirect('admin/processes')->with('error', 'Erro ao alterar o registro!');
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
        $data = $this->process->find($id);
        if($data->delete()){
            return redirect('admin/processes')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/processes')->with('error', 'Erro ao excluir o registro!');
        }
    }
}
