<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Binafy\LaravelReaction\Enums\LaravelReactionTypeEnum;
use Illuminate\Http\Request;

class ReactionController
{
    public function reactToPost(Request $request, $postId)
    {
        $request->validate([
            'type' => 'required|string|max:50'
        ]);

        $post = Post::findOrFail($postId);
        $user = auth()->user();

        $user->reaction($request->type, $post);

        return response()->json([
            'message' => 'Reaction added successfully',
            'reaction' => $request->type,
        ]);
    }

    public function removeReaction($postId)
    {
        $post = Post::findOrFail($postId);
        $user = auth()->user();

        $user->removeReactions($post);
        return response()->json([
            'message' => 'Reaction removed successfully'
        ]);
    }

    public function getReactors($postId)
    {
        $post = Post::findOrFail($postId);

        return response()->json([
            'post_id' => $postId,
            '$reactors' => $post->getReactors(),
        ]);
    }

    public function myReaction($postId)
    {
        $post = Post::findOrFail($postId);
        $user = auth()->user();

        $reaction = $user->myReaction($post);

        return response()->json([
            'reaction' => $reaction?->type ?? null
        ]);
    }

    public function reactionCounts(Post $post)
    {
        $reactionCounts = $post->getReactionsWithCount();

        return response()->json([
            'post_id' => $post->title,
            'all_reaction_counts' => $reactionCounts,
        ]);
    }
}
