<?php
namespace App\Services;

class HeaderFontCalculator
{
    public function __construct(
        private int $baseSize = 16,
        private int $step = 2,
        private int $minSize = 10,
    ) {}

    public function sizeFor(int $lineIndex): int
    {
        return max($this->minSize, $this->baseSize - ($lineIndex * $this->step));
    }

    public function calculateAll(array $lines): array
    {
        $result = [];
        foreach ($lines as $index => $text) {
            $result[] = [
                'text' => $text,
                'size' => $this->sizeFor($index),
            ];
        }
        return $result;
    }
}
