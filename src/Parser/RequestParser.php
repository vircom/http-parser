<?php

declare(strict_types=1);

namespace VirCom\HttpParser\Parser;

use VirCom\HttpParser\Parser\Headers\HeadersParser;
use VirCom\HttpParser\Parser\StartLine\RequestStartLineParser;
use VirCom\HttpParser\ValueObject\Request;

class RequestParser extends AbstractMessageParser
{
    private RequestStartLineParser $requestStartLineParser;

    public function __construct(
        RequestStartLineParser $requestStartLineParser,
        HeadersParser $headersParser
    ) {
        $this->requestStartLineParser = $requestStartLineParser;
        $this->headersParser = $headersParser;
    }

    public function parse(string $data): Request
    {
        [$startLine, $headers, $body] = $this->getStartLineHeadersAndBodyBlocks($data);

        return new Request(
            $this->requestStartLineParser->parse($startLine),
            $this->headersParser->parse($headers),
            $body
        );
    }
}
