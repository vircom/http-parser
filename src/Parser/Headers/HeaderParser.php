<?php

declare(strict_types=1);

namespace VirCom\HttpParser\Parser\Headers;

use VirCom\HttpParser\Parser\Headers\Exception\InvalidHeaderException;
use VirCom\HttpParser\ValueObject\Headers\Header;

class HeaderParser
{
    private const HEADER_SEPARATOR = ':';
    private const HEADER_VALUES_SEPARATOR = ',';
    private const HEADER_PATTERN = '/^([\x00-\x19\x21-\x39\x3B-\x7F]+)(:){1}(.*)$/';

    public function parse(string $data): Header
    {
        $this->validateHeader($data);
        [$name, $valuesBlock] = $this->splitHeader($data);
        $values = $this->splitHeaderValues($valuesBlock);

        return new Header($name, $values);
    }

    /**
     * @return string[]
     */
    private function splitHeader(string $data): array
    {
        return array_pad(explode(self::HEADER_SEPARATOR, $data, 2), 2, '');
    }

    /**
     * @return string[]
     */
    private function splitHeaderValues(string $data): array
    {
        return array_map(
            function ($value) {
                return rtrim(ltrim($value, " \t"), "\r\n");
            },
            explode(self::HEADER_VALUES_SEPARATOR, $data)
        );
    }

    private function validateHeader(string $data): void
    {
        if (preg_match(self::HEADER_PATTERN, $data) === 0) {
            throw new InvalidHeaderException(
                sprintf(
                    'HTTP header: %s is invalid.',
                    $data
                )
            );
        }
    }
}
