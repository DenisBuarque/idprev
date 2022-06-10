<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Client;
use App\models\Permission;
use App\Notifications\AdvisorNotification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class FranchiseeController extends Controller
{
    private $user;
    private $permission;
    private $client;

    public function __construct(User $user, Client $client, Permission $permission)
    {
        $this->middleware('auth');
        
        $this->user = $user;
        $this->client = $client;
        $this->permission = $permission;
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

            $query = $this->user;
            
            $columns = ['name','phone','email','address','district','city','state'];
            foreach($columns as $key => $value):
                $query = $query->orWhere($value, 'LIKE', '%'.$search.'%');
            endforeach;

            $users = $query->where('type','F')->orderBy('id','DESC')->get();

        } else {
            $users = $this->user->where('type','F')->orderBy('id', 'DESC')->paginate(10);
        }

        return view('admin.franchisees.index', ['users' => $users, 'search' => $search]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = $this->permission->all();
        return view('admin.franchisees.create',['permissions' => $permissions]);
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

        $data['type'] = 'F';
        $data['password'] =  bcrypt($request->password);

        // salva a imagem de existir
        if($request->hasFile('image') && $request->file('image')->isValid())
        {
            $file = $request->image->store('users','public');
            $data['image'] = $file;
        }

        $record = $this->user->create($data);
        if ($record)
        {
            if(isset($data['permission']) && count($data['permission']))
            {
                foreach($data['permission'] as $key => $value):
                    $record->permissions()->attach($value);
                endforeach;
            }
            return redirect('admin/franchisees')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/franchisee/create')->with('error', 'Erro ao inserido o registro!');
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
        $permissions = $this->permission->all();
        $user = $this->user->find($id);
        if ($user) {
            return view('admin.franchisees.edit', ['user' => $user, 'permissions' => $permissions]);
        } else {
            return redirect('admin/franchisees')->with('alert', 'Não encontramos o registro, tente outra vez!');
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
        $record = $this->user->find($id);

        if(!$data['password']):
            unset($data['password']);
        endif;

        Validator::make($data, [
            'name' => ['required', 'string', 'min:3', Rule::unique('users')->ignore($id)],
            'email' => ['required', 'string', 'email', 'max:100', Rule::unique('users')->ignore($id)],
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

        // atualiza a imagem
        if($request->hasFile('image') && $request->file('image')->isValid())
        {
            if($record['image'] != null){
                if(Storage::exists($record['image'])) {
                    Storage::delete($record['image']);
                }
            }
            
            $new_file = $request->image->store('users','public');
            $data['image'] = $new_file;
        }

        if ($record->update($data)) :

            $permissions = $record->permissions;
            if(count($permissions)){
                foreach($permissions as $key => $value):
                    $record->permissions()->detach($value->id);
                endforeach;
            }

            if(isset($data['permission']) && count($data['permission']))
            {
                foreach($data['permission'] as $key => $value):
                    $record->permissions()->attach($value);
                endforeach;
            }
            
            return redirect('admin/franchisees')->with('success', 'Registro alterado com sucesso!');
        else :
            return redirect('admin/franchisees')->with('error', 'Erro ao alterar o registro!');
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
        $data = $this->user->find($id);
        if ($data->delete()) {
            if($data['image'] != null){
                if(Storage::exists($data['image'])){
                    Storage::delete($data['image']);
                }
            }
            
            return redirect('admin/franchisees')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/franchisees')->with('error', 'Erro ao excluir o registro!');
        }
    }
}
