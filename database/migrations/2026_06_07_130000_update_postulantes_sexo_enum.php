<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('postulantes')) {
            return;
        }

        $driver = DB::connection()->getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE "postulantes" ALTER COLUMN "sexo" TYPE varchar(255);');
            DB::statement('ALTER TABLE "postulantes" DROP CONSTRAINT IF EXISTS postulantes_sexo_check;');
            DB::statement('ALTER TABLE "postulantes" ADD CONSTRAINT postulantes_sexo_check CHECK ("sexo" IN (\'M\', \'F\', \'O\'));');
        } else {
            Schema::table('postulantes', function (Blueprint $table) {
                $table->enum('sexo', ['M', 'F', 'O'])->change();
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('postulantes')) {
            return;
        }

        $driver = DB::connection()->getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE "postulantes" ALTER COLUMN "sexo" TYPE varchar(255);');
            DB::statement('ALTER TABLE "postulantes" DROP CONSTRAINT IF EXISTS postulantes_sexo_check;');
            DB::statement('ALTER TABLE "postulantes" ADD CONSTRAINT postulantes_sexo_check CHECK ("sexo" IN (\'M\', \'F\'));');
        } else {
            Schema::table('postulantes', function (Blueprint $table) {
                $table->enum('sexo', ['M', 'F'])->change();
            });
        }
    }
};
