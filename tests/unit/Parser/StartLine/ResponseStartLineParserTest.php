<?php

declare(strict_types=1);

namespace VirComTest\HttpParser\Parser\StartLine;

use PHPUnit\Framework\TestCase;
use VirCom\HttpParser\Parser\StartLine\Exception\InvalidHttpStatusCodeException;
use VirCom\HttpParser\Parser\StartLine\Exception\InvalidHttpVersionException;
use VirCom\HttpParser\Parser\StartLine\ResponseStartLineParser;
use VirCom\HttpParser\ValueObject\StartLine\HttpVersion;

class ResponseStartLineParserTest extends TestCase
{
    private ResponseStartLineParser $parser;

    protected function setUp(): void
    {
        $this->parser = new ResponseStartLineParser();
    }

    /**
     * @return string[][]
     */
    public function httpProtocolVersionStatusCodeAndStatusTextDataProvider(): array
    {
        $httpProtocolVersions = array_values(HttpVersion::toArray());
        $httpStatusTexts = ['', 'OK'];

        $result = [];
        foreach ($httpProtocolVersions as $httpProtocolVersion) {
            foreach ($httpStatusTexts as $httpStatusText) {
                $result[sprintf('HTTP protocol version: %s and status code: %s and status text: %s', $httpProtocolVersion, '200', $httpStatusText)] = [
                    $httpProtocolVersion,
                    '200',
                    $httpStatusText,
                ];
            }
        }

        return $result;
    }

    /**
     * @return string[][]
     */
    public function httpProtocolVersionAndStatusTextWithInvalidStatusCodeDataProvider(): array
    {
        $httpProtocolVersions = array_values(HttpVersion::toArray());
        $httpStatusTexts = ['', 'OK'];

        $result = [];
        foreach ($httpProtocolVersions as $httpProtocolVersion) {
            foreach ($httpStatusTexts as $httpStatusText) {
                $result[sprintf('HTTP protocol version: %s and status code: %s and status text: %s', $httpProtocolVersion, 'INVALID', $httpStatusText)] = [
                    $httpProtocolVersion,
                    'INVALID',
                    $httpStatusText,
                ];
            }
        }

        return $result;
    }

    /**
     * @return string[][]
     */
    public function httpProtocolStatusTextAndStatusCodeWithInvalidVersionDataProvider(): array
    {
        $httpStatusTexts = ['', 'OK'];

        $result = [];
        foreach ($httpStatusTexts as $httpStatusText) {
            $result[sprintf('HTTP protocol version: %s and status code: %s and status text: %s', 'INVALID', '200', $httpStatusText)] = [
                'INVALID',
                '200',
                $httpStatusText,
            ];
        }

        return $result;
    }

    /**
     * @dataProvider httpProtocolVersionStatusCodeAndStatusTextDataProvider
     */
    public function testThatParserSupportsStartLine(
        string $httpVersion,
        string $statusCode,
        string $statusText
    ): void {
        $response = $this->parser->parse(
            sprintf(
                '%s %s %s',
                $httpVersion,
                $statusCode,
                $statusText
            )
        );

        $this->assertSame($httpVersion, $response->getHttpVersion()->__toString());
        $this->assertSame($statusCode, $response->getStatusCode());
        $this->assertSame($statusText, $response->getStatusText());
    }

    /**
     * @dataProvider httpProtocolVersionAndStatusTextWithInvalidStatusCodeDataProvider
     */
    public function testThatParserThrowsExceptionWhenStatusCodeIsInvalid(
        string $httpVersion,
        string $statusCode,
        string $statusText
    ): void {
        $this->expectException(InvalidHttpStatusCodeException::class);

        $this->parser->parse(
            sprintf(
                '%s %s %s',
                $httpVersion,
                $statusCode,
                $statusText
            )
        );
    }

    /**
     * @dataProvider httpProtocolStatusTextAndStatusCodeWithInvalidVersionDataProvider
     */
    public function testThatParserThrowsExceptionWhenVersionIsInvalid(
        string $httpVersion,
        string $statusCode,
        string $statusText
    ): void {
        $this->expectException(InvalidHttpVersionException::class);

        $this->parser->parse(
            sprintf(
                '%s %s %s',
                $httpVersion,
                $statusCode,
                $statusText
            )
        );
    }
}
