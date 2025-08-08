<?php

namespace App\Services;

use App\Models\SlowQuery;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SlowQueryMonitor
{
    public function register(): void
    {
        DB::listen(function (QueryExecuted $query) {
            $threshold = (int) config('database.slow_query_ms', 1000);

            if ($query->time <= $threshold) {
                return;
            }
            // Get the top application frame to identify where the query originated
            $frame = $this->topAppFrame();
            $fingerprint = $this->fingerprint($query, $frame);

            $row = SlowQuery::firstWhere('fingerprint', $fingerprint);

            if (! $row) {
                SlowQuery::create([
                    'fingerprint' => $fingerprint,
                    'connection' => $query->connectionName,
                    'sql' => $query->sql,
                    'raw_sql' => method_exists($query, 'toRawSql') ? $query->toRawSql() : $query->sql,
                    'bindings' => $query->bindings,
                    'time' => $query->time,
                    'file' => $frame['file'] ?? null,
                    'line' => $frame['line'] ?? null,
                    'occurrences' => 1,
                    'first_seen_at' => now(),
                    'last_seen_at' => now(),
                    'max_time' => $query->time,
                    'avg_time' => $query->time,
                    'resolved' => false,
                ]);
                return;
            }

            $newCount = $row->occurrences + 1;
            $newAvg = (int) floor((($row->avg_time * $row->occurrences) + $query->time) / $newCount);

            $row->update([
                'raw_sql' => method_exists($query, 'toRawSql') ? $query->toRawSql() : $row->raw_sql,
                'bindings' => $query->bindings,
                'time' => $query->time,
                'line' => $frame['line'] ?? $row->line,
                'occurrences' => $newCount,
                'last_seen_at' => now(),
                'max_time' => max($row->max_time, $query->time),
                'avg_time' => $newAvg,
                'resolved' => false,
            ]);
        });
    }

    private function fingerprint(QueryExecuted $query, ?array $frame): string
    {
        $sql = Str::of($query->sql)->lower()->replaceMatches('/\s+/', ' ')->trim()->toString();
        $conn = $query->connectionName ?? 'default';
        $file = $frame['file'] ?? 'unknown';
        return sha1($conn.'|'.$file.'|'.$sql);
    }

    private function topAppFrame(): ?array
    {
        return collect(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS))
            ->first(fn ($f) => isset($f['file']) && ! str($f['file'])->contains('vendor'.DIRECTORY_SEPARATOR));
    }
}
