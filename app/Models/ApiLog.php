<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'api_endpoint',
        'method',
        'request_headers',
        'response_headers',
        'response_body',
        'request_body',
        'ip',
        'user_agent',
        'status_code',
        'response_time',
        'error',
    ];

    protected $casts = [
        'request_headers' => 'array',
        'response_headers' => 'array',
        'response_body' => 'array',
        'request_body' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('api_endpoint', 'like', '%' . $search . '%')
                ->orWhere('method', 'like', '%' . $search . '%')
                ->orWhere('ip', 'like', '%' . $search . '%')
                ->orWhere('user_agent', 'like', '%' . $search . '%')
                ->orWhere('status_code', 'like', '%' . $search . '%');
        });
    }

    public function scopeSort($query, array $sorts)
    {
        $query->when($sorts['sort'] ?? null, function ($query, $sort) {
            $query->orderBy($sort, $sorts['order'] ?? 'asc');
        });
    }

    public function scopePaginate($query, $perPage)
    {
        return $query->paginate($perPage);
    }

    public function scopeGetLogs($query, array $filters, array $sorts, $perPage)
    {
        return $query->filter($filters)->sort($sorts)->paginate($perPage);
    }

    public function scopeGetLog($query, $id)
    {
        return $query->findOrFail($id);
    }

    public function scopeCreateLog($query, array $data)
    {
        return $query->create($data);
    }

    public function scopeUpdateLog($query, $id, array $data)
    {
        $log = $query->findOrFail($id);
        $log->update($data);

        return $log;
    }

    public function scopeDeleteLog($query, $id)
    {
        $log = $query->findOrFail($id);
        $log->delete();

        return $log;
    }

    public function scopeGetUserLogs($query, $userId, array $filters, array $sorts, $perPage)
    {
        return $query->where('user_id', $userId)->filter($filters)->sort($sorts)->paginate($perPage);
    }

    public function scopeGetUserLog($query, $userId, $id)
    {
        return $query->where('user_id', $userId)->findOrFail($id);
    }

    public function getFormattedResponseTimeAttribute()
    {
        return $this->response_time . 'ms';
    }
}
