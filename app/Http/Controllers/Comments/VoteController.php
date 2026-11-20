<?php

namespace App\Http\Controllers\Comments;

use App\Comment;
use App\Comments\CommentRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VoteController extends Controller
{
    public function __construct(protected CommentRepository $comments)
    {
    }

    public function upvote(Request $request, Comment $comment): Response
    {
        $this->comments->upvote($comment, $request->user()->getKey());

        return response()->noContent();
    }

    public function downvote(Request $request, Comment $comment): Response
    {
        $this->comments->downvote($comment, $request->user()->getKey());

        return response()->noContent();
    }

    public function remove(Request $request, Comment $comment): Response
    {
        $this->comments->removeVote($comment, $request->user()->getKey());

        return response()->noContent();
    }
}
