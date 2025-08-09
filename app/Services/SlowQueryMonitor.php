<?php

namespace App\Services;

use App\Models\SlowQuery;
use Illuminate\Database\Connection;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SlowQueryMonitor
{
    /** @var array<string,array> */
    private array $frames = [];

    public function register(): void
    {
        // Capture the *caller* right before PDO executes
        DB::beforeExecuting(function (...$args) {
            // Older/L10 style: ($sql, $bindings, $conn)
            if (!($args[0] instanceof Connection)) {
                [$sql, $bindings, $conn] = $args;
            } else {
                // Newer/L11 style: ($conn, &$sql, $bindings)
                [$conn, $sql, $bindings] = $args;
            }

            if (! $conn instanceof Connection) {
                return; // safety
            }

            $key = $this->queryKey($conn->getName(), $sql, $bindings);
            $this->frames[$key] = $this->findCallerFrame();
        });

        // Handle slow queries after execution
        DB::listen(function (QueryExecuted $query) {
            $threshold = (int) config('database.slow_query_ms', 300);
            if ($query->time <= $threshold) {
                return;
            }

            $key   = $this->queryKey($query->connectionName ?? 'default', $query->sql, $query->bindings);
            $frame = $this->frames[$key] ?? null;
            unset($this->frames[$key]); // avoid unbounded growth

            $fingerprint = $this->fingerprint($query, $frame);

            $row = SlowQuery::firstWhere('fingerprint', $fingerprint);

            if (! $row) {
                SlowQuery::create([
                    'fingerprint'   => $fingerprint,
                    'connection'    => $query->connectionName,
                    'sql'           => $query->sql,
                    'raw_sql'       => method_exists($query, 'toRawSql') ? $query->toRawSql() : $query->sql,
                    'bindings'      => $query->bindings,
                    'time'          => $query->time,
                    'file'          => $frame['file'] ?? null,
                    'line'          => $frame['line'] ?? null,
                    'occurrences'   => 1,
                    'first_seen_at' => now(),
                    'last_seen_at'  => now(),
                    'max_time'      => $query->time,
                    'avg_time'      => $query->time,
                    'resolved'      => false,
                ]);
                return;
            }

            $newCount = $row->occurrences + 1;
            $newAvg   = (int) floor((($row->avg_time * $row->occurrences) + $query->time) / $newCount);

            $row->update([
                'raw_sql'      => method_exists($query, 'toRawSql') ? $query->toRawSql() : $row->raw_sql,
                'bindings'     => $query->bindings,
                'time'         => $query->time,
                'line'         => $frame['line'] ?? $row->line,
                'occurrences'  => $newCount,
                'last_seen_at' => now(),
                'max_time'     => max($row->max_time, $query->time),
                'avg_time'     => $newAvg,
                'resolved'     => false,
            ]);
        });
    }

    private function queryKey(string $conn, string $sql, array $bindings): string
    {
        // Normalize SQL + bindings to match before/after phases
        $normSql = Str::of($sql)->lower()->replaceMatches('/\s+/', ' ')->trim()->toString();
        $packed  = $conn.'|'.$normSql.'|'.sha1(serialize($bindings));
        return sha1($packed);
    }

    private function fingerprint(QueryExecuted $query, ?array $frame): string
    {
        $sql  = Str::of($query->sql)->lower()->replaceMatches('/\s+/', ' ')->trim()->toString();
        $conn = $query->connectionName ?? 'default';
        $file = $frame['file'] ?? 'unknown';
        return sha1($conn.'|'.$file.'|'.$sql);
    }

    private function findCallerFrame(): ?array
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

        // Skip vendor/, framework internals, and this monitor file itself
        $self = __FILE__;

        foreach ($trace as $f) {
            if (!isset($f['file'])) {
                continue;
            }

            $file = $f['file'];

            if (
                str_contains($file, DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR) ||
                $file === $self ||
                str_contains($file, DIRECTORY_SEPARATOR.'Illuminate'.DIRECTORY_SEPARATOR) ||
                str_contains($file, DIRECTORY_SEPARATOR.'Symfony'.DIRECTORY_SEPARATOR)
            ) {
                continue;
            }

            return ['file' => $file, 'line' => $f['line'] ?? null];
        }

        return null;
    }
}
