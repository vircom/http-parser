<?php

declare(strict_types=1);

namespace VirCom\HttpParser\ValueObject\StartLine;

class ResponseStartLine extends AbstractStartLine
{
    private string $statusCode;
    private string $statusText;

    public function __construct(
        HttpVersion $httpVersion,
        string $statusCode,
        string $statusText
    ) {
        $this->httpVersion = $httpVersion;
        $this->statusCode = $statusCode;
        $this->statusText = $statusText;
    }

    public function getStatusCode(): string
    {
        return $this->statusCode;
    }

    public function getStatusText(): string
    {
        return $this->statusText;
    }
}
