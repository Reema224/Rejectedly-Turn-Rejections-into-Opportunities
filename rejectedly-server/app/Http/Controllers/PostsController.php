<?php

namespace App\Http\Controllers;
use App\Models\Post;
use Illuminate\Http\Request;

class PostsController extends Controller
{
public function GetLatestNotImproved(Request $request)
{
    $posts = Post::with('user')->get();
    $notImprovedStories = $posts->filter(function($story) {
        return empty($story->story_text_improved);
    })->sortByDesc('created_at');

    $latestStory = $notImprovedStories->first();

    $latestStoryWithUser = [
        'name' => $latestStory->user->name,
        'email' => $latestStory->user->email,
        'image_url' => $latestStory->user->image_url,
        'story_type' => $latestStory->story_type,
        'story_text' => $latestStory->story_text,
    ];

    return response()->json(['latest_story' => $latestStoryWithUser], 200);
}


public function storePost(Request $request)
    {
        $post = new Post;
        $post->story_type = $request->story_type;
        $post->story_text = $request->story_text;
        $post->user_id = auth()->user()->id;

       if (!$post->save()) {
           return response()->json(['message' => 'Failed to save story'], 500);
       }

       return response()->json(['message' => 'Post created successfully', 'post' => $post], 201);
    }
}