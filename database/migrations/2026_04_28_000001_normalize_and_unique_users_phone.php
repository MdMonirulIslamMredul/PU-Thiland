<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_country', 2)->nullable()->after('phone');
        });

        $seenPhones = [];

        DB::table('users')
            ->select('id', 'phone')
            ->orderBy('id')
            ->chunkById(100, function ($users) use (&$seenPhones) {
                foreach ($users as $user) {
                    $normalizedPhone = $this->normalizeAndDetectCountry($user->phone);

                    if ($normalizedPhone === null || isset($seenPhones[$normalizedPhone['e164']])) {
                        DB::table('users')->where('id', $user->id)->update([
                            'phone' => null,
                            'phone_country' => null,
                        ]);
                        continue;
                    }

                    $seenPhones[$normalizedPhone['e164']] = true;

                    DB::table('users')->where('id', $user->id)->update([
                        'phone' => $normalizedPhone['e164'],
                        'phone_country' => $normalizedPhone['country'],
                    ]);
                }
            });

        Schema::table('users', function (Blueprint $table) {
            $table->unique('phone');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['phone']);
            $table->dropColumn('phone_country');
        });
    }

    private function normalizeAndDetectCountry(?string $value): ?array
    {
        if ($value === null) {
            return null;
        }

        $value = trim($value);
        $value = preg_replace('/[\s\-()]/', '', $value) ?? $value;

        if ($value === '') {
            return null;
        }

        if (str_starts_with($value, '+880')) {
            $national = '0' . substr($value, 4);
        } elseif (str_starts_with($value, '+88')) {
            $national = '0' . substr($value, 3);
        } elseif (str_starts_with($value, '880')) {
            $national = '0' . substr($value, 3);
        } elseif (str_starts_with($value, '88')) {
            $national = '0' . substr($value, 2);
        } else {
            $national = $value;
        }

        if (preg_match('/^01\d{9}$/', $national)) {
            return [
                'country' => 'BD',
                'e164' => '+880' . substr($national, 1),
            ];
        }

        if (str_starts_with($value, '+86')) {
            $chinaNational = substr($value, 3);
        } elseif (str_starts_with($value, '86') && strlen($value) >= 13) {
            $chinaNational = substr($value, 2);
        } else {
            $chinaNational = $value;
        }

        if (preg_match('/^1\d{10}$/', $chinaNational)) {
            return [
                'country' => 'CN',
                'e164' => '+86' . $chinaNational,
            ];
        }

        return null;
    }
};
