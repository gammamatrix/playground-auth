<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {

            // Primary key

            $table->uuid('id')->primary();

            // IDs

            $table->uuid('created_by_id')->nullable()->index();
            $table->uuid('modified_by_id')->nullable()->index();
            $table->string('user_type')->nullable()->index();

            // Date columns

            $table->timestamps();

            $table->timestamp('email_verified_at')->nullable();
            // $table->dateTime('email_verified_at')->nullable();

            $table->dateTime('banned_at')->nullable();
            $table->dateTime('suspended_at')->nullable();

            $table->softDeletes();

            // Permissions

            $table->bigInteger('gids')->default(0)->unsigned();
            $table->bigInteger('po')->default(0)->unsigned();
            $table->bigInteger('pg')->default(0)->unsigned();
            $table->bigInteger('pw')->default(0)->unsigned();

            $table->rememberToken();

            $table->string('role')->default('');

            // Status

            $table->bigInteger('status')->default(0)->unsigned();
            $table->bigInteger('rank')->default(0);
            $table->bigInteger('size')->default(0);

            // Flags

            $table->boolean('active')->default(1)->index();
            $table->boolean('banned')->default(0)->index();
            $table->boolean('flagged')->default(0)->index();
            $table->boolean('internal')->default(0)->index();
            $table->boolean('locked')->default(0)->index();
            $table->boolean('problem')->default(0)->index();
            $table->boolean('suspended')->default(0)->index();
            $table->boolean('unknown')->default(0)->index();

            // Strings

            $table->string('name')->default('');
            $table->string('email')->unique();
            $table->string('password')->default('');
            $table->string('phone')->nullable();
            $table->string('locale')->default('');
            $table->string('timezone')->default('');

            $table->string('label')->default('');
            $table->string('title')->default('');
            $table->string('byline')->default('');
            $table->string('slug')->nullable()->default(null)->index();

            // A link to the external source of the user.
            $table->string('url')->default('');

            $table->string('description')->default('');

            // The introduction should be the first 255 characters or less of the content.
            // The introduction is visible to the client. No HTML.
            $table->string('introduction')->default('');

            // The HTML content of the user.
            $table->mediumText('content')->nullable();

            // The summary of the content, HTML allowed, to be shown to the client.
            $table->mediumText('summary')->nullable();

            // UI

            $table->string('icon')->default('');
            $table->string('image')->default('');
            $table->string('avatar')->default('');
            $table->json('ui')->nullable()->default(new Expression('(JSON_OBJECT())'));

            $table->json('abilities')
                ->default(new Expression('(JSON_ARRAY())'))
                ->comment('Array of ability strings');
            $table->longText('accounts')
                ->comment('Encrypted user account objects');
            $table->longText('address')
                ->comment('Encrypted user address object');
            $table->longText('contact')
                ->comment('Encrypted contact object');
            $table->longText('meta')
                ->comment('Encrypted meta object');
            $table->longText('notes')
                ->comment('Encrypted array of note objects');
            $table->longText('options')
                ->comment('Encrypted options object');
            $table->longText('registration')
                ->comment('Encrypted registration information object');
            $table->json('roles')
                ->default(new Expression('(JSON_ARRAY())'))
                ->comment('Array of role strings');
            $table->json('permissions')
                ->default(new Expression('(JSON_ARRAY())'))
                ->comment('Array of permission strings');
            $table->json('privileges')
                ->default(new Expression('(JSON_ARRAY())'))
                ->comment('Array of privilege strings');
            $table->longText('sources')
                ->comment('Encrypted array of sources');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
