<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Advisor;
use App\Models\Service;
use App\Models\Testimonie;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    private $client;
    private $advisor;
    private $service;
    private $testimonie;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Advisor $advisor, Client $client, Service $service, Testimonie $testimonie)
    {
        //$this->middleware('auth:advisor');
        $this->advisor = $advisor;
        $this->client = $client;
        $this->service = $service;
        $this->testimonie = $testimonie;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $testimonies = $this->testimonie->all();
        $services = $this->service->where('active','yes')->get();
        return view('site',['services' => $services, 'testimonies' => $testimonies]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required','string', 'email', 'max:50'],
            'password' => ['required','string','min:6'],
        ]);

        if (Auth::guard('advisor')->attempt($credentials)) {

            $request->session()->regenerate();
            return redirect()->route('site.franchisee');
        }
 
        return back()->withErrors([
            'error' => 'Suas credenciais são inválidas!',
        ])->onlyInput('error');
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function franchisee(Request $request)
    {

        $franchisee_id = Auth::guard('advisor')->user()->id;

        $advisor = $this->advisor->find($franchisee_id);

        $search = "";
        if (isset($request->search)) {
            $search = $request->search;
            $clients = $advisor->clients()->where('name', 'LIKE', '%' . $search . '%')->get();
        } else {
            $clients = $advisor->clients()->orderBy('id', 'DESC')->get();
        }

        return view('franchisee', ['clients' => $clients, 'search' => $search]);
    }

    public function detail($id)
    {
        $client = $this->client->find($id);
        return view('detail', ['client' => $client]);
    }
}
