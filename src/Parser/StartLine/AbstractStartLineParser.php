<?php

declare(strict_types=1);

namespace VirCom\HttpParser\Parser\StartLine;

use UnexpectedValueException;
use VirCom\HttpParser\Parser\StartLine\Exception\InvalidHttpVersionException;
use VirCom\HttpParser\ValueObject\StartLine\HttpVersion;

abstract class AbstractStartLineParser
{
    protected const FIELDS_SEPARATOR = ' ';

    protected function parseHttpVersion(string $data): HttpVersion
    {
        try {
            return new HttpVersion($data);
        } catch (UnexpectedValueException $exception) {
            throw new InvalidHttpVersionException(
                sprintf(
                    'HTTP version: %s is invalid. Allowed values are: %s.',
                    $data,
                    implode(', ', HttpVersion::values())
                )
            );
        }
    }

    /**
     * @return string[]
     */
    protected function splitLine(string $data): array
    {
        return array_pad(explode(self::FIELDS_SEPARATOR, $data, 3), 3, '');
    }
}
