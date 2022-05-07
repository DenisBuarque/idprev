<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\ClientPhotos;
use App\Models\User;
use App\Models\Action;
use App\models\ModelDoc;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class LeadController extends Controller
{
    private $lead;
    private $user;
    private $action;

    public function __construct(User $user, Lead $lead, Action $action, ModelDoc $modeldoc)
    {   
        $this->user = $user;
        $this->lead = $lead;
        $this->action = $action;
        $this->modeldoc = $modeldoc;
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
            $query = $this->lead;

            $columns = ['name','phone','email','address','district','city','state','process','court','stick','term'];
            foreach($columns as $key => $value):
                $query = $query->orWhere($value, 'LIKE', '%'.$search.'%');
            endforeach;

            $leads = $query->orderBy('id','DESC')->get();

        } else {
            $leads = $this->lead->orderBy('id','DESC')->paginate(10);
        }
        
        return view('admin.leads.index',['leads' => $leads, 'search' => $search]);
    }

    public function documents($id)
    {
        $documents = $this->modeldoc->where('action_id',$id)->get();
        return view('admin.leads.documents',['documents' => $documents]);
    }

     public function download($id)
    {
        $record = $this->modeldoc->find($id);

        if(Storage::exists($record['document'])){
            return Storage::download($record['document']);
        } 

        return redirect('admin/lead/create')->with('alert', 'Desculpe! Não encontramos o documento!');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $actions = $this->action->all();
        $users = $this->user->all();
        return view('admin.leads.create',['users' => $users, 'actions' => $actions]);
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
            'name' => 'required|string|min:3',
            'phone' => 'required|string',
        ])->validate();

        if(isset($data['financial'])):
            $data['financial'] = str_replace(['.', ','], ['', '.'], $data['financial']);
        else:
            $data['financial'] = 0;
        endif;

        if(empty($data['user_id'])){
            $data['user_id'] = null;
        }

        $data['situation'] = 1;

        $lead = $this->lead->create($data);
        if($lead)
        {
            if(isset($data['comments'])){
                $lead->feedbackLeads()->create([
                    'comments' => $data['comments']
                ]);
            }
            
            if($request->hasFile('photos')){
                $images = $this->imageUpload($request,'image');
                $lead->photos()->createMany($images);
            }

            return redirect('admin/leads')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/lead/create')->with('error', 'Erro ao inserir o registro!');
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
        $users = $this->user->all();
        $lead = $this->lead->find($id);
        if($lead){
            return view('admin.leads.edit',[
                'lead' => $lead, 
                'users' => $users, 
                'actions' => $actions]
            );
        } else {
            return redirect('admin/leads')->with('alert', 'Desculpe! Não encontramos o registro!');
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
        $record = $this->lead->find($id);

        Validator::make($data, [
            'name' => 'required|string|min:3',
            'phone' => 'required|string',
        ])->validate();

        if(isset($data['financial'])):
            $data['financial'] = str_replace(['.', ','], ['', '.'], $data['financial']);
        else:
            $data['financial'] = 0;
        endif;

        if(empty($data['user_id'])){
            $data['user_id'] = null;
        }

        if($record->update($data)):

            if(isset($data['comments'])){
                $record->feedbackLeads()->create([
                    'comments' => $data['comments']
                ]);
            }

            if($request->hasFile('photos'))
            {
                $images = $this->imageUpload($request,'image');
                $record->photos()->createMany($images);
            }
            
            return redirect('admin/leads')->with('success', 'Registro alterado com sucesso!');
        else:
            return redirect('admin/leads')->with('error', 'Erro ao alterar o registro!');
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

        $removePhoto = ClientPhotos::where('image', $photo);
        $lead_id = $removePhoto->first()->lead_id;

        $removePhoto->delete();

        return redirect()->route('admin.leads.edit',['id' => $lead_id]); 
    }
}
