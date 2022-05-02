<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Lead;
use App\Models\Advisor;

class DashboardController extends Controller
{
    private $lead;
    private $advisor;

    public function __construct(Lead $lead, Advisor $advisor)
    {
        $this->lead = $lead;
        $this->advisor = $advisor;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $waiting = $this->lead->where('tag','2')->get();
        $converted_lead = $this->lead->where('tag','3')->get();
        $unconverted_lead = $this->lead->where('tag','4')->get();
        $progress_in_order = $this->lead->where('situation','2')->get();
        $originating_customers = $this->lead->where('situation','3')->get();
        $unfounded_customers = $this->lead->where('situation','4')->get();
        $resources = $this->lead->where('situation','5')->get();

        $advisors = $this->advisor->all();

        $leads = $this->lead->orderBy('id','DESC')->get();
        return view('dashboard',[
            'leads' => $leads, 
            'waiting' => $waiting, 
            'converted_lead' => $converted_lead, 
            'unconverted_lead' => $unconverted_lead, 
            'originating_customers' => $originating_customers,
            'unfounded_customers' => $unfounded_customers,
            'resources' => $resources,
            'advisors' => $advisors
        ]);
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
                    'comments' => $data['comments']
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
