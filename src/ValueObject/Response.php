<?php

declare(strict_types=1);

namespace VirCom\HttpParser\ValueObject;

use VirCom\HttpParser\ValueObject\Headers\HeadersCollection;
use VirCom\HttpParser\ValueObject\StartLine\ResponseStartLine;

class Response extends AbstractMessage
{
    private ResponseStartLine $startLine;

    public function __construct(
        ResponseStartLine $startLine,
        HeadersCollection $headers,
        ?string $body
    ) {
        parent::__construct($headers, $body);
        $this->startLine = $startLine;
    }

    public function getStartLine(): ResponseStartLine
    {
        return $this->startLine;
    }
}
