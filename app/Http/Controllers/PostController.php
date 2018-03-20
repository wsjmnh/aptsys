<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Models\Posts;
use App\Models\PostType;
use App\Models\Document;
use App\User;
// use Illuminate\Support\Facades\Validator;
use DateTime;
use Validator;
use App\Models\Comment;

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
            $post->comments = $post->all_comments;
            return response()->json($post);
        }
        return ['error' => 'Not Found'];
    }

    public function createPost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'user_id' => 'required',
            'content' => 'required',
            'type' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        else
        {
            $user = User::find(Input::get('user_id'));
            if($user)
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
                    $post->comments = $post->all_comments;
                    $post->document = $this->createDocument($post->id, $post->post_type->name, Input::file('document'));
                    return response()->json($post);
                }
            }
            return response()->json(['error' => 'User Not Found']);
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

    public function createPostComment(Request $request, $id)
    {
        $post = Posts::find($id);
        if($post)
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'comments' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors());
            }else
            {
                $newComment = new Comment;
                $newComment->post_id = $id;
                $newComment->name = Input::get('name');
                $newComment->comments = Input::get('comments');
                $newComment->save();
                return response()->json($newComment);
            }
        }
        return response()->json(['error' => 'Not Found']);
    }

    public function postComments($id)
    {
        $post = Posts::find($id);
        if($post)
        {
            return response()->json($post->all_comments);
        }
        return response()->json(['error' => 'Not Found']);
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
