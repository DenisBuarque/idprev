<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\ClientPhotos;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
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
            $query = $this->client;

            $columns = ['name','phone','email','address','district','city','state'];
            foreach($columns as $key => $value):
                $query = $query->orWhere($value, 'LIKE', '%'.$search.'%');
            endforeach;

            $clients = $query->orderBy('id','DESC')->get();

            //$clients = $this->findWereLike(['name','phone','email','address','district','city','state'], $search, 'id','DESC');
        } else {
            $clients = $this->client->orderBy('id','DESC')->paginate(5);
        }
        
        return view('admin.clients.index',['clients' => $clients, 'search' => $search]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.clients.create');
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
            'name' => 'required|string|min:3|unique:clients',
            'cpf' => 'required|string|unique:clients',
            'phone' => 'required|string',
            'email' => 'required|string|email|unique:clients|max:50',
            'address' => 'required|string',
            'number' => 'required|string',
            'district' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string'
        ])->validate();

        $client = $this->client->create($data);
        if($client)
        {
            if($request->hasFile('photos')){
                $images = $this->imageUpload($request,'image');
                $client->photos()->createMany($images);
            }

            return redirect('admin/client/create')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/client/create')->with('error', 'Erro ao inserir o registro!');
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
        $client = $this->client->find($id);
        if($client){
            return view('admin.clients.edit',['client' => $client]);
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
        $record = $this->client->find($id);

        Validator::make($data, [
            'name' => ['required','string','min:3',Rule::unique('clients')->ignore($id)],
            'cpf' => ['required','string',Rule::unique('clients')->ignore($id)],
            'phone' => 'required|string',
            'email' => ['required','string','email','max:100',Rule::unique('clients')->ignore($id)],
            'address' => 'required|string',
            'number' => 'required|string',
            'district' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string'
        ])->validate();

        if($record->update($data)):

            if($request->hasFile('photos'))
            {
                $images = $this->imageUpload($request,'image');
                $record->photos()->createMany($images);
            }
            
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
        $data = $this->client->find($id);
        
        if($data->delete())
        {
            foreach($data->photos as $foto)
            {
                $photo = $foto->image;

                if(Storage::disk('public')->exists($photo)){
                    Storage::disk('public')->delete($photo);
                }
        
                $removePhoto = ClientPhotos::where('image', $photo);
                $removePhoto->delete();
            }

            return redirect('admin/clients')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/clients')->with('alert', 'Erro ao excluir o registro!');
        }
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

        $removePhoto = ClientPhotos::where('image', $photo);
        $client_id = $removePhoto->first()->client_id;

        $removePhoto->delete();

        return redirect()->route('admin.clients.edit',['id' => $client_id]); 
    }
}
