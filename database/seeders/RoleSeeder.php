<?php

namespace Database\Seeders;

use App\Library\Enumerations\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role as RoleModel;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Role::getInstances() as $key => $value) {
            $role = RoleModel::find($value->value);

            if ($role && $role->name !== $key) {
                throw new Exception(sprintf(
                    'The %s role value mismatches with the id stored in the database.',
                    $key,
                ));
            }

            if (is_null($role)) {
                RoleModel::insert([
                    'id' => $value->value,
                    'name' => $key,
                    'guard_name' => 'api',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
