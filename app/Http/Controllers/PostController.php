<?php

namespace App\Http\Controllers;

use App\Post;
use Artesaos\SEOTools\Facades\SEOTools;
use Str;

class PostController extends Controller
{
    public function index()
    {
        SEOTools::setTitle('Blog');

        $posts = Post::published()->latest()->paginate();

        return view('posts.index')->withPosts($posts);
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

        return view('posts.show')->withPost($post);
    }
}
