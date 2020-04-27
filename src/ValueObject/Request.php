<?php

declare(strict_types=1);

namespace VirCom\HttpParser\ValueObject;

use VirCom\HttpParser\ValueObject\Headers\HeadersCollection;
use VirCom\HttpParser\ValueObject\StartLine\RequestStartLine;

class Request extends AbstractMessage
{
    private RequestStartLine $startLine;

    public function __construct(
        RequestStartLine $startLine,
        HeadersCollection $headers,
        ?string $body
    ) {
        parent::__construct($headers, $body);
        $this->startLine = $startLine;
    }

    public function getStartLine(): RequestStartLine
    {
        return $this->startLine;
    }
}
