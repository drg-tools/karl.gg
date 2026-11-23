<?php

namespace App\Http\Controllers\Comments;

use App\Comments\CommentRepository;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VoteController extends Controller
{
    public function __construct(protected CommentRepository $comments)
    {
    }

    public function upvote(Request $request, Comment $comment): Response
    {
        $this->authorize('vote', $comment);

        $this->comments->upvote($comment, $request->user()->getKey());

        return response()->noContent();
    }

    public function downvote(Request $request, Comment $comment): Response
    {
        $this->authorize('vote', $comment);

        $this->comments->downvote($comment, $request->user()->getKey());

        return response()->noContent();
    }

    public function remove(Request $request, Comment $comment): Response
    {
        $this->authorize('vote', $comment);

        $this->comments->removeVote($comment, $request->user()->getKey());

        return response()->noContent();
    }
}
