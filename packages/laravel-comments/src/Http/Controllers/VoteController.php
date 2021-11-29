<?php

namespace Hazzard\Comments\Http\Controllers;

use Hazzard\Comments\Contracts\CommentRepository;
use Hazzard\Comments\Http\Requests\StoreVote;
use Illuminate\Routing\Controller;

class VoteController extends Controller
{
    /**
     * @var \Hazzard\Comments\Contracts\CommentRepository
     */
    protected $comments;

    /**
     * Create a new controller instance.
     *
     * @param  \Hazzard\Comments\Contracts\CommentRepository  $comments
     * @return void
     */
    public function __construct(CommentRepository $comments)
    {
        $this->middleware('auth');
        $this->comments = $comments;
    }

    /**
     * Upvote the given comment.
     *
     * @param  \Hazzard\Comments\Http\Requests\StoreVote  $request
     * @param  int  $commentId
     * @return \Illuminate\Http\Response
     */
    public function upvote(StoreVote $request, $commentId)
    {
        $this->comments->upvote($commentId, $request->user()->getKey());

        return response()->json(null, 204);
    }

    /**
     * Downvote the given comment.
     *
     * @param  \Hazzard\Comments\Http\Requests\StoreVote  $request
     * @param  int  $commentId
     * @return \Illuminate\Http\Response
     */
    public function downvote(StoreVote $request, $commentId)
    {
        $this->comments->downvote($commentId, $request->user()->getKey());

        return response()->json(null, 204);
    }

    /**
     * Remove the vote for the given comment.
     *
     * @param  \Hazzard\Comments\Http\Requests\StoreVote  $request
     * @param  int  $commentId
     * @return \Illuminate\Http\Response
     */
    public function remove(StoreVote $request, $commentId)
    {
        $this->comments->removeVote($commentId, $request->user()->getKey());

        return response()->json(null, 204);
    }
}
