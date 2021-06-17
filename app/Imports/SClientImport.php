<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\{Importable, WithHeadingRow};

class SClientImport implements WithHeadingRow
{
     use Importable;
}
