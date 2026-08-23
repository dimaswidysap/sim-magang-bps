<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithLimit;

class ReadHeaderImport implements ToCollection, WithLimit
{
    public array $headers = [];

    public function collection(Collection $rows): void
    {
        if ($rows->isEmpty()) {
            return;
        }

        $this->headers = $rows->first()->toArray();
    }

    public function limit(): int
    {
        return 1; // Cukup baca 1 baris paling atas (header saja)
    }
}
