<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    public $timestamps = false; // We only use created_at

    protected $fillable = [
        'user_id', 'type', 'description', 'ip_address',
        'user_agent', 'url', 'method', 'properties',
    ];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Quick helper to log activity.
     */
    public static function log(string $type, string $description, ?array $properties = null): self
    {
        $request = request();

        return self::create([
            'user_id'    => auth()->id(),
            'type'       => $type,
            'description'=> $description,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent() ? substr($request->userAgent(), 0, 255) : null,
            'url'        => $request?->fullUrl() ? substr($request->fullUrl(), 0, 255) : null,
            'method'     => $request?->method(),
            'properties' => $properties,
        ]);
    }
}
