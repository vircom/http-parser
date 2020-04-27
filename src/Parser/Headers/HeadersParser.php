<?php

declare(strict_types=1);

namespace VirCom\HttpParser\Parser\Headers;

use VirCom\HttpParser\ValueObject\Headers\HeadersCollection;

class HeadersParser
{
    protected const LINES_SEPARATOR = "\r\n";

    private HeaderParser $headerParser;

    public function __construct(
        HeaderParser $headerParser
    ) {
        $this->headerParser = $headerParser;
    }

    public function parse(string $data): HeadersCollection
    {
        $headers = new HeadersCollection();
        if ($data === '') {
            return $headers;
        }

        $lines = $this->splitHeaders($data);
        foreach ($lines as $line) {
            $headers->add($this->headerParser->parse($line));
        }

        return $headers;
    }

    /**
     * @return string[]
     */
    private function splitHeaders(string $data): array
    {
        return explode(self::LINES_SEPARATOR, $data);
    }
}
