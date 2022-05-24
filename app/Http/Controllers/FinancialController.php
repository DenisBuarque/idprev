<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Lead;
use App\Models\Action;
use App\Models\Financial;
use Illuminate\Support\Facades\Validator;

class FinancialController extends Controller
{
    private $user;
    private $lead;
    private $action;
    private $financial;

    public function __construct(User $user, Lead $lead, Action $action, Financial $financial)
    {
        $this->middleware('auth');

        $this->user = $user;
        $this->lead = $lead;
        $this->action = $action;
        $this->financial = $financial;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $total = $this->financial->sum('value_total');
        $received = $this->financial->where('payment_confirmation','=','S')->sum('payment_amount');
        $unreceived = $this->financial->where('payment_confirmation','=','N')->sum('payment_amount');
        $fees = $this->financial->where('payment_confirmation','=','N')->sum('fees');
        
        $search = "";
        if (isset($request->search)) {
            $search = $request->search;

            $query = $this->lead;
            
            $columns = ['name','phone','email','address','district','city','state'];
            foreach($columns as $key => $value):
                $query = $query->orWhere($value, 'LIKE', '%'.$search.'%');
            endforeach;

            $leads = $query->whereIn('situation',[3])->orderBy('id','DESC')->get();

        } else {
            $leads = $this->lead->whereIn('situation',[3])->orderBy('id', 'DESC')->paginate(10);
        }

        return view('admin.financial.index', [
            'leads' => $leads, 
            'search' => $search, 
            'total' => $total, 
            'received' => $received,
            'unreceived' => $unreceived,
            'fees' => $fees
        ]);
    }

    public function autofindos(Request $request)
    {
        $search = "";
        if (isset($request->search)) {
            $search = $request->search;

            $query = $this->lead;
            
            $columns = ['name','phone','email','address','district','city','state'];
            foreach($columns as $key => $value):
                $query = $query->orWhere($value, 'LIKE', '%'.$search.'%');
            endforeach;

            $leads = $query->whereIn('situation',[3])->orderBy('id','DESC')->get();

        } else {
            $leads = $this->lead->whereIn('situation',[3])->orderBy('id', 'DESC')->paginate(10);
        }

        return view('admin.financial.autofindos', ['leads' => $leads, 'search' => $search]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.financial.create');
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
            'precatory' => 'required|string',
            'receipt_date' => 'required|date',
            'bank' => 'required|string',
            'value_total' => 'required|string',
            'value_client' => 'required|string',
            'fees' => 'required|string',
            'fees_received' => 'required|string',
            'payday' => 'required|date',
            'payment_amount' => 'required|string',
            'payment_bank' => 'required|string',
            'confirmation_date' => 'required|date',
            'people' => 'required|string',
            'contact' => 'required|string',
            'payment_confirmation' => 'required|string'
        ])->validate();

        $data['value_total'] = str_replace(['.', ','], ['', '.'], $request->value_total);
        $data['value_client'] = str_replace(['.', ','], ['', '.'], $request->value_client);
        $data['fees'] = str_replace(['.', ','], ['', '.'], $request->fees);
        $data['payment_amount'] = str_replace(['.', ','], ['', '.'], $request->payment_amount);

        if($this->financial->create($data)){
            return redirect('admin/financial')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/financial')->with('error', 'Erro ao inserir o registro!');
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
        $actions = $this->action->all();
        $lead = $this->lead->find($id);
        $user = $this->lead->user();

        if($lead){
            return view('admin.financial.edit',[
                'lead' => $lead, 
                'user' => $user, 
                'actions' => $actions
            ]);
        } else {
            return redirect('admin/financial')->with('alert', 'Desculpe! Não encontramos o registro!');
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

        Validator::make($data, [
            'precatory' => 'required|string',
            'receipt_date' => 'required|date',
            'bank' => 'required|string',
            'value_total' => 'required|string',
            'value_client' => 'required|string',
            'fees' => 'required|string',
            'fees_received' => 'required|string',
            'payday' => 'required|date',
            'payment_amount' => 'required|string',
            'payment_bank' => 'required|string',
            'confirmation_date' => 'required|date',
            'people' => 'required|string',
            'contact' => 'required|string',
            'payment_confirmation' => 'required|string'
        ])->validate();

        $data['value_total'] = str_replace(['.', ','], ['', '.'], $request->value_total);
        $data['value_client'] = str_replace(['.', ','], ['', '.'], $request->value_client);
        $data['fees'] = str_replace(['.', ','], ['', '.'], $request->fees);
        $data['payment_amount'] = str_replace(['.', ','], ['', '.'], $request->payment_amount);

        $record = $this->financial->find($id);
        if($record->update($data)){
            return redirect('admin/financial')->with('success', 'Registro atualizado com sucesso!');
        } else {
            return redirect('admin/financial')->with('error', 'Erro ao atuliazar o registro!');
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
        //
    }
}
