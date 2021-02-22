<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vote;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Validator;

class VoteController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }
    
    public function index()
    {
        return Vote::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'bail|required|min:1|exists:posts,id|unique:votes,post_id,NULL,NULL,user_id,'.(int)Auth::id(),
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        return Vote::create(array_merge(
            $validator->validated(),
            ['user_id' => (int)Auth::id()]
        ));
    }

    //Perdoruesit qe kane votuar postin
    public function show($id)
    { 
        return DB::table('users')
            ->join('votes','votes.user_id','=','users.id')
            ->select('users.*')
            ->where('votes.post_id',$id)
            ->get();
    }

    // Nr votave Posti
    // public function countPostVotes($id)
    // { 
    //       return Vote::where('post_id',$id)->count();
    // }


    public function destroy($id)
    {   
        return DB::table('votes')
            ->where('user_id','=',(int)Auth::id())
            ->where('post_id','=',$id)
            ->delete();
    }
}
