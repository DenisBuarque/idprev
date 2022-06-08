<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Advisor;
use App\Models\FeedbackTicket;
use App\Models\Ticket;
use Carbon\Carbon;

class TicketController extends Controller
{
    private $ticket;
    private $advisor;
    private $feedback;

    public function __construct(Ticket $ticket, Advisor $advisor, FeedbackTicket $feedback)
    {
        $this->middleware('auth');
        
        $this->ticket = $ticket;
        $this->advisor = $advisor;
        $this->feedback = $feedback;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type_user = auth()->user()->type;
        if($type_user == 'F') {
            $ticket_total = $this->ticket->where('user_id',auth()->user()->id)->get()->count();
            $open = $this->ticket->where('user_id',auth()->user()->id)->where('status','1')->get()->count();
            $resolved = $this->ticket->where('user_id',auth()->user()->id)->where('status','2')->get()->count();
            $pending = $this->ticket->where('user_id',auth()->user()->id)->where('status','3')->get()->count();
        } else {
            $ticket_total = $this->ticket->all()->count();
            $open = $this->ticket->where('status','1')->get()->count();
            $resolved = $this->ticket->where('status','2')->get()->count();
            $pending = $this->ticket->where('status','3')->get()->count();
        }
        
        $search = "";
        if (isset($request->search)) 
        {
            $search = $request->search;
            if($type_user == 'F'){
                $tickets = $this->ticket->where('user_id',auth()->user()->id)->where('status', $search)->orderBy('id','DESC')->get();
            } else {
                $tickets = $this->ticket->orWhere('status', $search)->orderBy('id','DESC')->get();
            }

        } else {
            if($type_user == 'F'){
                $tickets = $this->ticket->where('user_id',auth()->user()->id)->whereIn('status',[1,3])->orderBy('id', 'DESC')->paginate(10);
            } else {
                $tickets = $this->ticket->whereIn('status',[1,3])->orderBy('id', 'DESC')->paginate(10);
            }
        }

        return view('admin.tickets.index', [
            'tickets' => $tickets, 
            'search' => $search,
            'ticket_total' => $ticket_total,
            'open' => $open,
            'resolved' => $resolved,
            'pending' => $pending,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tickets.create');
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
            'description' => 'required|string|min:10',
        ])->validate();

        $data['user_id'] = auth()->user()->id;
        $data['code'] = Carbon::now()->timestamp; //timestamp em números
        $data['status'] = 1; // aberto

        $create = $this->ticket->create($data);
        if ($create) {

            if(isset($data['description'])){
                $create->feedbackTickets()->create([
                    'description' => $data['description'],
                    'user_id' => auth()->user()->id
                ]);
            }

            return redirect('admin/tickets')->with('success', 'Seu ticket foi enviado, aguardo resposta!');
        } else {
            return redirect('admin/tickets/create')->with('error', 'Erro ao inserido o ticket!');
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

    public function response($id)
    {
        $users = \App\Models\User::all();
        $ticket = $this->ticket->find($id);
        $feedbacks = $this->feedback->orderBy('id','DESC')->where('ticket_id','=',$id)->get();
        return view('admin.tickets.response', ['ticket' => $ticket, 'users' => $users, 'feedbacks' => $feedbacks]);
    }

    public function feedback(Request $request)
    {
        $data = $request->all();
        Validator::make($data, [
            'description' => 'required|string|min:10',
        ])->validate();

        $data['user_id'] = auth()->user()->id;

        $create = $this->feedback->create($data);
        if ($create) {
            // atualiza o estado do ticket para pendente
            $pendente = $this->ticket->find($data['ticket_id']);
            $status['status'] = 3;
            $pendente->update($status);

            //return redirect('admin/ticket/response/'.$data['ticket_id'])->with('success', 'Seu ticket foi enviado, aguardo sua resposta!');;
            return redirect('admin/tickets')->with('success', 'Seu ticket foi enviado, aguardo sua resposta!');
        } else {
            return redirect('admin/tickets')->with('error', 'Erro ao inserido o ticket!');
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
        $ticket = $this->ticket->find($id);
        if($ticket){
            return view('admin.tickets.edit',['ticket' => $ticket]);
        } else {
            return redirect('admin/tickets')->with('alert', 'Erro, não encontrado o ticket que procura!');
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
        $record = $this->ticket->findOrFail($id);

        if($record->update($data)):
            return redirect('admin/tickets')->with('success', 'Registro alterado com sucesso!');
        else:
            return redirect('admin/tickets')->with('error', 'Erro ao alterar o registro!');
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
        $data = $this->ticket->find($id);
        if($data->delete()) {
            return redirect('admin/tickets')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/tickets')->with('error', 'Erro ao excluir o registro!');
        }
    }
}
