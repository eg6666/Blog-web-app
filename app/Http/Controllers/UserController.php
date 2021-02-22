<?php

namespace App\Http\Controllers;

use App\Mail\AccessEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function index()
    {
        return User::all();
    }

    public function store(Request $request)
    {  
    	$validator = Validator::make($request->all(), [
            'fname' => 'bail|required|max:20',
            'lname' => 'bail|required|max:20',
            'gender' => 'bail|required|max:1',
            'birthday' => 'bail|required|date_format:"Y-m-d"|before:tomorrow',
            'email' => 'bail|required|unique:users|max:255|regex:/@fti\.edu\.al$/',
            'password' => 'bail|required|regex:/^.*(?=.{8,30})(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!$#%.,^&@]).*$/',
            'user_type_id' => 'bail|required|min:1|exists:user_types,id',
            'department_id' => 'bail|required|min:1|exists:departments,id',
            'access' => 'boolean'
        ]);

        return User::create($validator->validated());
    }

    public function show($id)
    {
        return User::find($id);
    }

    public function update(Request $request, $id)
    {   
        $validator = Validator::make($request->all(), [
            'password' => 'bail|required|regex:/^.*(?=.{8,30})(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!$#%.,^&]).*$/',
            'department_id' => 'bail|required|min:1|exists:departments,id',
            'access' => 'boolean'
        ]);
        $user=User::find($id);
        $user->update($validator->validated());
        return $user;
    }

    public function changeAccess($id)
    {   $user=User::find($id);
        $data=[
            'adminEmail'=>User::find(Auth::id())->email,
            'name'=>$user->fname.' '.$user->lname
        ];
        Mail::to($user->email)->send(new AccessEmail($data));
        if($user->access){
            return $user->update(['access'=>0]);
        }
        return $user->update(['access'=>1]);
    }

    public function changeAllNewStudentAccess()
    {   
        return User::where('user_type_id', 4)
                    ->where('access', 0)
                    ->update(['access'=>1]);
    }

    public function getUsersWithoutAccess()
    {   
        return User::where('access', 0)
                ->orderBy('created_at','DESC')
                ->get();
    }

    public function destroy($id)
    {
        return User::destroy($id);
    }
}
