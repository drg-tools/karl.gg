<?php

namespace App\Http\Controllers\Comments;

use App\Comment;
use App\Comments\CommentRepository;
use App\Comments\Formatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Comments\StoreComment;
use App\Http\Requests\Comments\UpdateComment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommentController extends Controller
{
    public function __construct(protected CommentRepository $comments)
    {
    }

    /**
     * List one page of a page's comment threads.
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate(['page_id' => ['required', 'string']]);

        $comments = $this->comments->forPage($request->string('page_id'), [
            'page' => $request->integer('page', 1),
            'order' => $request->string('order')->value() ?: null,
            'target' => $request->integer('target') ?: null,
            'user_id' => $request->user()?->getKey(),
        ]);

        return response()->json($comments->toArray());
    }

    public function store(StoreComment $request): JsonResponse
    {
        $attributes = $request->safe()->only('page_id', 'parent_id', 'content', 'permalink');
        $attributes += [
            'root_id' => $request->replyRootId(),
            'author_ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ];

        if ($user = $request->user()) {
            $attributes['user_id'] = $user->getKey();
        } else {
            $attributes += $request->safe()->only('author_name', 'author_email', 'author_url');
        }

        $comment = $this->comments->create($attributes);

        return response()->json($comment, 201);
    }

    public function update(UpdateComment $request, Comment $comment): JsonResponse
    {
        $this->authorize('update', $comment);

        return response()->json($this->comments->updateContent($comment, $request->validated('content')));
    }

    public function destroy(Comment $comment): Response
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return response()->noContent();
    }

    public function report(Request $request, Comment $comment): Response
    {
        $this->authorize('report', $comment);

        $this->comments->report($comment, $request->user()->getKey());

        return response()->noContent();
    }

    /**
     * Render unsaved comment text as HTML for the editor's preview tab.
     */
    public function preview(Request $request, Formatter $formatter): string
    {
        return $formatter->render($formatter->parse((string) $request->input('content')));
    }
}
