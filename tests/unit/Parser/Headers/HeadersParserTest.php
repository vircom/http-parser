<?php

declare(strict_types=1);

namespace VirComTest\HttpParser\Parser\Headers;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\Stub;
use VirCom\HttpParser\Parser\Headers\HeaderParser;
use VirCom\HttpParser\Parser\Headers\HeadersParser;

class HeadersParserTest extends TestCase
{
    /**
     * @var HeaderParser|Stub
     */
    private HeaderParser $headerParser;
    private HeadersParser $headersParser;

    protected function setUp(): void
    {
        $this->headerParser = $this->createStub(HeaderParser::class);

        $this->headersParser = new HeadersParser($this->headerParser);
    }

    /**
     * @return array[]
     */
    public function headerLinesDataProvider(): array
    {
        return [
            'empty value' => ['', 0],
            'lines count 1 and single header value' => ['Header name: Header value 1', 1],
            'lines count 1 and multiple header value' => ['Header name: Header value 1, Header value 2', 1],
        ];
    }

    /**
     * @dataProvider headerLinesDataProvider
     * @param integer $count
     */
    public function testThatParserSupportsHeadersLines(
        string $headersLine,
        int $count
    ): void {
        $headers = $this->headersParser->parse($headersLine);

        $this->assertCount(
            $count,
            $headers
        );
    }
}
