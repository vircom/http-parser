<?php

declare(strict_types=1);

namespace VirCom\HttpParser\ValueObject\StartLine;

use MyCLabs\Enum\Enum;

/**
 * @psalm-template T
 * @psalm-immutable
 */
class HttpMethod extends Enum
{
    private const GET = 'GET';
    private const HEAD = 'HEAD';
    private const POST = 'POST';
    private const PUT = 'PUT';
    private const DELETE = 'DELETE';
    private const CONNECT = 'CONNECT';
    private const OPTIONS = 'OPTIONS';
    private const TRACE = 'TRACE';
    private const PATCH = 'PATCH';
}
