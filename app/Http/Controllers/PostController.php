<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['search','pageNotifications']]);
    }

    public function index()
    {
        return DB::table('posts')
        ->select(   'posts.*',
        DB::raw('COUNT(votes.post_id) AS votes'),
        DB::raw('IF( '.Auth::id().' IN (SELECT user_id FROM votes WHERE post_id=posts.id),1,0) AS has_voted')
        )
        ->leftJoin('votes','votes.post_id','=','posts.id')
        ->orderBy('posts.updated_at','DESC')
        ->groupBy('votes.post_id','posts.id')
        ->get();
    }

    public function pageNotifications()
    {
        return Post::where('is_public',1)->get();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'bail|required|max:255',
            'is_public' => 'bail|required|min:0|max:1',
            'content' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        return Post::create(array_merge(
            $validator->validated(),
            ['user_id' => (int)Auth::id()]
        ));
    }

    public function show($id)
    {
        return Post::find($id);
    }

    public function showUserPosts()
    {
        return Post::where('user_id', (int)Auth::id())->get();
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'bail|required|max:255',
            'is_public' => 'bail|required|min:0|max:1',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $post=Post::find($id);
        $post->update($request->all());
        return $post;
    }

    public function destroy($id)
    {
        return Post::destroy($id);
    }

    public function search($value)
    {   
        if(Auth::check()){
            return DB::table('posts')
                        ->whereRaw('(title like ? or content like ?)', ['%'.$value.'%','%'.$value.'%'])
                        ->get();
        }
        return DB::table('posts')
                    ->whereRaw('is_public=1 and (title like ? or content like ?)', ['%'.$value.'%','%'.$value.'%'])
                    ->get();
    }
}
