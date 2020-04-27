<?php

declare(strict_types=1);

namespace VirCom\HttpParser\ValueObject;

use VirCom\HttpParser\ValueObject\Headers\HeadersCollection;

abstract class AbstractMessage
{
    private HeadersCollection $headers;
    private ?string $body;

    public function __construct(
        HeadersCollection $headers,
        ?string $body
    ) {
        $this->headers = $headers;
        $this->body = $body;
    }

    public function getHeaders(): HeadersCollection
    {
        return $this->headers;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }
}
