<?php

declare(strict_types=1);

namespace VirCom\HttpParser;

use VirCom\HttpParser\Parser\Headers\HeaderParser;
use VirCom\HttpParser\Parser\Headers\HeadersParser;
use VirCom\HttpParser\Parser\RequestParser;
use VirCom\HttpParser\Parser\ResponseParser;
use VirCom\HttpParser\Parser\StartLine\RequestStartLineParser;
use VirCom\HttpParser\Parser\StartLine\ResponseStartLineParser;

class HttpParserFactory
{
    public function createRequestParser(): RequestParser
    {
        return new RequestParser(
            new RequestStartLineParser(),
            new HeadersParser(
                new HeaderParser()
            )
        );
    }

    public function createResponseParser(): ResponseParser
    {
        return new ResponseParser(
            new ResponseStartLineParser(),
            new HeadersParser(
                new HeaderParser()
            )
        );
    }
}
