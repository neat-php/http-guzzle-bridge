<?php declare(strict_types=1);

namespace Neat\Http\Guzzle;

use GuzzleHttp\Psr7\ServerRequest;
use Neat\Http\ReceiverInterface;
use Neat\Http\Request;
use Neat\Http\StatusException;

class Receiver implements ReceiverInterface
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->request = new Request(ServerRequest::fromGlobals());
        $contentType   = $this->request->contentType();
        if ($contentType && 'application/json' === $contentType->getValue()) {
            $this->json();
        }
    }

    /**
     * @return Request
     */
    public function request(): Request
    {
        return $this->request;
    }

    /**
     * Decodes the json body and throws an exception when decoding fails
     *
     * @throws StatusException When parsing the body fails
     */
    private function json()
    {
        $body = $this->request->body();
        if (!$body) {
            return;
        }
        $decoded = json_decode($body);
        if (json_last_error()) {
            throw new StatusException(400, null, new \Exception(json_last_error_msg(), json_last_error()));
        }

        $this->request = new Request($this->request->psr()->withParsedBody($decoded));
    }
}
