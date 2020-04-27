<?php

declare(strict_types=1);

namespace VirComTest\HttpParser\Parser;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use VirCom\HttpParser\Parser\Headers\Exception\InvalidHeaderException;
use VirCom\HttpParser\Parser\Headers\HeadersParser;
use VirCom\HttpParser\Parser\RequestParser;
use VirCom\HttpParser\Parser\StartLine\Exception\InvalidHttpMethodException;
use VirCom\HttpParser\Parser\StartLine\Exception\InvalidHttpVersionException;
use VirCom\HttpParser\Parser\StartLine\RequestStartLineParser;

class RequestParserTest extends TestCase
{
    /**
     * @var RequestStartLineParser|MockObject
     */
    private $startLineParser;

    /**
     * @var HeadersParser|MockObject
     */
    private $headersParser;

    private RequestParser $parser;

    protected function setUp(): void
    {
        $this->startLineParser = $this->createMock(RequestStartLineParser::class);
        $this->headersParser = $this->createMock(HeadersParser::class);

        $this->parser = new RequestParser(
            $this->startLineParser,
            $this->headersParser
        );
    }

    /**
     * @return string[][]
     */
    public function requestDataProvider(): array
    {
        return [
            'HTTP request contains only start line' => ["GET / HTTP/1.1\r\n\r\n", 'GET / HTTP/1.1', '', ''],
            'HTTP request contains start line and headers without body' => ["GET / HTTP/1.1\r\nHeader-name: Header value\r\n\r\n", 'GET / HTTP/1.1', 'Header-name: Header value', ''],
            'HTTP request contains start line and body without headers' => ["GET / HTTP/1.1\r\n\r\n{\"fruit\":\"Apple\",\"size\":\"Large\",\"color\":\"Red\"}", 'GET / HTTP/1.1', '', '{"fruit":"Apple","size":"Large","color":"Red"}'],
            'HTTP request contains start line, headers and body' => ["GET / HTTP/1.1\r\nHeader-name: Header value\r\n\r\n{\"fruit\":\"Apple\",\"size\":\"Large\",\"color\":\"Red\"}", 'GET / HTTP/1.1', 'Header-name: Header value', '{"fruit":"Apple","size":"Large","color":"Red"}'],
        ];
    }

    /**
     * @dataProvider requestDataProvider
     */
    public function testParserExtractsStartLine(
        string $request,
        string $startLine,
        string $headerLines,
        string $body
    ): void {
        $this->startLineParser
            ->expects($this->atLeastOnce())
            ->method('parse')
            ->with($this->equalTo($startLine));

        $this->parser->parse($request);
    }

    /**
     * @dataProvider requestDataProvider
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
     * @dataProvider requestDataProvider
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

    public function testParserThrowsExceptionWhenHttpProtocolMethodIsInvalid(): void
    {
        $this->expectException(InvalidHttpMethodException::class);

        $this->startLineParser
            ->method('parse')
            ->willThrowException(new InvalidHttpMethodException());

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
