<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\ClientPhotos;
use App\Models\User;
use App\Models\Action;
use App\models\ModelDoc;
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

    public function __construct(Lead $lead, User $user, Action $action, ModelDoc $model, FeedbackLead $feedback, ClientPhotos $clientPhotos)
    {   
        $this->middleware('auth');
        
        $this->lead = $lead;
        $this->user = $user;
        $this->action = $action;
        $this->model = $model;
        $this->feedback = $feedback;
        $this->clientPhotos = $clientPhotos;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $waiting = $this->lead->where('tag','2')->get()->count();
        $converted_lead = $this->lead->where('tag','3')->get()->count();
        $unconverted_lead = $this->lead->where('tag','4')->get()->count();
        $progress = $this->lead->where('situation','1')->get()->count();
        $awaiting_fulfillment = $this->lead->where('situation','2')->get()->count();
        $procedente = $this->lead->where('situation','3')->get()->count();
        $improcedente = $this->lead->where('situation','4')->get()->count();
        $resources = $this->lead->where('situation','5')->get()->count();
        $models = $this->model->all();

        $type_user = auth()->user()->type;

        $search = "";
        if(isset($request->search))
        {
            $search = $request->search;
            $query = $this->lead;

            $columns = ['name','phone','email','address','district','city','state','process','court','stick','term'];
            foreach($columns as $key => $value):
                $query = $query->orWhere($value, 'LIKE', '%'.$search.'%');
            endforeach;
            if($type_user == "F"){
                $leads = $query->where('user_id',auth()->user()->id)->whereIn('tag',[3])->orderBy('id','DESC')->get();
            } else {
                $leads = $query->whereIn('tag',[3])->orderBy('id','DESC')->get();
            }

        } else {
            if($type_user == "F"){
                $leads = $this->lead->where('user_id',auth()->user()->id)->whereIn('tag',[3])->orderBy('id','DESC')->paginate(10);
            }else {
                $leads = $this->lead->whereIn('tag',[3])->orderBy('id','DESC')->paginate(10);
            }
        }
        
        return view('admin.clients.index',[
            'leads' => $leads, 
            'search' => $search,
            'waiting' => $waiting,
            'converted_lead' => $converted_lead, 
            'unconverted_lead' => $unconverted_lead,
            'progress' => $progress,
            'awaiting_fulfillment' => $awaiting_fulfillment,
            'procedente' => $procedente,
            'improcedente' => $improcedente,
            'resources' => $resources,
            'models' => $models,
        ]);
    }

    public function tag($tag)
    {
        $waiting = $this->lead->where('tag','2')->get()->count();
        $converted_lead = $this->lead->where('tag','3')->get()->count();
        $unconverted_lead = $this->lead->where('tag','4')->get()->count();
        $progress = $this->lead->where('situation','1')->get()->count();
        $awaiting_fulfillment = $this->lead->where('situation','2')->get()->count();
        $procedente = $this->lead->where('situation','3')->get()->count();
        $improcedente = $this->lead->where('situation','4')->get()->count();
        $resources = $this->lead->where('situation','5')->get()->count();
        $models = $this->model->all();

        $search = "";

        $leads = $this->lead->where('tag',$tag)->orderBy('id','DESC')->get();
        
        return view('admin.clients.tag',[
            'leads' => $leads, 
            'search' => $search,
            'waiting' => $waiting,
            'converted_lead' => $converted_lead, 
            'unconverted_lead' => $unconverted_lead,
            'progress' => $progress,
            'awaiting_fulfillment' => $awaiting_fulfillment,
            'procedente' => $procedente,
            'improcedente' => $improcedente,
            'resources' => $resources,
            'models' => $models,
        ]);
    }

    public function situation($situation)
    {
        $waiting = $this->lead->where('tag','2')->get()->count();
        $converted_lead = $this->lead->where('tag','3')->get()->count();
        $unconverted_lead = $this->lead->where('tag','4')->get()->count();
        $progress = $this->lead->where('situation','1')->get()->count();
        $awaiting_fulfillment = $this->lead->where('situation','2')->get()->count();
        $procedente = $this->lead->where('situation','3')->get()->count();
        $improcedente = $this->lead->where('situation','4')->get()->count();
        $resources = $this->lead->where('situation','5')->get()->count();
        $models = $this->model->all();

        $search = "";

        $leads = $this->lead->where('situation',$situation)->orderBy('id','DESC')->get();
        
        return view('admin.clients.situation',[
            'leads' => $leads, 
            'search' => $search,
            'waiting' => $waiting,
            'converted_lead' => $converted_lead, 
            'unconverted_lead' => $unconverted_lead,
            'progress' => $progress,
            'awaiting_fulfillment' => $awaiting_fulfillment,
            'procedente' => $procedente,
            'improcedente' => $improcedente,
            'resources' => $resources,
            'models' => $models,
        ]);
    }

    public function converted(Request $request)
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

            $leads = $query->whereIn('tag',[3])->orderBy('id','DESC')->get();

        } else {
            $leads = $this->lead->whereIn('tag',[3])->orderBy('id','DESC')->paginate(10);
        }
        
        return view('admin.clients.converted',['leads' => $leads, 'search' => $search]);
    }

    public function unconverted(Request $request)
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

            $leads = $query->whereIn('tag',[4])->orderBy('id','DESC')->get();

        } else {
            $leads = $this->lead->whereIn('tag',[4])->orderBy('id','DESC')->paginate(10);
        }
        
        return view('admin.clients.converted',['leads' => $leads, 'search' => $search]);
    }

    public function term(Request $request)
    {
        $models = $this->model->all();
        $search = "";
        if(isset($request->search))
        {
            $search = $request->search;
            $query = $this->lead;

            $columns = ['name','phone','email','address','district','city','state','process','court','stick','term'];
            foreach($columns as $key => $value):
                $query = $query->orWhere($value, 'LIKE', '%'.$search.'%');
            endforeach;

            $leads = $query->whereIn('situation',[2])->orderBy('id','DESC')->get();

        } else {
            $leads = $this->lead->whereIn('situation',[2])->orderBy('id','DESC')->paginate(10);
        }
        
        return view('admin.clients.term',[
            'leads' => $leads, 
            'search' => $search,
            'models' => $models
        ]);
    }

    public function edit_term($id)
    {
        $lead = $this->lead->find($id);
        if($lead){
            return view('admin.clients.edit_term',['lead' => $lead]);
        } else {
            return redirect('admin/client/term/edit')->with('alert', 'Desculpe! Não encontramos o registro!');
        }
    }

    public function documents($id)
    {
        $documents = $this->modeldoc->where('action_id',$id)->get();
        return view('admin.clients.documents',['documents' => $documents]);
    }

    public function download($id)
    {
        $record = $this->model->find($id);

        if(Storage::exists($record['document'])){
            return Storage::download($record['document']);
        } 

        return redirect('/admin/client/show/'.$id)->with('alert', 'Desculpe! Não encontramos o documento!');
    }

    public function downloaddoc($id,$lead)
    {
        $record = $this->clientPhotos->find($id);

        if(Storage::exists($record['image'])){
            return Storage::download($record['image']);
        } 
        return redirect('/admin/client/show/'.$lead)->with('err', 'Desculpe! Erro ao baixar arquivo!');
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

        if($data['situation'] == 2){
            Validator::make($data, [
                'term' => 'required',
            ])->validate();
        }

        if(isset($data['financial'])):
            $data['financial'] = str_replace(['.', ','], ['', '.'], $data['financial']);
        else:
            $data['financial'] = 0;
        endif;

        if(empty($data['user_id'])){
            $data['user_id'] = null;
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
        $users = $this->user->where('type','F')->get();
        $lead = $this->lead->find($id);
        if($lead){
            return view('admin.clients.edit',[
                'lead' => $lead, 
                'users' => $users, 
                'actions' => $actions]
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

        if($data['situation'] == 2){
            Validator::make($data, [
                'term' => 'required',
            ])->validate();
        }

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

        return redirect()->route('admin.clients.edit',['id' => $lead_id]); 
    }
}
