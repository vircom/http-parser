<?php

declare(strict_types=1);

namespace VirCom\HttpParser\ValueObject\StartLine;

abstract class AbstractStartLine
{
    protected HttpVersion $httpVersion;

    public function getHttpVersion(): HttpVersion
    {
        return $this->httpVersion;
    }
}
