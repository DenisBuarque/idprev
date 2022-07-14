<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Lead;
use App\Models\Action;
use App\Models\Financial;
use App\Models\FinancialPhotos;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FinancialController extends Controller
{
    private $user;
    private $lead;
    private $action;
    private $financial;

    public function __construct(User $user, Lead $lead, Action $action, Financial $financial)
    {
        $this->middleware('auth');

        $this->user = $user;
        $this->lead = $lead;
        $this->action = $action;
        $this->financial = $financial;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type_user = auth()->user()->type;

        if($type_user == "F"){
            $total = $this->financial->where('confirmation','=','N')->where('user_id',auth()->user()->id)->sum('value_total');
            $received = $this->financial->where('user_id',auth()->user()->id)->where('confirmation','=','S')->sum('payment_amount');
            $fees = $this->financial->where('user_id',auth()->user()->id)->where('confirmation','=','S')->where('fees_received','S')->sum('fees');
            $unreceived = $this->financial->where('user_id',auth()->user()->id)->where('confirmation','=','N')->sum('fees');
        } else {
            $total = $this->financial->where('confirmation','=','N')->sum('value_total');
            $received = $this->financial->where('confirmation','=','S')->sum('payment_amount');
            $fees = $this->financial->where('confirmation','=','S')->where('fees_received','S')->sum('fees');
            $unreceived = $this->financial->where('confirmation','=','N')->sum('fees');
        }

        $financials = $this->financial->all();

        $search = "";
        if (isset($request->search)) 
        {
            $search = $request->search;
            $query = $this->lead;
            
            $columns = ['name','phone','email','address','district','city','state'];
            foreach($columns as $key => $value):
                $query = $query->orWhere($value, 'LIKE', '%'.$search.'%');
            endforeach;
            
            if($type_user == "F"){
                $leads = $query->where('user_id',auth()->user()->id)->whereIn('situation',[3])->orderBy('id','DESC')->get();
            } else {
                $leads = $query->whereIn('situation',[3])->orderBy('id','DESC')->get();
            }
            
        } else {
            if($type_user == "F"){
                $leads = $this->lead->where('user_id',auth()->user()->id)->whereIn('situation',[3])->orderBy('id', 'DESC')->paginate(10);
            } else {
                $leads = $this->lead->whereIn('situation',[3])->orderBy('id', 'DESC')->paginate(10);
            }
        }

        return view('admin.financial.index', [
            'leads' => $leads, 
            'search' => $search, 
            'total' => $total, 
            'received' => $received,
            'unreceived' => $unreceived,
            'fees' => $fees,
            'financials' => $financials
        ]);
    }

    public function autofindos(Request $request)
    {
        $type_user = auth()->user()->type;

        if($type_user == "F"){
            $total = $this->financial->where('confirmation','=','N')->where('user_id',auth()->user()->id)->sum('value_total');
            $received = $this->financial->where('user_id',auth()->user()->id)->where('confirmation','=','S')->sum('payment_amount');
            $fees = $this->financial->where('user_id',auth()->user()->id)->where('confirmation','=','S')->where('fees_received','S')->sum('fees');
            $unreceived = $this->financial->where('user_id',auth()->user()->id)->where('confirmation','=','N')->sum('fees');
        } else {
            $total = $this->financial->where('confirmation','=','N')->sum('value_total');
            $received = $this->financial->where('confirmation','=','S')->sum('payment_amount');
            $fees = $this->financial->where('confirmation','=','S')->where('fees_received','S')->sum('fees');
            $unreceived = $this->financial->where('confirmation','=','N')->sum('fees');
        }

        if (isset($request->search)) 
        {
            $search = $request->search;
            $query = $this->lead;
            
            $columns = ['name','phone','email','address','district','city','state'];
            foreach($columns as $key => $value):
                $query = $query->orWhere($value, 'LIKE', '%'.$search.'%');
            endforeach;
            
            if($type_user == "F"){
                $leads = $query->where('user_id',auth()->user()->id)->whereIn('situation',[3])->orderBy('id','DESC')->get();
            } else {
                $leads = $query->whereIn('situation',[3])->orderBy('id','DESC')->get();
            }
            
        } else {

            if($type_user == "F"){
                $financials = $this->financial->where('user_id',auth()->user()->id)->orderBy('id', 'DESC')->paginate(10);
            } else {
                $financials = $this->financial->orderBy('id', 'DESC')->paginate(10);
            }
        }

        return view('admin.financial.autofindos', [
            'financials' => $financials,
            'total' => $total, 
            'received' => $received,
            'unreceived' => $unreceived,
            'fees' => $fees
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $actions = $this->action->all();
        $lead = $this->lead->find($id);
        //$user = $this->lead->user();
        return view('admin.financial.create',[
            'actions' => $actions,
            'lead' => $lead
        ]);
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
            'precatory' => 'required|string',
            'receipt_date' => 'required|date',
            'bank' => 'required|string',
            'value_total' => 'required|string',
            'value_client' => 'required|string',
            'fees' => 'required|string',
            'fees_received' => 'required|string'
            //'photos' => 'sometimes|required|max:50000|mimes:jpg,jpeg,gif,png',
        ])->validate();

        $data['value_total'] = str_replace(['.', ','], ['', '.'], $request->value_total);
        $data['value_client'] = str_replace(['.', ','], ['', '.'], $request->value_client);
        $data['fees'] = str_replace(['.', ','], ['', '.'], $request->fees);

        if(isset($data['payment_amount'])){
            $data['payment_amount'] = str_replace(['.',','], ['','.'], $request->payment_amount);
        } else {
            $data['payment_amount'] = 0;
        }

        $create = $this->financial->create($data);
        if($create)
        {
            if($request->hasFile('photos')){
                $images = $this->imageUpload($request,'image');
                $create->photos()->createMany($images);
            }

            return redirect('admin/financial')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/financial')->with('error', 'Erro ao inserir o registro!');
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
        $lead = $this->lead->find($id);
        $user = $this->lead->user();

        if($lead){
            return view('admin.financial.edit',[
                'lead' => $lead, 
                'user' => $user, 
                'actions' => $actions
            ]);
        } else {
            return redirect('admin/financial')->with('alert', 'Desculpe! Não encontramos o registro!');
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
            'precatory' => 'required|string',
            'receipt_date' => 'required|date',
            'bank' => 'required|string',
            'value_total' => 'required|string',
            'value_client' => 'required|string',
            'fees' => 'required|string',
            'fees_received' => 'required|string'
            //'photos' => 'sometimes|required|mimes:jpg,jpeg,gif,png',
        ])->validate();

        $data['value_total'] = str_replace(['.',','], ['','.'], $request->value_total);
        $data['value_client'] = str_replace(['.',','], ['','.'], $request->value_client);
        $data['fees'] = str_replace(['.',','], ['','.'], $request->fees);
        if(isset($data['payment_amount'])){
            $data['payment_amount'] = str_replace(['.',','], ['','.'], $request->payment_amount);
        } else {
            $data['payment_amount'] = 0;
        }

        $record = $this->financial->find($id);
        if($record->update($data)){

            if($request->hasFile('photos'))
            {
                $images = $this->imageUpload($request,'image');
                $record->photos()->createMany($images);
            }

            if($data['confirmation'] == 'N'){
                return redirect('admin/financial')->with('success', 'Registro atualizado com sucesso!');
            } else {
                return redirect('admin/financial')->with('success', 'Registro atualizado com sucesso!');
            }

        } else {
            return redirect('admin/financial')->with('error', 'Erro ao atuliazar o registro!');
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
        return redirect('admin/financial')->with('alert', 'Por segurança não estamos excluindo esse registro!');
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

        $removePhoto = FinancialPhotos::where('image', $photo);
        $lead_id = $removePhoto->first()->lead_id;

        $removePhoto->delete();

        return redirect()->route('admin.leads.edit',['id' => $lead_id]); 
    }
}
