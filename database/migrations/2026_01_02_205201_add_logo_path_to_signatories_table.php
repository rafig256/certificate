<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('signatories', function (Blueprint $table) {

            $table
                ->string('logo_path')
                ->nullable()
                ->after('phone');

            $table
                ->string('sign_path')
                ->nullable()
                ->after('logo_path');

            $table
                ->string('responsible')
                ->nullable()
                ->after('user_id')
                ->comment('Name of the person responsible');

            // unique indexes (explicit names)
            $table->unique('logo_path', 'signatories_logo_path_unique');
            $table->unique('sign_path', 'signatories_sign_path_unique');
        });
    }

    public function down(): void
    {
        Schema::table('signatories', function (Blueprint $table) {

            // drop indexes first
            $table->dropUnique('signatories_logo_path_unique');
            $table->dropUnique('signatories_sign_path_unique');

            // then drop columns
            $table->dropColumn([
                'logo_path',
                'sign_path',
                'responsible',
            ]);
        });
    }
};
