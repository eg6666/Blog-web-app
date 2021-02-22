<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Validator;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{   
    public function __construct() {
        $this->middleware('auth:api');
    }
    
    public function index()
    {   
        return Comment::all();
    }

    public function store(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'post_id' => 'bail|required|min:1|exists:posts,id',
            'content' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        return Comment::create(array_merge(
            $validator->validated(),
            ['user_id' => (int)Auth::id()]
        ));
    }

    //Komentet e nje posti
    public function show($id)
    {
        return Comment::where('post_id',$id)->get();
    }

    public function update(Request $request, $id)
    {   
        $validator = Validator::make($request->all(), [
            'content' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        
        $comment=Comment::find($id);
        $comment->update($request->all());
        return $comment;
    }

    public function destroy($id)
    {
        return Comment::destroy($id);
    }
}
