<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonalisationTypePrintingAgency extends Model
{
    protected $table = 'p_t_pr_a';

    protected $fillable =
        [
            'personalisationtype_id',
            'printingagency_id'
        ];
}
