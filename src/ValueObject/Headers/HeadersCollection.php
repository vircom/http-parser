<?php

declare(strict_types=1);

namespace VirCom\HttpParser\ValueObject\Headers;

use Countable;
use Iterator;

class HeadersCollection implements Countable, Iterator
{
    /**
     * @var Header[]
     */
    private array $values = [];
    private int $position = 0;

    public function count(): int
    {
        return count($this->values);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function current(): Header
    {
        return $this->values[$this->position];
    }

    public function next(): void
    {
        $this->position++;
    }

    public function valid(): bool
    {
        return isset($this->values[$this->position]);
    }

    public function add(Header $header): void
    {
        $this->values[] = $header;
    }
}
