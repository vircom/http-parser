<?php

declare(strict_types=1);

namespace VirComTest\HttpParser\Parser\Headers;

use PHPUnit\Framework\TestCase;
use VirCom\HttpParser\Parser\Headers\Exception\InvalidHeaderException;
use VirCom\HttpParser\Parser\Headers\HeaderParser;

class HeaderParserTest extends TestCase
{
    private HeaderParser $parser;

    protected function setUp(): void
    {
        $this->parser = new HeaderParser();
    }

    /**
     * @return array[]
     */
    public function headerLineDataProvider(): array
    {
        return [
            'Header-name: Header name and empty value' => ['Header-name', ['']],
            (string) sprintf('Header-name: Header name and header values: %s', implode(', ', ['Header value 1'])) => ['Header-name', ['Header value 1']],
            (string) sprintf('Header-name: Header name and header values: %s', implode(', ', ['Header value 1', 'Header value 2'])) => ['Header-name', ['Header value 1', 'Header value 2']],
        ];
    }

    /**
     * @dataProvider headerLineDataProvider
     * @param string[][] $headerValues
     */
    public function testThatParserSupportsHeaderLine(
        string $headerName,
        array $headerValues
    ): void {
        $requestLine = sprintf(
            "%s: %s\r\n",
            $headerName,
            implode(', ', $headerValues)
        );

        $header = $this->parser->parse($requestLine);

        $this->assertSame($headerName, $header->getName());
        $this->assertSame(
            $headerValues,
            $header->getValues()
        );
    }

    public function testThatParserThrowsExceptionWhenHeaderNameIsInvalid(): void
    {
        $this->expectException(InvalidHeaderException::class);
        $this->parser->parse('Header name: Header value');
    }
}
