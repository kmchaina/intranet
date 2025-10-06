<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();

        // Users table indexes
        Schema::table('users', function (Blueprint $table) use ($driver) {
            if (!$this->indexExists('users', 'idx_users_role', $driver)) {
                $table->index('role', 'idx_users_role');
            }
            if (!$this->indexExists('users', 'idx_users_centre_id', $driver)) {
                $table->index('centre_id', 'idx_users_centre_id');
            }
            if (!$this->indexExists('users', 'idx_users_station_id', $driver)) {
                $table->index('station_id', 'idx_users_station_id');
            }
            if (!$this->indexExists('users', 'idx_users_email_verified_at', $driver)) {
                $table->index('email_verified_at', 'idx_users_email_verified_at');
            }
            if (!$this->indexExists('users', 'idx_users_centre_role', $driver)) {
                $table->index(['centre_id', 'role'], 'idx_users_centre_role');
            }
            if (!$this->indexExists('users', 'idx_users_station_role', $driver)) {
                $table->index(['station_id', 'role'], 'idx_users_station_role');
            }
        });

        // Announcements table indexes
        Schema::table('announcements', function (Blueprint $table) use ($driver) {
            if (!$this->indexExists('announcements', 'idx_announcements_created_by', $driver)) {
                $table->index('created_by', 'idx_announcements_created_by');
            }
            if (!$this->indexExists('announcements', 'idx_announcements_target_scope', $driver)) {
                $table->index('target_scope', 'idx_announcements_target_scope');
            }
            if (!$this->indexExists('announcements', 'idx_announcements_published_at', $driver)) {
                $table->index('published_at', 'idx_announcements_published_at');
            }
            if (!$this->indexExists('announcements', 'idx_announcements_is_published', $driver)) {
                $table->index('is_published', 'idx_announcements_is_published');
            }
            if (!$this->indexExists('announcements', 'idx_announcements_published', $driver)) {
                $table->index(['is_published', 'published_at'], 'idx_announcements_published');
            }
            if (!$this->indexExists('announcements', 'idx_announcements_scope_published', $driver)) {
                $table->index(['target_scope', 'is_published'], 'idx_announcements_scope_published');
            }
        });

        // Documents table indexes
        Schema::table('documents', function (Blueprint $table) use ($driver) {
            if (!$this->indexExists('documents', 'idx_documents_uploaded_by', $driver)) {
                $table->index('uploaded_by', 'idx_documents_uploaded_by');
            }
            if (!$this->indexExists('documents', 'idx_documents_category', $driver)) {
                $table->index('category', 'idx_documents_category');
            }
            if (!$this->indexExists('documents', 'idx_documents_access_level', $driver)) {
                $table->index('access_level', 'idx_documents_access_level');
            }
            if (!$this->indexExists('documents', 'idx_documents_visibility_scope', $driver)) {
                $table->index('visibility_scope', 'idx_documents_visibility_scope');
            }
            if (!$this->indexExists('documents', 'idx_documents_category_active', $driver)) {
                $table->index(['category', 'is_active'], 'idx_documents_category_active');
            }
            if (!$this->indexExists('documents', 'idx_documents_scope_active', $driver)) {
                $table->index(['visibility_scope', 'is_active'], 'idx_documents_scope_active');
            }
        });

        // Messages table indexes
        Schema::table('messages', function (Blueprint $table) use ($driver) {
            if (!$this->indexExists('messages', 'idx_messages_conversation_id', $driver)) {
                $table->index('conversation_id', 'idx_messages_conversation_id');
            }
            if (!$this->indexExists('messages', 'idx_messages_user_id', $driver)) {
                $table->index('user_id', 'idx_messages_user_id');
            }
            if (!$this->indexExists('messages', 'idx_messages_conversation_created', $driver)) {
                $table->index(['conversation_id', 'created_at'], 'idx_messages_conversation_created');
            }
        });

        // Conversations table indexes
        Schema::table('conversations', function (Blueprint $table) use ($driver) {
            if (!$this->indexExists('conversations', 'idx_conversations_type', $driver)) {
                $table->index('type', 'idx_conversations_type');
            }
            if (!$this->indexExists('conversations', 'idx_conversations_created_by', $driver)) {
                $table->index('created_by', 'idx_conversations_created_by');
            }
            if (!$this->indexExists('conversations', 'idx_conversations_type_created', $driver)) {
                $table->index(['type', 'created_at'], 'idx_conversations_type_created');
            }
        });

        // Conversation participants table indexes
        Schema::table('conversation_participants', function (Blueprint $table) use ($driver) {
            if (!$this->indexExists('conversation_participants', 'idx_conv_participants_user_id', $driver)) {
                $table->index('user_id', 'idx_conv_participants_user_id');
            }
            if (!$this->indexExists('conversation_participants', 'idx_conv_participants_conv_user', $driver)) {
                $table->index(['conversation_id', 'user_id'], 'idx_conv_participants_conv_user');
            }
        });

        // Events table indexes
        Schema::table('events', function (Blueprint $table) use ($driver) {
            if (!$this->indexExists('events', 'idx_events_created_by', $driver)) {
                $table->index('created_by', 'idx_events_created_by');
            }
            if (!$this->indexExists('events', 'idx_events_start_datetime', $driver)) {
                $table->index('start_datetime', 'idx_events_start_datetime');
            }
            if (!$this->indexExists('events', 'idx_events_is_published', $driver)) {
                $table->index('is_published', 'idx_events_is_published');
            }
            if (!$this->indexExists('events', 'idx_events_published_start', $driver)) {
                $table->index(['is_published', 'start_datetime'], 'idx_events_published_start');
            }
        });

        // Polls table indexes
        Schema::table('polls', function (Blueprint $table) use ($driver) {
            if (!$this->indexExists('polls', 'idx_polls_created_by', $driver)) {
                $table->index('created_by', 'idx_polls_created_by');
            }
            if (!$this->indexExists('polls', 'idx_polls_status', $driver)) {
                $table->index('status', 'idx_polls_status');
            }
            if (!$this->indexExists('polls', 'idx_polls_end_date', $driver)) {
                $table->index('end_date', 'idx_polls_end_date');
            }
            if (!$this->indexExists('polls', 'idx_polls_status_end', $driver)) {
                $table->index(['status', 'end_date'], 'idx_polls_status_end');
            }
        });

        // News table indexes
        Schema::table('news', function (Blueprint $table) use ($driver) {
            if (!$this->indexExists('news', 'idx_news_author_id', $driver)) {
                $table->index('author_id', 'idx_news_author_id');
            }
            if (!$this->indexExists('news', 'idx_news_published_at', $driver)) {
                $table->index('published_at', 'idx_news_published_at');
            }
            if (!$this->indexExists('news', 'idx_news_is_published', $driver)) {
                $table->index('is_published', 'idx_news_is_published');
            }
            if (!$this->indexExists('news', 'idx_news_published', $driver)) {
                $table->index(['is_published', 'published_at'], 'idx_news_published');
            }
        });

        // Activity events table indexes (for reporting and analytics)
        Schema::table('activity_events', function (Blueprint $table) use ($driver) {
            if (!$this->indexExists('activity_events', 'idx_activity_events_user_id', $driver)) {
                $table->index('user_id', 'idx_activity_events_user_id');
            }
            if (!$this->indexExists('activity_events', 'idx_activity_events_event_type', $driver)) {
                $table->index('event_type', 'idx_activity_events_event_type');
            }
            if (!$this->indexExists('activity_events', 'idx_activity_events_created_at', $driver)) {
                $table->index('created_at', 'idx_activity_events_created_at');
            }
            if (!$this->indexExists('activity_events', 'idx_activity_events_user_created', $driver)) {
                $table->index(['user_id', 'created_at'], 'idx_activity_events_user_created');
            }
            if (!$this->indexExists('activity_events', 'idx_activity_events_type_created', $driver)) {
                $table->index(['event_type', 'created_at'], 'idx_activity_events_type_created');
            }
        });
    }

    /**
     * Check if an index exists on a table
     */
    private function indexExists(string $table, string $indexName, string $driver): bool
    {
        $connection = Schema::getConnection();

        if ($driver === 'mysql') {
            $indexes = $connection->select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);
            return !empty($indexes);
        } elseif ($driver === 'sqlite') {
            $indexes = $connection->select("SELECT name FROM sqlite_master WHERE type='index' AND name=?", [$indexName]);
            return !empty($indexes);
        } elseif ($driver === 'pgsql') {
            $indexes = $connection->select("SELECT indexname FROM pg_indexes WHERE indexname = ?", [$indexName]);
            return !empty($indexes);
        }

        return false;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: Only drop indexes that this migration created
        // We check existence before dropping to avoid errors

        $driver = DB::connection()->getDriverName();

        // Users table
        Schema::table('users', function (Blueprint $table) use ($driver) {
            if ($this->indexExists('users', 'idx_users_role', $driver)) {
                $table->dropIndex('idx_users_role');
            }
            if ($this->indexExists('users', 'idx_users_centre_id', $driver)) {
                $table->dropIndex('idx_users_centre_id');
            }
            if ($this->indexExists('users', 'idx_users_station_id', $driver)) {
                $table->dropIndex('idx_users_station_id');
            }
            if ($this->indexExists('users', 'idx_users_email_verified_at', $driver)) {
                $table->dropIndex('idx_users_email_verified_at');
            }
            if ($this->indexExists('users', 'idx_users_centre_role', $driver)) {
                $table->dropIndex('idx_users_centre_role');
            }
            if ($this->indexExists('users', 'idx_users_station_role', $driver)) {
                $table->dropIndex('idx_users_station_role');
            }
        });

        // Announcements table
        Schema::table('announcements', function (Blueprint $table) use ($driver) {
            if ($this->indexExists('announcements', 'idx_announcements_created_by', $driver)) {
                $table->dropIndex('idx_announcements_created_by');
            }
            if ($this->indexExists('announcements', 'idx_announcements_target_scope', $driver)) {
                $table->dropIndex('idx_announcements_target_scope');
            }
            if ($this->indexExists('announcements', 'idx_announcements_published_at', $driver)) {
                $table->dropIndex('idx_announcements_published_at');
            }
            if ($this->indexExists('announcements', 'idx_announcements_is_published', $driver)) {
                $table->dropIndex('idx_announcements_is_published');
            }
            if ($this->indexExists('announcements', 'idx_announcements_published', $driver)) {
                $table->dropIndex('idx_announcements_published');
            }
            if ($this->indexExists('announcements', 'idx_announcements_scope_published', $driver)) {
                $table->dropIndex('idx_announcements_scope_published');
            }
        });

        // Documents table
        Schema::table('documents', function (Blueprint $table) use ($driver) {
            if ($this->indexExists('documents', 'idx_documents_uploaded_by', $driver)) {
                $table->dropIndex('idx_documents_uploaded_by');
            }
            if ($this->indexExists('documents', 'idx_documents_category', $driver)) {
                $table->dropIndex('idx_documents_category');
            }
            if ($this->indexExists('documents', 'idx_documents_access_level', $driver)) {
                $table->dropIndex('idx_documents_access_level');
            }
            if ($this->indexExists('documents', 'idx_documents_visibility_scope', $driver)) {
                $table->dropIndex('idx_documents_visibility_scope');
            }
            if ($this->indexExists('documents', 'idx_documents_category_active', $driver)) {
                $table->dropIndex('idx_documents_category_active');
            }
            if ($this->indexExists('documents', 'idx_documents_scope_active', $driver)) {
                $table->dropIndex('idx_documents_scope_active');
            }
        });

        // Messages table
        Schema::table('messages', function (Blueprint $table) use ($driver) {
            if ($this->indexExists('messages', 'idx_messages_conversation_id', $driver)) {
                $table->dropIndex('idx_messages_conversation_id');
            }
            if ($this->indexExists('messages', 'idx_messages_user_id', $driver)) {
                $table->dropIndex('idx_messages_user_id');
            }
            if ($this->indexExists('messages', 'idx_messages_conversation_created', $driver)) {
                $table->dropIndex('idx_messages_conversation_created');
            }
        });

        // Conversations table
        Schema::table('conversations', function (Blueprint $table) use ($driver) {
            if ($this->indexExists('conversations', 'idx_conversations_type', $driver)) {
                $table->dropIndex('idx_conversations_type');
            }
            if ($this->indexExists('conversations', 'idx_conversations_created_by', $driver)) {
                $table->dropIndex('idx_conversations_created_by');
            }
            if ($this->indexExists('conversations', 'idx_conversations_type_created', $driver)) {
                $table->dropIndex('idx_conversations_type_created');
            }
        });

        // Conversation participants table
        Schema::table('conversation_participants', function (Blueprint $table) use ($driver) {
            if ($this->indexExists('conversation_participants', 'idx_conv_participants_user_id', $driver)) {
                $table->dropIndex('idx_conv_participants_user_id');
            }
            if ($this->indexExists('conversation_participants', 'idx_conv_participants_conv_user', $driver)) {
                $table->dropIndex('idx_conv_participants_conv_user');
            }
        });

        // Events table
        Schema::table('events', function (Blueprint $table) use ($driver) {
            if ($this->indexExists('events', 'idx_events_created_by', $driver)) {
                $table->dropIndex('idx_events_created_by');
            }
            if ($this->indexExists('events', 'idx_events_start_datetime', $driver)) {
                $table->dropIndex('idx_events_start_datetime');
            }
            if ($this->indexExists('events', 'idx_events_is_published', $driver)) {
                $table->dropIndex('idx_events_is_published');
            }
            if ($this->indexExists('events', 'idx_events_published_start', $driver)) {
                $table->dropIndex('idx_events_published_start');
            }
        });

        // Polls table
        Schema::table('polls', function (Blueprint $table) use ($driver) {
            if ($this->indexExists('polls', 'idx_polls_created_by', $driver)) {
                $table->dropIndex('idx_polls_created_by');
            }
            if ($this->indexExists('polls', 'idx_polls_status', $driver)) {
                $table->dropIndex('idx_polls_status');
            }
            if ($this->indexExists('polls', 'idx_polls_end_date', $driver)) {
                $table->dropIndex('idx_polls_end_date');
            }
            if ($this->indexExists('polls', 'idx_polls_status_end', $driver)) {
                $table->dropIndex('idx_polls_status_end');
            }
        });

        // News table
        Schema::table('news', function (Blueprint $table) use ($driver) {
            if ($this->indexExists('news', 'idx_news_author_id', $driver)) {
                $table->dropIndex('idx_news_author_id');
            }
            if ($this->indexExists('news', 'idx_news_published_at', $driver)) {
                $table->dropIndex('idx_news_published_at');
            }
            if ($this->indexExists('news', 'idx_news_is_published', $driver)) {
                $table->dropIndex('idx_news_is_published');
            }
            if ($this->indexExists('news', 'idx_news_published', $driver)) {
                $table->dropIndex('idx_news_published');
            }
        });

        // Activity events table
        Schema::table('activity_events', function (Blueprint $table) use ($driver) {
            if ($this->indexExists('activity_events', 'idx_activity_events_user_id', $driver)) {
                $table->dropIndex('idx_activity_events_user_id');
            }
            if ($this->indexExists('activity_events', 'idx_activity_events_event_type', $driver)) {
                $table->dropIndex('idx_activity_events_event_type');
            }
            if ($this->indexExists('activity_events', 'idx_activity_events_created_at', $driver)) {
                $table->dropIndex('idx_activity_events_created_at');
            }
            if ($this->indexExists('activity_events', 'idx_activity_events_user_created', $driver)) {
                $table->dropIndex('idx_activity_events_user_created');
            }
            if ($this->indexExists('activity_events', 'idx_activity_events_type_created', $driver)) {
                $table->dropIndex('idx_activity_events_type_created');
            }
        });
    }
};
