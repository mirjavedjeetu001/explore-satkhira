<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class BirthdayCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'birthday_date',
        'card_image',
        'bengali_message',
        'english_message',
    ];

    protected function casts(): array
    {
        return [
            'birthday_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(BirthdayCardComment::class);
    }

    public function scopeForToday(Builder $query): Builder
    {
        return $query->whereDate('birthday_date', now()->toDateString());
    }

    public function scopeForEligibleUsers(Builder $query): Builder
    {
        return $query->whereHas('user', function ($userQuery) {
            static::applyEligibleUserFilter($userQuery);
        });
    }

    public static function syncTodayForEligibleUsers(): int
    {
        $today = now();
        static::cleanupDuplicatesForDate($today->toDateString());

        $users = User::query()
            ->whereNotNull('date_of_birth')
            ->whereMonth('date_of_birth', $today->month)
            ->whereDay('date_of_birth', $today->day)
            ->where(function ($query) {
                static::applyEligibleUserFilter($query);
            })
            ->pluck('id');

        if ($users->isEmpty()) {
            return 0;
        }

        $now = now();
        $rows = $users->map(function ($userId) use ($today, $now) {
            return [
                'user_id' => $userId,
                'birthday_date' => $today->toDateString(),
                'bengali_message' => 'আপনার এই বিশেষ দিনে আমরা আপনার সুস্বাস্থ্য, সুখ এবং সমৃদ্ধি কামনা করি।',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        })->all();

        // Atomic insert to avoid race conditions against the unique key.
        $inserted = DB::table('birthday_cards')->insertOrIgnore($rows);

        // Normalize older bad data (date-time text values in SQLite) into one card per user/day.
        static::cleanupDuplicatesForDate($today->toDateString());

        return $inserted;
    }

    public static function cleanupDuplicatesForDate(string $date): void
    {
        $cards = static::query()
            ->whereDate('birthday_date', $date)
            ->orderByDesc('id')
            ->get(['id', 'user_id']);

        $idsToDelete = [];

        foreach ($cards->groupBy('user_id') as $group) {
            $duplicateIds = $group->pluck('id')->slice(1)->all();
            if (!empty($duplicateIds)) {
                $idsToDelete = array_merge($idsToDelete, $duplicateIds);
            }
        }

        if (!empty($idsToDelete)) {
            static::query()->whereIn('id', $idsToDelete)->delete();
        }
    }

    private static function applyEligibleUserFilter($query): void
    {
        $query->whereIn('role_id', function ($subQuery) {
            $subQuery->select('id')
                ->from('roles')
                ->whereIn('slug', ['super-admin', 'admin', 'moderator']);
        })->orWhere('is_upazila_moderator', true);
    }
}
