<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Advisor;
use App\Models\Client;
use App\Notifications\AdvisorNotification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class AdvisorController extends Controller
{
    private $advisor;
    private $client;

    public function __construct(Advisor $advisor, Client $client)
    {
        $this->advisor = $advisor;
        $this->client = $client;
        
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = "";
        if (isset($request->search)) {
            $search = $request->search;

            $query = $this->advisor;
            
            $columns = ['name','phone','email','address','district','city','state'];
            foreach($columns as $key => $value):
                $query = $query->orWhere($value, 'LIKE', '%'.$search.'%');
            endforeach;

            $advisors = $query->orderBy('id','DESC')->get();

            //$advisors = $this->advisor->where('name', 'LIKE', '%' . $search . '%')->get();
        } else {
            $advisors = $this->advisor->orderBy('id', 'DESC')->paginate(5);
        }

        return view('admin.advisors.index', ['advisors' => $advisors, 'search' => $search]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //compara duas tabelas e pega a disferença entre elas...
        $advisorClient = \App\Models\AdvisorClient::select('client_id')->get()->toArray();
        $clients = $this->client->whereNotIn('id', $advisorClient)->get();

        return view('admin.advisors.create', ['clients' => $clients]);
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
            'name' => 'required|string|min:3|unique:advisors',
            'email' => 'required|string|email|unique:advisors|max:50',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required|string',
            'address' => 'required|string',
            'number' => 'required|string',
            'district' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string'
        ])->validate();

        $data['password'] =  bcrypt($request->password);

        $create = $this->advisor->create($data);
        if ($create) 
        {
            //$create->notify(new AdvisorNotification);
            
            if(!empty($data['client_id'])){
                $create->clients()->sync($data['client_id']);
            }

            return redirect('admin/advisors')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/advisor/create')->with('error', 'Erro ao inserido o registro!');
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
        $clients = $this->client->all();
        //compara duas tabelas e pega a disferença entre elas...
        $advisorClient = \App\Models\AdvisorClient::select('client_id')->get()->toArray();
        $clientsNotIn = $this->client->whereNotIn('id', $advisorClient)->get();

        $advisor = $this->advisor->find($id);
        if ($advisor) {
            return view('admin.advisors.edit', [
                'advisor' => $advisor, 
                'clients' => $clients, 
                'clientsNotIn' => $clientsNotIn
            ]);
        } else {
            return redirect('admin/advisors')->with('alert', 'Não encontramos o registro, tente outra vez!');
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
        $record = $this->advisor->findOrFail($id);

        if(!$data['password']):
            unset($data['password']);
        endif;

        Validator::make($data, [
            'name' => ['required', 'string', 'min:3', Rule::unique('advisors')->ignore($id)],
            'email' => ['required', 'string', 'email', 'max:100', Rule::unique('advisors')->ignore($id)],
            'password' => 'sometimes|required|string|min:6|confirmed',
            'phone' => 'required|string',
            'address' => 'required|string',
            'number' => 'required|string',
            'district' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string'
        ])->validate();

        if($request->password){
            $data['password'] =  bcrypt($request->password);
        }

        $update = $record->update($data);
        if ($update) :
            if(!empty($data['client_id'])){
                $record->clients()->sync($data['client_id']);
            }
            return redirect('admin/advisors')->with('success', 'Registro alterado com sucesso!');
        else :
            return redirect('admin/advisors')->with('error', 'Erro ao alterar o registro!');
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
        $data = $this->advisor->find($id);
        if ($data->delete()) {
            return redirect('admin/advisors')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/advisors')->with('error', 'Erro ao excluir o registro!');
        }
    }
}
