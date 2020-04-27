<?php

declare(strict_types=1);

namespace VirCom\HttpParser\Parser;

use VirCom\HttpParser\Parser\Headers\HeadersParser;

abstract class AbstractMessageParser
{
    protected const LINES_SEPARATOR = "\r\n";

    protected HeadersParser $headersParser;

    /**
     * @return string[]
     */
    private function splitHeadersFromBody(string $data): array
    {
        return array_pad(explode(self::LINES_SEPARATOR . self::LINES_SEPARATOR, $data, 2), 2, '');
    }

    /**
     * @return string[]
     */
    private function splitStartLineFromHeaders(string $data): array
    {
        return array_pad(explode(self::LINES_SEPARATOR, $data, 2), 2, '');
    }

    /**
     * @return string[]
     */
    protected function getStartLineHeadersAndBodyBlocks(string $data): array
    {
        [$headersBlock, $body] = $this->splitHeadersFromBody($data);
        [$startLine, $headers] = $this->splitStartLineFromHeaders($headersBlock);

        return [$startLine, $headers, $body];
    }
}
