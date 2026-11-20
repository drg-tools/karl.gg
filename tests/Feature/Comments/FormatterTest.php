<?php

namespace Tests\Feature\Comments;

use App\Comments\Formatter;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class FormatterTest extends TestCase
{
    public function test_it_parses_text_into_xml_and_back(): void
    {
        $xml = $this->formatter()->parse('Hello world');

        $this->assertSame('<t><p>Hello world</p></t>', $xml);
        $this->assertSame('Hello world', $this->formatter()->unparse($xml));
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function renderedComments(): array
    {
        return [
            'markdown links' => ['[Link text](http://example.org)', '<p><a href="http://example.org">Link text</a></p>'],
            'markdown emphasis' => ['**bold**', '<p><strong>bold</strong></p>'],
            'plain urls' => ['More info at http://example.org.', '<p>More info at <a href="http://example.org">http://example.org</a>.</p>'],
            'plain emails' => ['Email me at user@example.org', '<p>Email me at <a href="mailto:user@example.org">user@example.org</a></p>'],
            'image urls' => ['Image: http://example.org/image.png', '<p>Image: <img src="http://example.org/image.png"></p>'],
            'video urls' => ['Video: http://example.org/video.mp4', '<p>Video: <video src="http://example.org/video.mp4"></video></p>'],
            'emoticons' => ['Hello world :)!', '<p>Hello world 😄!</p>'],
        ];
    }

    #[DataProvider('renderedComments')]
    public function test_it_renders_comment_text_as_html(string $text, string $html): void
    {
        $formatter = $this->formatter();

        $this->assertSame($html, $formatter->render($formatter->parse($text)));
    }

    public function test_it_embeds_youtube_videos(): void
    {
        $formatter = $this->formatter();

        $html = $formatter->render($formatter->parse('[youtube]-cEzsCAzTak[/youtube]'));

        $this->assertStringContainsString('src="https://www.youtube.com/embed/-cEzsCAzTak"', $html);
    }

    public function test_it_escapes_html_in_comment_text(): void
    {
        $formatter = $this->formatter();

        $html = $formatter->render($formatter->parse('<script>alert(1)</script>'));

        $this->assertSame('<p>&lt;script&gt;alert(1)&lt;/script&gt;</p>', $html);
    }

    protected function formatter(): Formatter
    {
        return $this->app->make(Formatter::class);
    }
}
