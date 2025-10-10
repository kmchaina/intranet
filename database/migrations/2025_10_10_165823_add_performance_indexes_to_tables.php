<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Check if an index exists on a table
     */
    private function indexExists(string $table, string $indexName): bool
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            $indexes = DB::select("SELECT name FROM sqlite_master WHERE type='index' AND tbl_name=?", [$table]);
            foreach ($indexes as $index) {
                if ($index->name === $indexName) {
                    return true;
                }
            }
            return false;
        } elseif ($driver === 'mysql') {
            $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$indexName]);
            return count($indexes) > 0;
        }

        return false;
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Announcements - Add additional targeting index
        Schema::table('announcements', function (Blueprint $table) {
            if (!$this->indexExists('announcements', 'idx_announcements_targeting')) {
                $table->index(['target_scope', 'is_published'], 'idx_announcements_targeting');
            }
            if (!$this->indexExists('announcements', 'idx_announcements_creator')) {
                $table->index('created_by', 'idx_announcements_creator');
            }
        });

        // Messages - Add sender index (conversation indexes already exist)
        Schema::table('messages', function (Blueprint $table) {
            if (!$this->indexExists('messages', 'idx_messages_sender')) {
                $table->index(['sender_id', 'created_at'], 'idx_messages_sender');
            }
        });

        // Users - Add composite role/location indexes
        Schema::table('users', function (Blueprint $table) {
            if (!$this->indexExists('users', 'idx_users_role_centre')) {
                $table->index(['role', 'centre_id'], 'idx_users_role_centre');
            }
            if (!$this->indexExists('users', 'idx_users_role_station')) {
                $table->index(['role', 'station_id'], 'idx_users_role_station');
            }
            if (!$this->indexExists('users', 'idx_users_headquarters')) {
                $table->index('headquarters_id', 'idx_users_headquarters');
            }
            if (!$this->indexExists('users', 'idx_users_department')) {
                $table->index('department_id', 'idx_users_department');
            }
        });

        // Documents - Optimize document searches and access control
        Schema::table('documents', function (Blueprint $table) {
            if (!$this->indexExists('documents', 'idx_documents_department_time')) {
                $table->index(['department_id', 'created_at'], 'idx_documents_department_time');
            }
            if (!$this->indexExists('documents', 'idx_documents_uploader')) {
                $table->index(['uploaded_by', 'created_at'], 'idx_documents_uploader');
            }
            if (!$this->indexExists('documents', 'idx_documents_access')) {
                $table->index('access_level', 'idx_documents_access');
            }
        });

        // Notifications - Optimize unread notification queries
        Schema::table('notifications', function (Blueprint $table) {
            if (!$this->indexExists('notifications', 'idx_notifications_type')) {
                $table->index('type', 'idx_notifications_type');
            }
        });

        // Events - Optimize event queries
        Schema::table('events', function (Blueprint $table) {
            if (!$this->indexExists('events', 'idx_events_date_range')) {
                $table->index(['start_date', 'end_date'], 'idx_events_date_range');
            }
            if (!$this->indexExists('events', 'idx_events_creator')) {
                $table->index('created_by', 'idx_events_creator');
            }
        });

        // News - Optimize news feed queries
        Schema::table('news', function (Blueprint $table) {
            if (!$this->indexExists('news', 'idx_news_published')) {
                $table->index(['published_at', 'is_published'], 'idx_news_published');
            }
            if (!$this->indexExists('news', 'idx_news_creator')) {
                $table->index('created_by', 'idx_news_creator');
            }
        });

        // Conversation participants - Optimize user conversation lookups
        if (Schema::hasTable('conversation_participants')) {
            Schema::table('conversation_participants', function (Blueprint $table) {
                if (!$this->indexExists('conversation_participants', 'idx_conv_participant_read')) {
                    $table->index(['user_id', 'last_read_at'], 'idx_conv_participant_read');
                }
                if (!$this->indexExists('conversation_participants', 'idx_conv_participant_lookup')) {
                    $table->index(['conversation_id', 'user_id'], 'idx_conv_participant_lookup');
                }
            });
        }

        // Activity events - Optimize analytics queries
        Schema::table('activity_events', function (Blueprint $table) {
            if (!$this->indexExists('activity_events', 'idx_activity_user_time')) {
                $table->index(['user_id', 'created_at'], 'idx_activity_user_time');
            }
            if (!$this->indexExists('activity_events', 'idx_activity_type')) {
                $table->index(['event_type', 'entity_type'], 'idx_activity_type');
            }
        });

        // Polls - Optimize poll queries
        Schema::table('polls', function (Blueprint $table) {
            if (!$this->indexExists('polls', 'idx_polls_creator')) {
                $table->index(['created_by', 'created_at'], 'idx_polls_creator');
            }
            if (!$this->indexExists('polls', 'idx_polls_active')) {
                $table->index(['closes_at', 'is_active'], 'idx_polls_active');
            }
        });

        // Birthday wishes - Optimize celebration queries
        Schema::table('birthday_wishes', function (Blueprint $table) {
            if (!$this->indexExists('birthday_wishes', 'idx_wishes_recipient')) {
                $table->index(['recipient_id', 'created_at'], 'idx_wishes_recipient');
            }
            if (!$this->indexExists('birthday_wishes', 'idx_wishes_sender')) {
                $table->index(['sender_id', 'created_at'], 'idx_wishes_sender');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes - using if exists checks
        Schema::table('birthday_wishes', function (Blueprint $table) {
            if ($this->indexExists('birthday_wishes', 'idx_wishes_recipient')) {
                $table->dropIndex('idx_wishes_recipient');
            }
            if ($this->indexExists('birthday_wishes', 'idx_wishes_sender')) {
                $table->dropIndex('idx_wishes_sender');
            }
        });

        Schema::table('polls', function (Blueprint $table) {
            if ($this->indexExists('polls', 'idx_polls_creator')) {
                $table->dropIndex('idx_polls_creator');
            }
            if ($this->indexExists('polls', 'idx_polls_active')) {
                $table->dropIndex('idx_polls_active');
            }
        });

        Schema::table('activity_events', function (Blueprint $table) {
            if ($this->indexExists('activity_events', 'idx_activity_user_time')) {
                $table->dropIndex('idx_activity_user_time');
            }
            if ($this->indexExists('activity_events', 'idx_activity_type')) {
                $table->dropIndex('idx_activity_type');
            }
        });

        if (Schema::hasTable('conversation_participants')) {
            Schema::table('conversation_participants', function (Blueprint $table) {
                if ($this->indexExists('conversation_participants', 'idx_conv_participant_read')) {
                    $table->dropIndex('idx_conv_participant_read');
                }
                if ($this->indexExists('conversation_participants', 'idx_conv_participant_lookup')) {
                    $table->dropIndex('idx_conv_participant_lookup');
                }
            });
        }

        Schema::table('news', function (Blueprint $table) {
            if ($this->indexExists('news', 'idx_news_published')) {
                $table->dropIndex('idx_news_published');
            }
            if ($this->indexExists('news', 'idx_news_creator')) {
                $table->dropIndex('idx_news_creator');
            }
        });

        Schema::table('events', function (Blueprint $table) {
            if ($this->indexExists('events', 'idx_events_date_range')) {
                $table->dropIndex('idx_events_date_range');
            }
            if ($this->indexExists('events', 'idx_events_creator')) {
                $table->dropIndex('idx_events_creator');
            }
        });

        Schema::table('notifications', function (Blueprint $table) {
            if ($this->indexExists('notifications', 'idx_notifications_type')) {
                $table->dropIndex('idx_notifications_type');
            }
        });

        Schema::table('documents', function (Blueprint $table) {
            if ($this->indexExists('documents', 'idx_documents_department_time')) {
                $table->dropIndex('idx_documents_department_time');
            }
            if ($this->indexExists('documents', 'idx_documents_uploader')) {
                $table->dropIndex('idx_documents_uploader');
            }
            if ($this->indexExists('documents', 'idx_documents_access')) {
                $table->dropIndex('idx_documents_access');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if ($this->indexExists('users', 'idx_users_role_centre')) {
                $table->dropIndex('idx_users_role_centre');
            }
            if ($this->indexExists('users', 'idx_users_role_station')) {
                $table->dropIndex('idx_users_role_station');
            }
            if ($this->indexExists('users', 'idx_users_headquarters')) {
                $table->dropIndex('idx_users_headquarters');
            }
            if ($this->indexExists('users', 'idx_users_department')) {
                $table->dropIndex('idx_users_department');
            }
        });

        Schema::table('messages', function (Blueprint $table) {
            if ($this->indexExists('messages', 'idx_messages_sender')) {
                $table->dropIndex('idx_messages_sender');
            }
        });

        Schema::table('announcements', function (Blueprint $table) {
            if ($this->indexExists('announcements', 'idx_announcements_targeting')) {
                $table->dropIndex('idx_announcements_targeting');
            }
            if ($this->indexExists('announcements', 'idx_announcements_creator')) {
                $table->dropIndex('idx_announcements_creator');
            }
        });
    }
};
