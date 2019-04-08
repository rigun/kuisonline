<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use Hash;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function username($username){
      return User::where('username',$username)->get();
    }
    public function index(){
      return User::all();
    }
    public function getRole(){
      $role_id = JWTAuth::parseToken()->authenticate()->role_id; 
      return Role::where('id',$role_id)->first()->name;
    }
    public function updatePassword(Request $request, $id)
    {
        $this->validateWith([
            'password_lama' => 'required',
            'password_baru' => 'required',

          ]);
          $user = User::findOrFail($id);
          if(Hash::check($request->password_lama, $user->password)){
            $user->password = Hash::make($request->password_baru);    
            $json=['status' => 'success','msg'=>'Password berhasil diubah'];
          }else{
            $json=['status' => 'failed','msg'=>'Password yang anda masukkan salah, silahkan coba lagi'];
          }
          $user->save();
          
          return response()->json($json);
    }
    public function register(Request $request)
    {
      $this->validateWith([
        'username' => 'required',
        'password' => 'required',
      ]);
      $user = new User();
      $user->username = $request->username;
      $user->password = bcrypt($request->password); 
      $user->save();

      return 'Berhasil';
    }
    public function store(Request $request)
    {
      $this->validateWith([
        'username' => 'required'
      ]);
      $role = Role::where('name','user')->first()->id;
      $user = new User();
      $user->username = $request->username;
      $user->password = bcrypt('12345678'); 
      $user->role_id = $role;
      $user->save();

      return 'Berhasil';
    }
    public function update(Request $request, $id){
      $this->validateWith([
        'username' => 'required',
        'password' => 'required'
      ]);
      $user = User::find($id);
      $user->username = $request->username;
      if($request->reset){
        $user->password = bcrypt('12345678'); 
      }
      $user->save();

      return 'Berhasil';
    }
    public function destroy($id){
      $user = User::find($id);
      $user->delete();
      return 'Berhasil';
    }
    public function login(Request $request)
    {
      $credentials = $request->only(['username', 'password']);

      if (!$token = auth()->attempt($credentials)) {
        return response()->json(['error' => 'Unauthorized'], 401);
      }

      return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
      
      return response()->json([
        'access_token' => $token,
        'id' => auth()->user()->id,
        'status' => auth()->user()->status,
        'role' => auth()->user()->role()->first()->name
      ]);
    }
    public function getAuthenticatedUser()
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                    return response()->json(['user_not_found'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
        $userdata = User::with('role')->where('id',$user->id)->first();
        return response()->json(compact('userdata'));
    }
}
