<?php

declare(strict_types=1);

namespace VirCom\HttpParser\ValueObject\Headers;

class Header
{
    private string $name;

    /**
     * @var string[]
     */
    private array $values = [];

    public function __construct(
        string $name,
        array $values
    ) {
        $this->name = $name;
        $this->values = $values;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValues(): array
    {
        return $this->values;
    }
}
