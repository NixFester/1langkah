<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add new roles to options table (order by hierarchy: superadmin > admin > keuangan > marketing > mentor > student)
        $newRoles = [
            ['category' => 'user_role', 'key' => 'superadmin', 'label' => 'Super Admin', 'color' => '#7c3aed', 'sort_order' => 1, 'is_active' => true],
            ['category' => 'user_role', 'key' => 'keuangan', 'label' => 'Keuangan', 'color' => '#f59e0b', 'sort_order' => 3, 'is_active' => true],
            ['category' => 'user_role', 'key' => 'marketing', 'label' => 'Marketing', 'color' => '#ec4899', 'sort_order' => 4, 'is_active' => true],
        ];

        foreach ($newRoles as $role) {
            DB::table('options')->updateOrInsert(
                ['category' => $role['category'], 'key' => $role['key']],
                $role
            );
        }

        // Reorder existing roles to match new hierarchy
        DB::table('options')->where(['category' => 'user_role', 'key' => 'admin'])->update(['sort_order' => 2]);
        DB::table('options')->where(['category' => 'user_role', 'key' => 'mentor'])->update(['sort_order' => 5]);
        DB::table('options')->where(['category' => 'user_role', 'key' => 'student'])->update(['sort_order' => 6]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove new roles
        DB::table('options')->whereIn('key', ['superadmin', 'keuangan', 'marketing'])->where('category', 'user_role')->delete();

        // Restore original order
        DB::table('options')->where(['category' => 'user_role', 'key' => 'admin'])->update(['sort_order' => 3]);
        DB::table('options')->where(['category' => 'user_role', 'key' => 'mentor'])->update(['sort_order' => 2]);
        DB::table('options')->where(['category' => 'user_role', 'key' => 'student'])->update(['sort_order' => 1]);
    }
};
