<?php declare(strict_types=1);

namespace Neat\Http\Guzzle;

use GuzzleHttp\Psr7\Response as GuzzleResponse;
use GuzzleHttp\Psr7\Stream;
use Neat\Http\Response;
use Neat\Http\Response\Redirect;
use Neat\Http\TransmitterInterface;
use TypeError;

use function GuzzleHttp\Psr7\stream_for;

class Transmitter implements TransmitterInterface
{
    /**
     * @param string $html
     * @return Response
     */
    public function html(string $html): Response
    {
        return $this->response()->withContentType('text/html')->withBody(stream_for($html));
    }

    /**
     * @param mixed $body
     * @return Response
     */
    public function json($body): Response
    {
        return $this->response()->withContentType('application/json')->withBody(stream_for(json_encode($body)));
    }

    /**
     * @return Redirect
     */
    public function redirect(): Redirect
    {
        return new Redirect($this->response());
    }

    /**
     * @param resource $resource
     * @param string   $name
     * @param string   $mimeType
     * @param bool     $attachment
     * @return Response
     */
    public function download($resource, string $name, string $mimeType, bool $attachment = true): Response
    {
        if (!is_resource($resource)) {
            $method = __METHOD__;
            $type   = gettype($resource);
            throw new TypeError("Argument 1 passed to $method must be of the type resource, $type given");
        }

        $download = new Response\Download(new Stream($resource), $name, $mimeType, $attachment);

        return $download->write($this->response());
    }

    /**
     * @return Response
     */
    public function response(): Response
    {
        return new Response(new GuzzleResponse);
    }

    /**
     * @param Response $response
     */
    public function send(Response $response)
    {
        header($response->statusLine());
        foreach ($response->headers() as $header) {
            header($header->line());
        }

        $body = $response->psr()->getBody();
        if ($body->isSeekable()) {
            $body->rewind();
        }

        if (!$body->isReadable()) {
            echo $body;

            return;
        }

        while (!$body->eof()) {
            echo $body->read(1024);
        }
    }
}
