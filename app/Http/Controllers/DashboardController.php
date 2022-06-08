<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Lead;
use App\Models\User;
use App\Models\Ticket;
use App\Models\FeedbackLead;
use App\Models\Event;

class DashboardController extends Controller
{
    private $user;
    private $lead;
    private $ticket;
    private $event;

    public function __construct(User $user, Lead $lead, Ticket $ticket, FeedbackLead $feedback, Event $event)
    {
        $this->middleware('auth');
        
        $this->user = $user;
        $this->lead = $lead;
        $this->ticket = $ticket;
        $this->feedback = $feedback;
        $this->event = $event;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $type_user = auth()->user()->type;

        if($type_user == "F"){
            $waiting = $this->lead->where('user_id',auth()->user()->id)->where('tag','2')->get()->count(); // esperando
            $converted_lead = $this->lead->where('user_id',auth()->user()->id)->where('tag','3')->get()->count();
            $unconverted_lead = $this->lead->where('user_id',auth()->user()->id)->where('tag','4')->get()->count();
            $tickets = $this->ticket->where('user_id',auth()->user()->id)->where('status','1')->get()->count();
            $tickets_pendentes = $this->ticket->where('status','3')->get()->count();
            $originating_customers = $this->lead->where('user_id',auth()->user()->id)->where('situation','3')->get()->count(); // clientes precedentes
            $unfounded_customers = $this->lead->where('situation','4')->get()->count(); // clientes improcedentes
            $resources = $this->lead->where('user_id',auth()->user()->id)->where('situation','5')->get()->count(); //recursos
        } else {
            $waiting = $this->lead->where('tag','2')->get()->count(); // esperando
            $converted_lead = $this->lead->where('tag','3')->get()->count();
            $unconverted_lead = $this->lead->where('tag','4')->get()->count();
            $tickets = $this->ticket->where('status','1')->get()->count();
            $tickets_pendentes = $this->ticket->where('status','3')->get()->count();
            $originating_customers = $this->lead->where('situation','3')->get()->count(); // clientes precedentes
            $unfounded_customers = $this->lead->where('situation','4')->get()->count(); // clientes improcedentes
            $resources = $this->lead->where('situation','5')->get()->count(); //recursos
        }

        $users = $this->user->where('type','F')->get();
        $events = $this->event->all();

        if($type_user == "F"){
            $leads = $this->lead->where('user_id',auth()->user()->id)->whereIn('tag', [1])->orderBy('id','DESC')->get();
        } else {
            $leads = $this->lead->whereIn('tag', [1])->orderBy('id','DESC')->get();
        }

        return view('dashboard',[
            'leads'                 => $leads, 
            'waiting'               => $waiting, 
            'converted_lead'        => $converted_lead, 
            'unconverted_lead'      => $unconverted_lead, 
            'tickets'               => $tickets,
            'originating_customers' => $originating_customers,
            'unfounded_customers'   => $unfounded_customers,
            'resources'             => $resources,
            'users'                 => $users,
            'events'                => $events,
            'tickets_pendentes'     => $tickets_pendentes,
        ]);
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
            return redirect('dashboard')->with('success', 'Comentário adicionado com sucesso!');;
            //return redirect('admin/leads')->with('success', 'Seu ticket foi enviado, aguardo sua resposta!');
        } else {
            return redirect('dashboard')->with('error', 'Erro ao inserido o ticket!');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'user_id' => 'required'
        ])->validate();

        if(isset($data['financial'])):
            $data['financial'] = str_replace(['.', ','], ['', '.'], $data['financial']);
        else:
            $data['financial'] = 0;
        endif;

        $data['tag'] = 1;
        $data['situation'] = 1;

        $lead = $this->lead->create($data);
        if($lead)
        {
            if(isset($data['comments'])){
                $lead->feedbackLeads()->create([
                    'comments' => $data['comments'],
                    'user_id' =>  auth()->user()->id
                ]);
            }
            
            return redirect('dashboard')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/leads')->with('error', 'Erro ao inserir o registro!');
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
        //
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
        //
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
