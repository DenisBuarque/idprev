<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Term;
use App\Models\Lead;

class TermController extends Controller
{
    private $term;
    private $lead;

    public function __construct(Term $term, Lead $lead)
    {
        $this->term = $term;
        $this->lead = $lead;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $lead = $this->lead->find($id);
        return view('admin.terms.create',['lead' => $lead]);
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
            'term' => 'required|date',
            'comments' => 'required|string',
        ])->validate();

        /*if($request->has("hour")){
            $data['hour'] = $request->hour;
        } else {
            $data['hour'] = "00:00";
        }*/

        $term = $this->term->create($data);
        if($term){
            return redirect('admin/term/create/'.$request->lead_id)->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/clients')->with('error', 'Erro ao inserir o registro!');
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
        $term = $this->term->find($id);
        if($term){
            return view('admin.terms.edit',['term' => $term]);
        } else {
            return redirect('admin/clients')->with('alert', 'Desculpe! Não encontramos o registro!');
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
        $record = $this->term->find($id);

        Validator::make($data, [
            'term' => 'required|date',
            'comments' => 'required|string',
        ])->validate();

        if($record->update($data)):
            return redirect('admin/clients')->with('success', 'Registro alterado com sucesso!');
        else:
            return redirect('admin/clients')->with('error', 'Erro ao alterar o registro!');
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
        $data = $this->term->find($id);
        if($data->delete()) {
            return redirect('admin/clients')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/clients')->with('error', 'Erro ao excluir o registro!');
        }
    }
}
