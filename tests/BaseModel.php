<?php

namespace DirectoryTree\Anonymize\Tests;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected static $unguarded = true;
}
