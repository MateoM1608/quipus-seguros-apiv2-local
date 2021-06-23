<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\{Importable, WithHeadingRow};

class FileImport implements WithHeadingRow
{
     use Importable;
}
