<?php

declare(strict_types=1);

namespace VirCom\HttpParser\Parser;

use VirCom\HttpParser\Parser\Headers\HeadersParser;
use VirCom\HttpParser\Parser\StartLine\ResponseStartLineParser;
use VirCom\HttpParser\ValueObject\Response;

final class ResponseParser extends AbstractMessageParser
{
    private ResponseStartLineParser $responseStartLineParser;

    public function __construct(
        ResponseStartLineParser $responseStartLineParser,
        HeadersParser $headersParser
    ) {
        $this->responseStartLineParser = $responseStartLineParser;
        $this->headersParser = $headersParser;
    }

    public function parse(string $data): Response
    {
        [$startLine, $headers, $body] = $this->getStartLineHeadersAndBodyBlocks($data);

        return new Response(
            $this->responseStartLineParser->parse($startLine),
            $this->headersParser->parse($headers),
            $body
        );
    }
}
