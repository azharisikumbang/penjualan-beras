<?php

require_once __DIR__ . '/../Contract/EntityInterface.php';

class Takaran implements EntityInterface
{
    private ?int $id = null;

    private string $variant;

    public function getId(): int|null
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getVariant(): string
    {
        return $this->variant;
    }

    /**
     * @param string $variant
     */
    public function setVariant(string $variant): void
    {
        $this->variant = $variant;
    }

    public function toArray(): array
    {
        return [
          'id' => $this->getId(),
          'variant' => $this->getVariant()
        ];
    }

}