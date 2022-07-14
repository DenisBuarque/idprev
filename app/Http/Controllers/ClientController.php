<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\ClientPhotos;
use App\Models\User;
use App\Models\Action;
use App\models\ModelDoc;
use App\Models\lawyer;
use App\Models\Term;
use App\Models\FeedbackLead;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    private $lead;
    private $user;
    private $action;
    private $feedback;
    private $clientPhotos;
    private $model;
    private $lawyer;
    private $term;

    public function __construct(
        Lead $lead, 
        User $user, 
        Action $action, 
        ModelDoc $model, 
        FeedbackLead $feedback, 
        ClientPhotos $clientPhotos, 
        Lawyer $lawyer,
        Term $term
        )
    {   
        $this->middleware('auth');
        
        $this->lead = $lead;
        $this->user = $user;
        $this->action = $action;
        $this->model = $model;
        $this->feedback = $feedback;
        $this->clientPhotos = $clientPhotos;
        $this->lawyer = $lawyer;
        $this->term = $term;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type_user = auth()->user()->type;

        if($type_user == 'F'){
            $waiting = $this->lead->where('user_id',auth()->user()->id)->where('tag','2')->get()->count();
            $converted_lead = $this->lead->where('user_id',auth()->user()->id)->where('tag','3')->get()->count();
            $unconverted_lead = $this->lead->where('user_id',auth()->user()->id)->where('tag','4')->get()->count();
            $progress = $this->lead->where('user_id',auth()->user()->id)->where('situation','1')->where('tag','3')->get()->count();
            $awaiting_fulfillment = $this->lead->where('user_id',auth()->user()->id)->where('situation','2')->where('tag','3')->get()->count();
            $procedente = $this->lead->where('user_id',auth()->user()->id)->where('situation','3')->where('tag','3')->get()->count();
            $improcedente = $this->lead->where('user_id',auth()->user()->id)->where('situation','4')->where('tag','3')->get()->count();
            $resources = $this->lead->where('user_id',auth()->user()->id)->where('situation','5')->where('tag','3')->get()->count();
        } else {
            $waiting = $this->lead->where('tag','2')->get()->count();
            $converted_lead = $this->lead->where('tag','3')->get()->count();
            $unconverted_lead = $this->lead->where('tag','4')->get()->count();
            $progress = $this->lead->where('situation','1')->where('tag','3')->get()->count();
            $awaiting_fulfillment = $this->lead->where('situation','2')->where('tag','3')->get()->count();
            $procedente = $this->lead->where('situation','3')->where('tag','3')->get()->count();
            $improcedente = $this->lead->where('situation','4')->where('tag','3')->get()->count();
            $resources = $this->lead->where('situation','5')->where('tag','3')->get()->count();  
        }

        $models = $this->model->all(); // modelos de docs...
        $franchisees = $this->user->where('type','F')->get();
        $terms = $this->term->all();
        $terms_cumpridos = $this->term->where('tag','=','1')->get();

        // inicia a consulta
        $query = $this->lead->query();

        if ($request->has('franchisee')) {
            $query->orWhere('user_id', '=', $request->franchisee)->whereIn('tag',[3]);
        }

        if ($request->has('situation')) {
            $query->orWhere('situation', '=', $request->situation)->whereIn('tag',[3]);
        }

        if (isset($request->search)) {
            $columns = ['name','phone','email','address','district','city','state'];
            foreach ($columns as $key => $value) {
                $query->orWhere($value, 'LIKE', '%' . $request->search . '%')->whereIn('tag',[3]);
            }
        }

        // Monta a lista de clientes leads
        if($type_user == "F"){
            $leads = $query->where('user_id',auth()->user()->id)->whereIn('tag',[3])->whereIn('situation',[1,2,4,5])->orderBy('id','DESC')->paginate(10);
        }else {
            $leads = $query->whereIn('tag',[3])->whereIn('situation',[1,2,4,5])->orderBy('id','DESC')->paginate(10);
        }
        
        return view('admin.clients.index',[
            'leads' => $leads, 
            'waiting' => $waiting,
            'converted_lead' => $converted_lead, 
            'unconverted_lead' => $unconverted_lead,
            'progress' => $progress,
            'awaiting_fulfillment' => $awaiting_fulfillment,
            'procedente' => $procedente,
            'improcedente' => $improcedente,
            'resources' => $resources,
            'models' => $models,
            'franchisees' => $franchisees,
            'terms' => $terms,
            'terms_cumpridos' => $terms_cumpridos
        ]);
    }

    public function tag($tag)
    {
        $type_user = auth()->user()->type;
        if($type_user == 'F'){
            $waiting = $this->lead->where('user_id',auth()->user()->id)->where('tag','2')->get()->count();
            $converted_lead = $this->lead->where('user_id',auth()->user()->id)->where('tag','3')->get()->count();
            $unconverted_lead = $this->lead->where('user_id',auth()->user()->id)->where('tag','4')->get()->count();
            $progress = $this->lead->where('user_id',auth()->user()->id)->where('situation','1')->get()->count();
            $awaiting_fulfillment = $this->lead->where('user_id',auth()->user()->id)->where('situation','2')->where('tag','3')->get()->count();
            $procedente = $this->lead->where('user_id',auth()->user()->id)->where('situation','3')->where('tag','3')->get()->count();
            $improcedente = $this->lead->where('user_id',auth()->user()->id)->where('situation','4')->where('tag','3')->get()->count();
            $resources = $this->lead->where('user_id',auth()->user()->id)->where('situation','5')->where('tag','3')->get()->count();
            $leads = $this->lead->where('user_id',auth()->user()->id)->where('tag',$tag)->orderBy('id','DESC')->get();
        } else {
            $waiting = $this->lead->where('tag','2')->get()->count();
            $converted_lead = $this->lead->where('tag','3')->get()->count();
            $unconverted_lead = $this->lead->where('tag','4')->get()->count();
            $progress = $this->lead->where('situation','1')->where('tag','3')->get()->count();
            $awaiting_fulfillment = $this->lead->where('situation','2')->where('tag','3')->get()->count();
            $procedente = $this->lead->where('situation','3')->get()->where('tag','3')->count();
            $improcedente = $this->lead->where('situation','4')->where('tag','3')->get()->count();
            $resources = $this->lead->where('situation','5')->where('tag','3')->get()->count();
            $leads = $this->lead->where('tag',$tag)->orderBy('id','DESC')->get();
        }

        $models = $this->model->all();
        $franchisees = $this->user->where('type','F')->get();
        $terms = $this->term->all();
        $terms_cumpridos = $this->term->where('tag','=','1')->get();

        return view('admin.clients.tag',[
            'waiting' => $waiting,
            'converted_lead' => $converted_lead, 
            'unconverted_lead' => $unconverted_lead,
            'progress' => $progress,
            'awaiting_fulfillment' => $awaiting_fulfillment,
            'procedente' => $procedente,
            'improcedente' => $improcedente,
            'resources' => $resources,
            'models' => $models,
            'franchisees' => $franchisees,
            'leads' => $leads,
            'terms' => $terms,
            'terms_cumpridos' => $terms_cumpridos,
            'tag' => $tag
        ]);
    }

    public function documents($id)
    {
        $documents = $this->model->where('action_id',$id)->get();
        return view('admin.clients.documents',['documents' => $documents]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $actions = $this->action->all();
        $users = $this->user->where('type','F')->get();
        return view('admin.clients.create',['users' => $users, 'actions' => $actions]);
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
            'user_id' => 'required',
            'zip_code' => 'required|string|max:9',
            'address' => 'required|string|max:255',
            'number' => 'required|string|max:5',
            'district' => 'required|string|max:50',
            'city' => 'required|string|max:50',
            'state' => 'required|string|max:2',
            'action' => 'required',
        ])->validate();

        if(isset($data['financial'])):
            $data['financial'] = str_replace(['.', ','], ['', '.'], $data['financial']);
        else:
            $data['financial'] = 0;
        endif;

        if(empty($data['user_id'])){
            $data['user_id'] = null;
        }

        if($request->has('confirmed')){
            $data['confirmed'] = true;
        } else {
            $data['confirmed'] = false;
        }

        $lead = $this->lead->create($data);
        if($lead)
        {
            if(isset($data['comments'])){
                $lead->feedbackLeads()->create([
                    'comments' => $data['comments'],
                    'user_id' => $data['user_id']
                ]);
            }
            
            if($request->hasFile('photos')){
                $images = $this->imageUpload($request,'image');
                $lead->photos()->createMany($images);
            }

            // adiciona os advogados se estiver checked.
            if(isset($data['lawyer']) && count($data['lawyer']))
            {
                foreach($data['lawyer'] as $key => $value):
                    $lead->lawyers()->attach($value);
                endforeach;
            }

            return redirect('admin/clients')->with('success', 'Registro inserido com sucesso!');
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
        $users = \App\Models\User::all();
        $actions = $this->action->all();
        $models = $this->model->all();
        $lead = $this->lead->find($id);
        $feedbackLeads = $this->feedback->orderBy('id','DESC')->where('lead_id','=',$id)->get();
        if($lead){
            return view('admin.clients.show',['lead' => $lead, 'users' => $users, 'feedbackLeads' => $feedbackLeads, 'actions' => $actions, 'models' => $models]);
        } else {
            return redirect('admin/clients')->with('alert', 'Desculpe! Não encontramos o registro!');
        }
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
        $models = $this->model->all();
        $users = $this->user->where('type','F')->get();
        $lawyers = $this->lawyer->all();
        $lead = $this->lead->find($id);
        if($lead){
            return view('admin.clients.edit',[
                'lead' => $lead, 
                'users' => $users, 
                'actions' => $actions,
                'models' => $models, 
                'lawyers' => $lawyers
                ]
            );
        } else {
            return redirect('admin/clients')->with('alert', 'Desculpe! Não encontramos o registro!');
        }
    }

    public function feedback(Request $request)
    {
        $data = $request->all();
        Validator::make($data, [
            'comments' => 'required|string|min:10',
        ])->validate();

        $data['user_id'] = auth()->user()->id;

        $create = $this->feedback->create($data);
        if ($create) {
            return redirect('admin/client/show/'.$data['lead_id'])->with('success', 'Comentário adicionado com sucesso!');;
            //return redirect('admin/leads')->with('success', 'Seu ticket foi enviado, aguardo sua resposta!');
        } else {
            return redirect('admin/clients')->with('error', 'Erro ao inserido o ticket!');
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
            'user_id' => 'required',
            'zip_code' => 'required|string|max:9',
            'address' => 'required|string|max:255',
            'number' => 'required|string|max:5',
            'district' => 'required|string|max:50',
            'city' => 'required|string|max:50',
            'state' => 'required|string|max:2',
            'action' => 'required',
        ])->validate();

        if(isset($data['financial'])):
            $data['financial'] = str_replace(['.', ','], ['', '.'], $data['financial']);
        else:
            $data['financial'] = 0;
        endif;

        if(empty($data['user_id'])){
            $data['user_id'] = null;
        }

        if($request->has('confirmed')){
            $data['confirmed'] = true;
        } else {
            $data['confirmed'] = false;
        }

        // atualiza as permissões
        $permissions = $record->lawyers;
        if(count($permissions)){
            foreach($permissions as $key => $value):
                $record->lawyers()->detach($value->id);
            endforeach;
        }

        if(isset($data['lawyer']) && count($data['lawyer']))
        {
            foreach($data['lawyer'] as $key => $value):
                $record->lawyers()->attach($value);
            endforeach;
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

            /*if(isset($data['lawyer']) && count($data['lawyer']))
            {
                foreach($data['lawyer'] as $key => $value):
                    $record->lawyers()->attach($value);
                endforeach;
            }*/
            
            return redirect('admin/clients')->with('success', 'Registro alterado com sucesso!');
        else:
            return redirect('admin/clients')->with('error', 'Erro ao alterar o registro!');
        endif;
    }

    public function update_term(Request $request, $id)
    {
        $data = $request->all();
        $record = $this->lead->find($id);

        Validator::make($data, [
            'responsible' => 'required|string|min:3|max:50',
            'date_fulfilled' => 'required',
            'greeting' => 'required|string|min:10',
        ])->validate();

        if($record->update($data)):
            return redirect('admin/clients/term')->with('success', 'Registro alterado com sucesso!');
        else:
            return redirect('admin/clients/term')->with('error', 'Erro ao alterar o registro!');
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
        $data = $this->lead->find($id);
        $photos = $this->clientPhotos->where('lead_id', $id)->get();
        
        if($data->delete()) {

            foreach($photos as $photo){
                $photo->delete();
                if(Storage::disk('public')->exists($photo->image)){
                    Storage::disk('public')->delete($photo->image);
                }
            }

            return redirect('admin/clients')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/clients')->with('error', 'Erro ao excluir o registro!');
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

    // remove a imagem
    public function remove(Request $request)
    {
        $photo = $request->get('photo');

        if(Storage::disk('public')->exists($photo)){
            Storage::disk('public')->delete($photo);
        }

        $removePhoto = ClientPhotos::where('image', $photo);
        $lead_id = $removePhoto->first()->lead_id;

        $removePhoto->delete();

        return redirect()->route('admin.clients.edit',['id' => $lead_id]); 
    }
}
