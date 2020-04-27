<?php

declare(strict_types=1);

namespace VirCom\HttpParser\Parser\StartLine;

use VirCom\HttpParser\Parser\StartLine\Exception\InvalidHttpStatusCodeException;
use VirCom\HttpParser\ValueObject\StartLine\ResponseStartLine;

class ResponseStartLineParser extends AbstractStartLineParser
{
    public function parse(string $data): ResponseStartLine
    {
        [$httpVersion, $statusCode, $statusText] = $this->splitLine($data);

        return new ResponseStartLine(
            $this->parseHttpVersion($httpVersion),
            $this->parseHttpStatusCode($statusCode),
            $statusText
        );
    }

    private function parseHttpStatusCode(string $data): string
    {
        if (! preg_match('/^\d{3}$/', $data)) {
            throw new InvalidHttpStatusCodeException(
                sprintf(
                    'HTTP status code: %s is invalid. It must be 3-digit integer value.',
                    $data
                )
            );
        }

        return $data;
    }
}
