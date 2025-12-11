<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestLog extends Model
{
    protected $fillable = [
        'method',
        'url',
        'full_url',
        'path',
        'query_params',
        'post_data',
        'headers',
        'raw_body',
        'ip_address',
        'user_agent',
        'server_vars',
    ];

    protected $casts = [
        'query_params' => 'array',
        'post_data' => 'array',
        'headers' => 'array',
        'server_vars' => 'array',
    ];
}
