<?php

namespace Hazzard\Comments\Tests;

use Illuminate\Support\Facades\Event;
use Hazzard\Comments\Events\FormatterConfigurator;

class FormatterTest extends TestCase
{
    /** @test */
    public function it_can_parse_text_into_xml()
    {
        Event::fake();

        $xml = $this->formatter()->parse('Hello world');

        $this->assertEquals('<t>Hello world</t>', $xml);

        Event::assertDispatched(FormatterConfigurator::class, function ($e) {
            return $e->configurator instanceof \s9e\TextFormatter\Configurator;
        });
    }

    /** @test */
    public function it_can_transform_xml_into_text()
    {
        $text = $this->formatter()->unparse('<t>Hello world</t>');

        $this->assertEquals('Hello world', $text);
    }

    /** @test */
    public function it_can_render_xml_into_html()
    {
        $xml = $this->formatter()->parse("\nHello world");

        $html = $this->formatter()->render($xml);

        $this->assertEquals("<br>\nHello world", $html);
    }

    /** @test */
    public function it_can_censor_words()
    {
        $formatter = $this->formatter([
            'censor' => true,
            'censored_words' => ['apple*', 'banana*'],
        ]);

        $xml = $formatter->parse('Putting apples in an applepie. Chopping some bananas on top.');
        $html = $formatter->render($xml);

        $this->assertEquals('Putting **** in an ****. Chopping some **** on top.', $html);
    }

    /** @test */
    public function it_can_convert_plain_text_urls_into_clickable_links()
    {
        $formatter = $this->formatter(['auto_link' => true]);

        $xml = $formatter->parse('More info at http://example.org.');
        $html = $formatter->render($xml);

        $this->assertEquals('More info at <a href="http://example.org">http://example.org</a>.', $html);
    }

    /** @test */
    public function it_can_convert_plain_text_emails_into_clickable_links()
    {
        $formatter = $this->formatter(['auto_email' => true]);

        $xml = $formatter->parse('Email me at user@example.org');
        $html = $formatter->render($xml);

        $this->assertEquals('Email me at <a href="mailto:user@example.org">user@example.org</a>', $html);
    }

    /** @test */
    public function it_can_convert_plain_text_image_urls_into_actual_images()
    {
        $formatter = $this->formatter(['auto_image' => true]);

        $xml = $formatter->parse('Image: http://example.org/image.png');
        $html = $formatter->render($xml);

        $this->assertEquals('Image: <img src="http://example.org/image.png">', $html);
    }

    /** @test */
    public function it_can_convert_plain_text_video_urls_into_actual_videos()
    {
        $formatter = $this->formatter(['auto_video' => true]);

        $xml = $formatter->parse('Video: http://example.org/video.mp4');
        $html = $formatter->render($xml);

        $this->assertEquals('Video: <video src="http://example.org/video.mp4"></video>', $html);
    }

    /** @test */
    public function it_can_embed_media_from_plain_text_urls_and_bbcodes()
    {
        $formatter = $this->formatter(['media_embed' => true]);

        $xml = $formatter->parse(MediaStub::TEXT);
        $html = $formatter->render($xml);

        $this->assertEquals(MediaStub::HTML, $html);
    }

    /** @test */
    public function it_can_convert_markdown_into_html()
    {
        $formatter = $this->formatter(['markdown' => true]);

        $xml = $formatter->parse('[Link text](http://example.org)');
        $html = $formatter->render($xml);

        $this->assertEquals('<p><a href="http://example.org">Link text</a></p>', $html);
    }

    /** @test */
    public function it_can_convert_bbcodes_into_html()
    {
        $formatter = $this->formatter(['bbcodes' => true, 'markdown' => false]);

        $xml = $formatter->parse('[b]Hello World[/b]');
        $html = $formatter->render($xml);

        $this->assertEquals('<b>Hello World</b>', $html);
    }

    /** @test */
    public function it_can_convert_custom_code_bbcode_to_html()
    {
        $formatter = $this->formatter(['bbcodes' => true, 'markdown' => false]);

        $xml = $formatter->parse('[code lang=php]<?php echo "hello world"; ?>[/code]');
        $html = $formatter->render($xml);

        $this->assertEquals('<pre><code class="language-php">&lt;?php echo "hello world"; ?&gt;</code></pre>', $html);
    }

    /** @test */
    public function it_can_convert_text_emoticons_into_unicode_symbols()
    {
        $formatter = $this->formatter(['emoticons' => true]);

        $xml = $formatter->parse('Hello world :)!');
        $html = $formatter->render($xml);

        $this->assertEquals('Hello world 😄!', $html);
    }

    protected function formatter(array $config = [])
    {
        return new Formatter(
            array_merge(config('comments'), $config),
            $this->app['cache.store']
        );
    }
}

class MediaStub
{
    const TEXT = "[media]http://www.dailymotion.com/video/x222z1[/media]\nhttps://www.facebook.com/video/video.php?v=10100658170103643\n[youtube]-cEzsCAzTak[/youtube]";

    const HTML = "<div data-s9e-mediaembed=\"dailymotion\" style=\"display:inline-block;width:100%;max-width:640px\"><div style=\"overflow:hidden;position:relative;padding-bottom:56.25%\"><iframe allowfullscreen=\"\" scrolling=\"no\" src=\"//www.dailymotion.com/embed/video/x222z1\" style=\"border:0;height:100%;left:0;position:absolute;width:100%\"></iframe></div></div><br>\n<iframe data-s9e-mediaembed=\"facebook\" allowfullscreen=\"\" onload=\"var a=Math.random();window.addEventListener('message',function(b){if(b.data.id==a)style.height=b.data.height+'px'});contentWindow.postMessage('s9e:'+a,'https://s9e.github.io')\" scrolling=\"no\" src=\"https://s9e.github.io/iframe/facebook.min.html#video10100658170103643\" style=\"border:0;height:360px;max-width:640px;width:100%\"></iframe><br>\n<div data-s9e-mediaembed=\"youtube\" style=\"display:inline-block;width:100%;max-width:640px\"><div style=\"overflow:hidden;position:relative;padding-bottom:56.25%\"><iframe allowfullscreen=\"\" scrolling=\"no\" style=\"background:url(https://i.ytimg.com/vi/-cEzsCAzTak/hqdefault.jpg) 50% 50% / cover;border:0;height:100%;left:0;position:absolute;width:100%\" src=\"https://www.youtube.com/embed/-cEzsCAzTak\"></iframe></div></div>";
}
