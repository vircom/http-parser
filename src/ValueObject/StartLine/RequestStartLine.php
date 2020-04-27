<?php

declare(strict_types=1);

namespace VirCom\HttpParser\ValueObject\StartLine;

class RequestStartLine extends AbstractStartLine
{
    private HttpMethod $httpMethod;
    private string $targetRequest;

    public function __construct(
        HttpMethod $httpMethod,
        string $targetRequest,
        HttpVersion $httpVersion
    ) {
        $this->httpMethod = $httpMethod;
        $this->targetRequest = $targetRequest;
        $this->httpVersion = $httpVersion;
    }

    public function getHttpMethod(): HttpMethod
    {
        return $this->httpMethod;
    }

    public function getTargetRequest(): string
    {
        return $this->targetRequest;
    }
}
