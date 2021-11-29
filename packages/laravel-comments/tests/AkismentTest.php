<?php

namespace Hazzard\Comments\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Hazzard\Comments\Akismet;
use Mockery;

class AkismentTest extends TestCase
{
    /** @test */
    public function it_checks_comment_for_spam()
    {
        $this->assertTrue((new Akismet('secret', $this->httpMock('true')))->commentCheck(['content' => 'hello world']));

        $this->assertFalse((new Akismet('secret', $this->httpMock('false')))->commentCheck(['content' => 'hello world']));
    }

    /** @test */
    public function it_throws_exception_if_akismet_ket_is_not_set()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('You must set the "akismet_key" in config/services.php.');

        $this->assertFalse((new Akismet(null, new Client))->commentCheck(['content' => 'hello world']));
    }

    /**
     * @param  string  $response
     * @return \GuzzleHttp\ClientInterface
     */
    protected function httpMock(string $response)
    {
        ($http = Mockery::mock(Client::class))
            ->shouldReceive('post')
            ->once()
            ->andReturn(new Response(200, [], $response));

        return $http;
    }
}
