<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Administrative;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdministrativeController extends Controller
{
    private $administrative;
    private $user;

    public function __construct(User $user, Administrative $administrative)
    {
        $this->administrative = $administrative;
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type_user = auth()->user()->type;

        $search = "";
        if (isset($request->search)) 
        {
            $search = $request->search;
            $query = $this->administrative;
            
            $columns = ['name','phone','email','address','district','city','state'];
            foreach($columns as $key => $value):
                $query = $query->orWhere($value, 'LIKE', '%'.$search.'%');
            endforeach;
            
            if($type_user == "F"){
                $administratives = $query->where('user_id',auth()->user()->id)->orderBy('id','DESC')->get();
            } else {
                $administratives = $query->orderBy('id','DESC')->get();
            }
            
        } else {
            if($type_user == "F"){
                $administratives = $this->administrative->where('user_id',auth()->user()->id)->orderBy('id', 'DESC')->paginate(10);
            } else {
                $administratives = $this->administrative->orderBy('id', 'DESC')->paginate(10);
            }
        }

        return view('admin.administratives.index', [
            'administratives' => $administratives, 
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       $users = $this->user->where('type','=','F')->get();
        return view('admin.administratives.create',['users' => $users]);
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
            'user_id' => 'required|string',
            'name' => 'required|string|max:50',
            'phone' => 'required|string|max:14',
            'rg' => 'required|string|max:30',
            'cpf' => 'required|string',
            'cep' => 'required|string|max:9',
            'address' => 'required|string|max:255',
            'number' => 'required|string|max:5',
            'district' => 'required|string|max:50',
            'city' => 'required|string|max:50',
            'state' => 'required|string|max:2',
            'inss' => 'required|string|max:30',
            'situation' => 'required|string',
            'benefits' => 'required|string',
            'results' => 'required|string',
            'initial_date' => 'required|string',
            'concessao_date' => 'required|string',
            'fees' => 'required|string',
            'payment' => 'required|string',
        ])->validate();

        $data['fees'] = str_replace(['.', ','], ['', '.'], $request->fees);

        $create = $this->administrative->create($data);
        if($create)
        {
            /*if($request->hasFile('photos')){
                $images = $this->imageUpload($request,'image');
                $create->photos()->createMany($images);
            }*/

            return redirect('admin/administrative')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/administrative')->with('error', 'Erro ao inserir o registro!');
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
        $administrative = $this->administrative->find($id);
        $users = $this->user->where('type','=','F')->get();

        if($administrative){
            return view('admin.administratives.edit',[
                'administrative' => $administrative, 
                'users' => $users,
            ]);
        } else {
            return redirect('admin/administrative')->with('alert', 'Desculpe! Não encontramos o registro!');
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
            'user_id' => 'required|string',
            'name' => 'required|string|max:50',
            'phone' => 'required|string|max:14',
            'rg' => 'required|string|max:30',
            'cpf' => 'required|string',
            'cep' => 'required|string|max:9',
            'address' => 'required|string|max:255',
            'number' => 'required|string|max:5',
            'district' => 'required|string|max:50',
            'city' => 'required|string|max:50',
            'state' => 'required|string|max:2',
            'inss' => 'required|string|max:30',
            'situation' => 'required|string',
            'benefits' => 'required|string',
            'results' => 'required|string',
            'initial_date' => 'required|string',
            'concessao_date' => 'required|string',
            'fees' => 'required|string',
            'payment' => 'required|string',
        ])->validate();

        $data['fees'] = str_replace(['.', ','], ['', '.'], $request->fees);

        $record = $this->administrative->find($id);
        if($record->update($data))
        {
            /*if($request->hasFile('photos'))
            {
                $images = $this->imageUpload($request,'image');
                $record->photos()->createMany($images);
            }*/

            return redirect('admin/administrative')->with('success', 'Registro atualizado com sucesso!');
        } else {
            return redirect('admin/administrative')->with('error', 'Erro ao atuliazar o registro!');
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

    // realiza o upload da imagem do produto
    private function imageUpload(Request $request, $imageColumn)
    {
        $images = $request->file('photos');
        $uploadedImage = [];
        foreach($images as $image){
            $uploadedImage[] = [$imageColumn => $image->store('documents','public')];
        }
        return $uploadedImage;
    }

    // remove a imagem do produto
    public function remove(Request $request)
    {
        $photo = $request->get('photo');

        if(Storage::disk('public')->exists($photo)){
            Storage::disk('public')->delete($photo);
        }

        //$removePhoto = FinancialPhotos::where('image', $photo);
        //$lead_id = $removePhoto->first()->lead_id;

        //$removePhoto->delete();

        //return redirect()->route('admin.leads.edit',['id' => $lead_id]); 
        return;
    }
}
