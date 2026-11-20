<?php

namespace App\Comments;

use Illuminate\Contracts\Cache\Repository as Cache;
use s9e\TextFormatter\Configurator;
use s9e\TextFormatter\Configurator\Bundles\MediaPack;
use s9e\TextFormatter\Parser;
use s9e\TextFormatter\Renderer;
use s9e\TextFormatter\Unparser;

/**
 * Turns comment text into stored XML and rendered HTML: Markdown, links,
 * emails, images, videos, media embeds and text emoticons.
 */
class Formatter
{
    public function __construct(protected Cache $cache)
    {
    }

    /**
     * Parse plain text into the stored XML document.
     */
    public function parse(string $text): string
    {
        return $this->component('parser')->parse($text);
    }

    /**
     * Transform the stored XML document back into plain text.
     */
    public function unparse(string $xml): string
    {
        return Unparser::unparse($xml);
    }

    /**
     * Render the stored XML document as HTML.
     */
    public function render(string $xml): string
    {
        spl_autoload_register(function (string $class): void {
            $file = storage_path("app/$class.php");

            if (file_exists($file)) {
                include $file;
            }
        });

        return $this->component('renderer')->render($xml);
    }

    /**
     * Forget the cached parser and renderer so the next call rebuilds them.
     */
    public function flush(): void
    {
        $this->cache->forget('comments.formatter.parser');
        $this->cache->forget('comments.formatter.renderer');
    }

    /**
     * @param  'parser'|'renderer'  $key
     */
    protected function component(string $key): Parser|Renderer
    {
        return $this->cache->rememberForever(
            "comments.formatter.$key",
            fn (): Parser|Renderer => $this->configurator()->finalize()[$key],
        );
    }

    protected function configurator(): Configurator
    {
        $configurator = new Configurator;
        $configurator->rootRules->enableAutoLineBreaks();
        $configurator->rendering->engine = 'PHP';
        $configurator->rendering->engine->cacheDir = storage_path('app');
        $configurator->Escaper;
        $configurator->Autolink;
        $configurator->Autoemail;
        $configurator->Autoimage;
        $configurator->Autovideo;
        $configurator->MediaEmbed->createIndividualBBCodes = true;
        (new MediaPack)->configure($configurator);
        $configurator->Litedown;

        foreach ([
            ':)' => '&#x1f604;',
            ':D' => '&#x1f603;',
            ':P' => '&#x1f61c;',
            ':(' => '&#x1f61f;',
            ':|' => '&#x1f610;',
            ';)' => '&#x1f609;',
            ':*' => '&#x1f618;',
            ':\'(' => '&#x1f622;',
            ':\')' => '&#x1f602;',
            ':O' => '&#x1f62e;',
            'B)' => '&#x1f60e;',
            '>:(' => '&#x1f621;',
        ] as $code => $emoji) {
            $configurator->Emoticons->add($code, $emoji);
        }

        return $configurator;
    }
}
