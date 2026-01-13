<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
        if (!Schema::hasColumn('assignments', 'content_upload_id')) {
            $table->unsignedBigInteger('content_upload_id')->nullable()->after('course_id');
            $table->foreign('content_upload_id')
                ->references('id')
                ->on('content_uploads')
                ->onDelete('set null');
        }

        if (!Schema::hasColumn('assignments', 'file_path')) {
            $table->string('file_path')->nullable();
        }
        if (!Schema::hasColumn('assignments', 'file_original_name')) {
            $table->string('file_original_name')->nullable();
        }
        if (!Schema::hasColumn('assignments', 'file_type')) {
            $table->string('file_type')->nullable();
        }
        if (!Schema::hasColumn('assignments', 'file_size')) {
            $table->unsignedBigInteger('file_size')->nullable();
        }
    });
    }

    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            if (Schema::hasColumn('assignments', 'content_upload_id')) {
                $table->dropForeign(['content_upload_id']);
                $table->dropColumn('content_upload_id');
            }

            foreach (['file_path','file_original_name','file_type','file_size'] as $col) {
                if (Schema::hasColumn('assignments', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
