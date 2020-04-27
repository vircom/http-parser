<?php

declare(strict_types=1);

namespace VirCom\HttpParser\ValueObject\StartLine;

use MyCLabs\Enum\Enum;

/**
 * @psalm-template T
 * @psalm-immutable
 */
class HttpVersion extends Enum
{
    private const VERSION_0_9 = 'HTTP/0.9';
    private const VERSION_1_0 = 'HTTP/1.0';
    private const VERSION_1_1 = 'HTTP/1.1';
    private const VERSION_2 = 'HTTP/2';
}
