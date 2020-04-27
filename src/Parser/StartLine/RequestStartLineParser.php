<?php

declare(strict_types=1);

namespace VirCom\HttpParser\Parser\StartLine;

use UnexpectedValueException;
use VirCom\HttpParser\Parser\StartLine\Exception\InvalidHttpMethodException;
use VirCom\HttpParser\ValueObject\StartLine\HttpMethod;
use VirCom\HttpParser\ValueObject\StartLine\RequestStartLine;

class RequestStartLineParser extends AbstractStartLineParser
{
    public function parse(string $data): RequestStartLine
    {
        [$httpMethod, $targetRequest, $httpVersion] = $this->splitLine($data);

        return new RequestStartLine(
            $this->parseHttpMethod($httpMethod),
            $targetRequest,
            $this->parseHttpVersion($httpVersion)
        );
    }

    private function parseHttpMethod(string $data): HttpMethod
    {
        try {
            return new HttpMethod($data);
        } catch (UnexpectedValueException $exception) {
            throw new InvalidHttpMethodException(
                sprintf(
                    'HTTP method: %s is invalid. Allowed values are: %s.',
                    $data,
                    implode(', ', HttpMethod::values())
                )
            );
        }
    }
}
