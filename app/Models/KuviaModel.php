<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;

abstract class KuviaModel extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use Auditable;
}
