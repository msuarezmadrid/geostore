<?php

namespace App\Http\Controllers\Auth;

use Log;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/items';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }


    /*
        Una vez authenticado el usuario, se realiza la validación de access_token contra la API
        en caso que petición no sea correcta o falle se realiza logout del usuario ya registrado
    */
    protected function authenticated(Request $request, User $user)
    {
        try {
            $client = new Client([
                'timeout'  => 30.0,
            ]);
			
			Log::info('LOG - IN!');
            $apiCrendentials = config('app.api_credentials');
            Log::info(json_encode($apiCrendentials));
            $params['grant_type'] = 'password';

            $params['client_id'] =   $apiCrendentials['client_id'];
            $params['client_secret'] = $apiCrendentials['client_secret'];
            $params['username'] = $request->input('email');
            $params['password'] = $request->input('password');
            Log::info(" _________________PARAMS  ____________");
            Log::info(json_encode($params));
              Log::info(" _________________url  ____________");
              Log::info(url('api/oauth/token'));
              Log::info(" _________________response ____________");
            $response = $client->request('POST', url('api/oauth/token'), ['form_params' => $params]);
            Log::info(json_encode($response));
            if($response->getStatusCode() == 200) {
                $body = json_decode($response->getBody(), true);
                $request->session()->put('access_token', $body['access_token']);
                $request->session()->put('refresh_token', $body['refresh_token']);
                switch(Auth::user()->type()) {
                    case 'gseller':
                        return redirect()->intended('/presale');
                    break;
                    default:
                        return redirect()->intended($this->redirectTo);
                    break;
                }
                
            } 
            Auth::logout();
            return redirect()->back()->withInput($request->input());
        } catch(\Exception $e) {
            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
            Auth::logout();
            return redirect()->back()->withInput($request->input());
        }
    }
}
