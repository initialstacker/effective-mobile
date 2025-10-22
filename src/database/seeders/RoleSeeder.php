<?php declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;

final class RoleSeeder extends Seeder
{
    /**
     * Predefined roles to seed.
     *
     * @var array<int, array{name:string, slug:string}>
     */
    private array $roles = [
        [
            'name' => 'Администратор',
            'slug' => 'admin'
        ],
        [
            'name' => 'Пользователь',
            'slug' => 'user'
        ],
    ];

    /**
     * Run the database seeds to insert roles.
     */
    public function run(): void
    {
        $roles = array_map(
            callback: fn (array $role): array => [
                'name' => $role['name'],
                'slug' => $role['slug'],
                'created_at' => $now = CarbonImmutable::now(),
                'updated_at' => $now
            ],
            array: $this->roles
        );

        DB::table(table: 'roles')->insert(values: $roles);
    }
}
