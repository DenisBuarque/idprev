<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
   private $user;

   public function __construct(User $user)
   {
       $this->user = $user;
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

            $users = $query->orderBy('id','DESC')->get();

        } else {
            $users = $this->user->orderBy('id', 'DESC')->paginate(10);
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
        return view('admin.users.create');
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
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|unique:users|max:50',
            'password' => 'required|string|min:6|confirmed',
        ])->validate();

        $data['password'] =  bcrypt($request->password);

        if($this->user->create($data)){
            return redirect('admin/users')->with('success', 'Registro inserido com sucesso!');
        }else{
            return redirect('admin/users')->with('error', 'Erro ao inserido o registro!');
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
        $user = $this->user->find($id);
        if($user){
            return view('admin.users.edit',['user' => $user]);
        } else {
            return redirect('admin/users')->with('alert', 'Desculpe! Não foi encontrado o registro que procura!');
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
        $record = $this->user->findOrFail($id);

        if(!$data['password']):
            unset($data['password']);
        endif;

        Validator::make($data, [
            'name' => 'required|string|max:50',
            'email' => ['required','string','email','max:50',Rule::unique('users')->ignore($id)],
            'password' => 'sometimes|required|string|min:6|confirmed',
        ])->validate();

        if($request->password){
            $data['password'] =  bcrypt($request->password);
        }

        if($record->update($data)):
            return redirect('admin/users')->with('success', 'Registro alterado com sucesso!');
        else:
            return redirect('admin/users')->with('alert', 'Erro ao alterar o registro!');
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
        if($data->delete()){
            return redirect('admin/users')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/users')->with('error', 'Erro ao excluir o registro!');
        }
    }
}
