<?php

declare(strict_types=1);

namespace VirComTest\HttpParser\Parser\StartLine;

use PHPUnit\Framework\TestCase;
use VirCom\HttpParser\Parser\StartLine\Exception\InvalidHttpMethodException;
use VirCom\HttpParser\Parser\StartLine\Exception\InvalidHttpVersionException;
use VirCom\HttpParser\Parser\StartLine\RequestStartLineParser;
use VirCom\HttpParser\ValueObject\StartLine\HttpMethod;
use VirCom\HttpParser\ValueObject\StartLine\HttpVersion;

class RequestStartLineParserTest extends TestCase
{
    private RequestStartLineParser $parser;

    protected function setUp(): void
    {
        $this->parser = new RequestStartLineParser();
    }

    /**
     * @return string[][]
     */
    public function httpProtocolVersionsMethodsAndTargetRequestsDataProvider(): array
    {
        $httpProtocolVersions = array_values(HttpVersion::toArray());
        $httpProtocolMethods = array_values(HttpMethod::toArray());

        $result = [];
        foreach ($httpProtocolVersions as $httpProtocolVersion) {
            foreach ($httpProtocolMethods as $httpProtocolMethod) {
                $result[sprintf('HTTP protocol method name: %s and target request: %s and HTTP protocol version: %s', $httpProtocolMethod, '/', $httpProtocolVersion)] = [
                    $httpProtocolVersion,
                    '/',
                    $httpProtocolMethod,
                ];
            }
        }

        return $result;
    }

    /**
     * @return string[][]
     */
    public function httpProtocolVersionsAndTargetRequestsWithInvalidMethodDataProvider(): array
    {
        $httpProtocolVersions = array_values(HttpVersion::toArray());

        $result = [];
        foreach ($httpProtocolVersions as $httpProtocolVersion) {
            $result[sprintf('HTTP protocol method name: INVALID and target request: %s and HTTP protocol version: %s', '/', $httpProtocolVersion)] = [
                $httpProtocolVersion,
                '/',
                'INVALID',
            ];
        }

        return $result;
    }

    /**
     * @return string[][]
     */
    public function httpProtocolMethodAndTargetRequestsWithInvalidVersionDataProvider(): array
    {
        $httpProtocolMethods = array_values(HttpMethod::toArray());

        $result = [];
        foreach ($httpProtocolMethods as $httpProtocolMethod) {
            $result[sprintf('HTTP protocol method name: %s and target request: %s and HTTP protocol version: %s', $httpProtocolMethod, '/', 'INVALID')] = [
                'INVALID',
                '/',
                $httpProtocolMethod,
            ];
        }

        return $result;
    }

    /**
     * @dataProvider httpProtocolVersionsMethodsAndTargetRequestsDataProvider
     */
    public function testThatParserSupportsStartLine(
        string $httpVersion,
        string $targetRequest,
        string $httpMethod
    ): void {
        $request = $this->parser->parse(
            sprintf(
                '%s %s %s',
                $httpMethod,
                $targetRequest,
                $httpVersion
            )
        );

        $this->assertSame($httpVersion, $request->getHttpVersion()->__toString());
        $this->assertSame($targetRequest, $request->getTargetRequest());
        $this->assertSame($httpMethod, $request->getHttpMethod()->__toString());
    }

    /**
     * @dataProvider httpProtocolVersionsAndTargetRequestsWithInvalidMethodDataProvider
     */
    public function testThatParserThrowsExceptionWhenMethodIsInvalid(
        string $httpVersion,
        string $targetRequest,
        string $httpMethod
    ): void {
        $this->expectException(InvalidHttpMethodException::class);

        $this->parser->parse(
            sprintf(
                '%s %s %s',
                $httpMethod,
                $targetRequest,
                $httpVersion
            )
        );
    }

    /**
     * @dataProvider httpProtocolMethodAndTargetRequestsWithInvalidVersionDataProvider
     */
    public function testThatParserThrowsExceptionWhenVersionIsInvalid(
        string $httpVersion,
        string $targetRequest,
        string $httpMethod
    ): void {
        $this->expectException(InvalidHttpVersionException::class);

        $this->parser->parse(
            sprintf(
                '%s %s %s',
                $httpMethod,
                $targetRequest,
                $httpVersion
            )
        );
    }
}
