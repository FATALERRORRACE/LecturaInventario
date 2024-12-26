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
use Illuminate\Support\Facades\DB;

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
        if(!$request->espacio)
            return back()->with('error', 'Seleccione un espacio');
        $result = $client->call('ws_autentica_usuario', $xmlr);
        $result = iconv('ISO-8859-1', 'UTF-8', $result);
        try {
            $result = (array)new \SimpleXMLElement($result);
            $password = env('USER_PASS');
            $user = User::first();
        } catch (\Throwable $th) {
            $user = User::where('username', $request->alias)->first();
            $password = $request->password;
            $result = [];
            $result['usuario'] = [];
            $result['usuario']['nome_pessoa'] = $user->name;
            $result['usuario']['email'] = $user->email;
            $result['usuario']['cod_documento'] = $user->username;
            $result['usuario']['unidade_informacao'] = "test";
        }

        if(isset($result['usuario'])){
            $result['usuario'] = (array)$result['usuario'];
            Log::info('User Logged: ' . json_encode($result['usuario']));
            $credetials = [
                'username' => $user->username,
                'password' => $password,
            ];
            if (Auth::attempt($credetials)) {

                $token = Str::random(60);

                $user->forceFill([
                    'api_token' => hash('sha256', $token),
                ])->save();
                
                $adminFind = DB::table('usuariosadministradores')->where('username', $request->alias)->first();
                if($adminFind)
                    $request->session()->put('admin', 1);
                else
                    $request->session()->put('admin', 0);

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
 
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->forget('admin');
        $request->session()->forget('apiToken');
        $request->session()->forget('espacio');
        $request->session()->forget('username');
        $request->session()->forget('nome_pessoa');
        $request->session()->forget('email');
        $request->session()->forget('cod_documento');
        $request->session()->forget('unidade_informacao');   
        return redirect()->route('login');
    }
}