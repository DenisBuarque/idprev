<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contract;
use Barryvdh\DomPDF\Facade as PDF;

class ContractController extends Controller
{
    private $contract;

    public function __construct(Contract $constract)
    {
        $this->contract = $constract;
    }

    public function pdf($id)
    {
        $contract = $this->contract->findOrFail($id);

        $processes = \App\Models\Process::all();
        $advisors = \App\Models\Advisor::all();

        if($contract){
            return PDF::loadView('admin.contracts.pdf',['contract' => $contract, 'processes' => $processes, 'advisors' => $advisors])->stream();
        } else {
            return redirect('admin.contracts.index')->with('error','Contrato não encontrado!');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search = '';
        $contracts = $this->contract->paginate(7);
        return view('admin.contracts.index',['contracts' => $contracts, 'search' => $search]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $processes = \App\Models\Process::all();
        $advisors = \App\Models\Advisor::all();
        return view('admin.contracts.create',['processes' => $processes, 'advisors' => $advisors]);
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

        $contract = $this->contract->create($data);
        $contract->advisors()->sync($data['advisor_id']);
        return redirect('admin/contracts')->with('success','Registro inserido com sucesso!');
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
        $contract = $this->contract->findOrFail($id);

        $processes = \App\Models\Process::all();
        $advisors = \App\Models\Advisor::all();

        if($contract){
            return view('admin.contracts.edit',['contract' => $contract, 'processes' => $processes, 'advisors' => $advisors]);
        } else {
            return redirect('admin.contracts.index')->with('error','Contrato não encontrado!');
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
        $record = $this->contract->findOrFail($id);
        if($record->update($data)){
            $record->advisors()->sync($data['advisor_id']);
            return redirect('admin/contracts')->with('success','Registro alterado com sucesso!');
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
        $record = $this->contract->find($id);
        $record->delete();

        return redirect('admin/contracts')->with('success','Registro excluído com sucesso!');
    }
}
