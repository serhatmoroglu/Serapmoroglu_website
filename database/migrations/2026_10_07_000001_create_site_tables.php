<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('must_change_password')->default(false);
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->longText('value')->nullable();
        });

        Schema::create('treatments', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('group', 30)->default('tedavi'); // cerrahi | protez | tedavi
            $table->string('summary')->nullable();
            $table->longText('body')->nullable();
            $table->string('image')->nullable();
            $table->string('meta_description', 300)->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('published')->default(true);
            $table->timestamps();
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('kind', 20)->index(); // yazi | gazete | video
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('excerpt')->nullable();
            $table->longText('body')->nullable();
            $table->string('image')->nullable();
            $table->string('external_url', 500)->nullable();
            $table->string('video_url', 500)->nullable();
            $table->date('published_at')->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('published')->default(true);
            $table->timestamps();
        });

        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('type', 20)->index(); // arayin | randevu | iletisim
            $table->string('name');
            $table->string('phone', 40)->nullable();
            $table->string('email')->nullable();
            $table->string('subject')->nullable();
            $table->text('message')->nullable();
            $table->string('page')->nullable();
            $table->string('status', 20)->default('yeni')->index(); // yeni | arandi | kapandi
            $table->text('note')->nullable();
            $table->string('ip', 45)->nullable();
            $table->boolean('mail_sent')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('treatments');
        Schema::dropIfExists('settings');
        Schema::table('users', fn (Blueprint $t) => $t->dropColumn('must_change_password'));
    }
};
