<?php

namespace App\Models;

use Database\Factories\CouncilProjectFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouncilProject extends Model
{
    /** @use HasFactory<CouncilProjectFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'ward',
        'description',
        'budget_amount',
        'budget_year',
        'status',
        'source_title',
        'source_url',
        'evidence_url',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'budget_amount' => 'decimal:2',
            'budget_year' => 'integer',
        ];
    }
}
