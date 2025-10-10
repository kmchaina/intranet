<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();

        // 1. Ensure composite (conversation_id, id) index on messages for fast newest fetch
        Schema::table('messages', function (Blueprint $table) use ($driver) {
            if (!$this->indexExists('messages', 'idx_messages_conversation_id_id', $driver)) {
                $table->index(['conversation_id', 'id'], 'idx_messages_conversation_id_id');
            }
        });

        // 2. Enforce uniqueness of participants per conversation
        Schema::table('conversation_participants', function (Blueprint $table) use ($driver) {
            $uniqueName = 'uniq_conv_participants_conv_user';
            $nonUniqueName = 'idx_conv_participants_conv_user';

            $hasUnique = $this->indexExists('conversation_participants', $uniqueName, $driver, true);
            if (!$hasUnique) {
                // Drop the existing non-unique composite index if present to avoid duplicate key errors
                if ($this->indexExists('conversation_participants', $nonUniqueName, $driver)) {
                    $table->dropIndex($nonUniqueName);
                }
                $table->unique(['conversation_id', 'user_id'], $uniqueName);
            }
        });
    }

    public function down(): void
    {
        $driver = DB::connection()->getDriverName();

        Schema::table('messages', function (Blueprint $table) use ($driver) {
            if ($this->indexExists('messages', 'idx_messages_conversation_id_id', $driver)) {
                $table->dropIndex('idx_messages_conversation_id_id');
            }
        });

        Schema::table('conversation_participants', function (Blueprint $table) use ($driver) {
            $uniqueName = 'uniq_conv_participants_conv_user';
            $nonUniqueName = 'idx_conv_participants_conv_user';
            if ($this->indexExists('conversation_participants', $uniqueName, $driver, true)) {
                $table->dropUnique($uniqueName);
                // Recreate original non-unique index for parity if it does not exist
                if (!$this->indexExists('conversation_participants', $nonUniqueName, $driver)) {
                    $table->index(['conversation_id', 'user_id'], $nonUniqueName);
                }
            }
        });
    }

    /**
     * Determine if an index (optionally unique) exists.
     */
    private function indexExists(string $table, string $indexName, string $driver, bool $expectUnique = false): bool
    {
        $connection = Schema::getConnection();
        if ($driver === 'mysql') {
            $rows = $connection->select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);
            if (empty($rows)) return false;
            if ($expectUnique) {
                // Non_unique = 0 means unique in MySQL
                return (int)$rows[0]->Non_unique === 0;
            }
            return true;
        } elseif ($driver === 'sqlite') {
            $rows = $connection->select("PRAGMA index_list('{$table}')");
            foreach ($rows as $r) {
                if (isset($r->name) && $r->name === $indexName) {
                    if ($expectUnique) {
                        return isset($r->unique) ? (int)$r->unique === 1 : false;
                    }
                    return true;
                }
            }
            return false;
        } elseif ($driver === 'pgsql') {
            $rows = $connection->select("SELECT indexname, indexdef FROM pg_indexes WHERE tablename = ? AND indexname = ?", [$table, $indexName]);
            if (empty($rows)) return false;
            if ($expectUnique) {
                return str_contains(strtolower($rows[0]->indexdef), 'unique index');
            }
            return true;
        }
        // Default: unknown driver -> assume not exists so caller can attempt create
        return false;
    }
};
