<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Support\Carbon;
use Str;

class PostController extends Controller
{
    public function index()
    {
        SEOTools::setTitle('Blog');

        $posts = Post::published()->with('user')->latest('published_at')->paginate(9);
        $latestPublishedAt = $posts->onFirstPage()
            ? $posts->first()?->published_at
            : Post::published()->max('published_at');

        return view('posts.index', [
            'posts' => $posts,
            'latestPublishedAt' => $latestPublishedAt ? Carbon::parse($latestPublishedAt) : null,
        ]);
    }

    public function show($id)
    {
        $post = Post::findOrFail($id);

        SEOTools::setTitle($post->title);
        SEOTools::setDescription(Str::limit($post->content, 80));
        SEOTools::metatags()->addMeta('article:published_time', $post->published_at->toW3CString(), 'property');

        SEOTools::opengraph()->setType('article');
        SEOTools::opengraph()->setArticle([
            'published_time' => $post->published_at->toW3CString(),
            'modified_time' => $post->updated_at->toW3CString(),
            'tag' => 'Deep Rock Galactic News',
        ]);

        SEOTools::jsonLd()->setType('Article');

        $morePosts = Post::published()
            ->with('user')
            ->whereKeyNot($post->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('posts.show', [
            'post' => $post,
            'morePosts' => $morePosts,
        ]);
    }
}
