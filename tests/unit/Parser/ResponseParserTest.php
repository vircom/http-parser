<?php

declare(strict_types=1);

namespace VirComTest\HttpParser\Parser;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use VirCom\HttpParser\Parser\Headers\Exception\InvalidHeaderException;
use VirCom\HttpParser\Parser\Headers\HeadersParser;
use VirCom\HttpParser\Parser\ResponseParser;
use VirCom\HttpParser\Parser\StartLine\Exception\InvalidHttpStatusCodeException;
use VirCom\HttpParser\Parser\StartLine\Exception\InvalidHttpVersionException;
use VirCom\HttpParser\Parser\StartLine\ResponseStartLineParser;

class ResponseParserTest extends TestCase
{
    /**
     * @var ResponseStartLineParser|MockObject
     */
    private $startLineParser;

    /**
     * @var HeadersParser|MockObject
     */
    private $headersParser;

    private ResponseParser $parser;

    protected function setUp(): void
    {
        $this->startLineParser = $this->createMock(ResponseStartLineParser::class);
        $this->headersParser = $this->createMock(HeadersParser::class);

        $this->parser = new ResponseParser(
            $this->startLineParser,
            $this->headersParser
        );
    }

    /**
     * @return string[][]
     */
    public function responseDataProvider(): array
    {
        return [
            'HTTP response contains only start line' => ["HTTP/1.1 200 OK\r\n\r\n", 'HTTP/1.1 200 OK', '', ''],
            'HTTP response contains start line and headers without body' => ["HTTP/1.1 200 OK\r\nHeader-name: Header value\r\n\r\n", 'HTTP/1.1 200 OK', 'Header-name: Header value', ''],
            'HTTP response contains start line and body without headers' => ["HTTP/1.1 200 OK\r\n\r\n{\"fruit\":\"Apple\",\"size\":\"Large\",\"color\":\"Red\"}", 'HTTP/1.1 200 OK', '', '{"fruit":"Apple","size":"Large","color":"Red"}'],
            'HTTP response contains start line, headers and body' => ["HTTP/1.1 200 OK\r\nHeader-name: Header value\r\n\r\n{\"fruit\":\"Apple\",\"size\":\"Large\",\"color\":\"Red\"}", 'HTTP/1.1 200 OK', 'Header-name: Header value', '{"fruit":"Apple","size":"Large","color":"Red"}'],
        ];
    }

    /**
     * @dataProvider responseDataProvider
     */
    public function testParserExtractsStartLine(
        string $response,
        string $startLine,
        string $headerLines,
        string $body
    ): void {
        $this->startLineParser
            ->expects($this->atLeastOnce())
            ->method('parse')
            ->with($this->equalTo($startLine));

        $this->parser->parse($response);
    }

    /**
     * @dataProvider responseDataProvider
     */
    public function testParserExtractsHeadersLine(
        string $request,
        string $startLine,
        string $headerLines,
        string $body
    ): void {
        $this->headersParser
            ->expects($this->atLeastOnce())
            ->method('parse')
            ->with($this->equalTo($headerLines));

        $this->parser->parse($request);
    }

    /**
     * @dataProvider responseDataProvider
     */
    public function testParserExtractsBody(
        string $request,
        string $startLine,
        string $headerLines,
        string $body
    ): void {
        $request = $this->parser->parse($request);

        $this->assertSame(
            $body,
            $request->getBody()
        );
    }

    public function testParserThrowsExceptionWhenHttpProtocolVersionIsInvalid(): void
    {
        $this->expectException(InvalidHttpVersionException::class);

        $this->startLineParser
            ->method('parse')
            ->willThrowException(new InvalidHttpVersionException());

        $this->parser->parse('');
    }

    public function testParserThrowsExceptionWhenHttpProtocolStatusCodeIsInvalid(): void
    {
        $this->expectException(InvalidHttpStatusCodeException::class);

        $this->startLineParser
            ->method('parse')
            ->willThrowException(new InvalidHttpStatusCodeException());

        $this->parser->parse('');
    }

    public function testParserThrowsExceptionWhenHttpHeaderIsInvalid(): void
    {
        $this->expectException(InvalidHeaderException::class);

        $this->headersParser
            ->method('parse')
            ->willThrowException(new InvalidHeaderException());

        $this->parser->parse('');
    }
}
