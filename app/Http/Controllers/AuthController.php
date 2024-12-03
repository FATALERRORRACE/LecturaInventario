<?php
 
namespace App\Http\Controllers;

@include('./nusoap.php');
use App\Models\User;
use App\Models\Bibliotecas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; 

class AuthController extends Controller
{
    public function register()
    {
        return view('register');
    }

    public function registerPost(Request $request)
    {
        $user = new User();

        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
 
        $user->save();
 
        return back()->with('success', 'Register successfully');
    }
 
    public function login(){
        return view('auth.login', 
            [
                'bibliotecas' => Bibliotecas::get()->toArray()
            ]
        );
    }
 
    public function loginPost(Request $request)
    {
        include(__DIR__.'/nusoap_a.php');
        $xmlr = array(
            'cod_pessoa' => $request->alias,
            'senha_pessoa' => $request->password,
            'chave' => env('CHAVE')
        );

        $result = $client->call('ws_autentica_usuario', $xmlr);
        $result = iconv('ISO-8859-1', 'UTF-8', $result);
        $result = (array)new \SimpleXMLElement($result);
        if(isset($result['usuario'])){
            $result['usuario'] = (array)$result['usuario'];
            Log::info('User Logged: ' . json_encode($result['usuario']));
            $user = User::first();
            $credetials = [
                'username' => $user->username,
                'password' => env('USER_PASS'),
            ];
            if (Auth::attempt($credetials)) {

                $token = Str::random(60);

                $user->forceFill([
                    'api_token' => hash('sha256', $token),
                ])->save();
                
                $request->session()->put('apiToken', $token);
                $request->session()->put('espacio', $request->espacio);
                $request->session()->put('username', $result['usuario']['nome_pessoa']);
                $request->session()->put('nome_pessoa', $result['usuario']['nome_pessoa']);
                $request->session()->put('email', $result['usuario']['email']);
                $request->session()->put('cod_documento', $result['usuario']['cod_documento']);
                $request->session()->put('unidade_informacao', $result['usuario']['unidade_informacao']);
                return redirect('/')->with('success', 'Login Success');
            }
        }elseif($result['erro']){
            return back()->with('error', 'Credenciales invalidas');    
        }
        return back()->with('error', 'Error Email or Password');
    }
 
    public function logout()
    {
        Auth::logout();
 
        return redirect()->route('login');
    }
}