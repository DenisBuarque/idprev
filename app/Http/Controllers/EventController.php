<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class EventController extends Controller
{
    private $event;

    public function __construct(Event $event)
    {
        $this->middleware('auth');
        
        $this->event = $event;
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
            $query = $this->event;

            $columns = ['title'];
            foreach($columns as $key => $value):
                $query = $query->orWhere($value, 'LIKE', '%'.$search.'%');
            endforeach;

            $events = $query->orderBy('id','DESC')->get();

        } else {
            $events = $this->event->orderBy('id','DESC')->paginate(5);
        }
        
        return view('admin.training.events.index',['events' => $events, 'search' => $search]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.training.events.create');
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
            'title' => 'required|string|min:3|max:100',
            'date_event' => 'date_format:"Y-m-d"|required',
            'description' => 'required',
            'image' => 'sometimes|required|max:50000|mimes:jpg,jpeg,gif,png',
        ])->validate();

        $data['slug'] = Str::slug($data['title'], '-');

        if($request->hasFile('image') && $request->file('image')->isValid())
        {
            $file = $request->image->store('events','public');
            $data['image'] = $file;
        }

        $event = $this->event->create($data);
        if($event)
        {
            return redirect('admin/training/events')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/training/event/create')->with('error', 'Erro ao inserir o registro!');
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
        $event = $this->event->find($id);
        if($event){
            return view('admin.training.events.edit',['event' => $event]);
        } else {
            return redirect('admin/training/events')->with('alert', 'Desculpe! Não encontramos o registro!');
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
        $record = $this->event->find($id);

        Validator::make($data, [
            'title' => 'required|string|min:3|max:100',
            'date_event' => 'date_format:"Y-m-d"|required',
            'description' => 'required',
        ])->validate();

        $data['slug'] = Str::slug($data['title'], '-');

        if($request->hasFile('image') && $request->file('image')->isValid())
        {
            if(Storage::exists($record['image'])){
                Storage::delete($record['image']);
            } 

            $new_file = $request->image->store('events','public');
            $data['image'] = $new_file;
        }

        if($record->update($data))
        {
            return redirect('admin/training/events')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/training/events')->with('error', 'Erro ao alterar o registro!');
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
        $data = $this->event->find($id);
        if($data->delete())
        {
            if(Storage::exists($data['image'])){
                Storage::delete($data['image']);
            } 

            return redirect('admin/training/events')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/training/events')->with('alert', 'Erro ao excluir o registro!');
        }
    }
}
