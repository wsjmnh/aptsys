<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Models\Posts;
use App\Models\PostType;
use App\Models\Document;
use Illuminate\Support\Facades\Validator;
use DateTime;

class PostController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function allPosts()
    {
        $posts = Posts::select('posts.id', 'posts.user_id', 'post_type.name as type', 'posts.content', 'posts.created_at', 'posts.updated_at')
        ->leftjoin('post_type', 'posts.type', '=', 'post_type.id')->get();
        return response()->json($posts);
    }

    public function getPost($id)
    {
        $post = Posts::find($id);
        if($post)
        {
            $post->type = $post->post_type->name;
            $post->document = $post->document;
            return response()->json($post);
        }
        return response()->json(['error' => 'Not Found']);
    }

    public function createPost(Request $request)
    {
        $post = new Posts;
        $post->title = Input::get('title');
        $post->content = Input::get('content');
        $post->user_id = Input::get('user_id');
        $post->type = Input::get('type');
        $post->save();

        if($post && Input::file('document'))
        {
            $post->type = $post->post_type->name;
            $post->document = $this->createDocument($post->id, $post->post_type->name, Input::file('document'));
            return response()->json($post);
        }
       
    }

    public function createDocument($id, $type, $document)
    {
        $originalName = $document->getClientOriginalName();
        $extension = $document->getClientOriginalExtension();
        $currentDateTime = new DateTime();
        $fileName = $currentDateTime->format('dmYHis') . $id .'.'. $extension;
        $destinationPath = storage_path().'/uploads/'.$type;
        $upload_success = $document->move($destinationPath, $fileName);
        $document = new Document;
        $document->name = $fileName;
        $document->path = $destinationPath;
        $document->post_id = $id;
        $document->save();
        return $document;
    }

    public function deletePost($id)
    {
        $post = Posts::find($id);
        if($post)
        {
            $post->document->delete();
            $post->delete();
            return response()->json(['message' => 'Successfully Deleted']);
        }
        return response()->json(['error' => 'Not Found']);
    }
}
