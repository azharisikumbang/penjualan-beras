<?php

interface EntityInterface
{
    public function getId(): int|null;

    public function toArray(): array;
}