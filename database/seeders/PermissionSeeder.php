<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $perm1 = new Permission();
        $perm1->name = 'create_content';
        $perm1->description = 'Создание публикаций';
        $perm1->save();

        $perm2 = new Permission();
        $perm2->name = 'update_content';
        $perm2->description = 'Редактирование публикаций';
        $perm2->save();

        $perm3 = new Permission();
        $perm3->name = 'delete_content';
        $perm3->description = 'Удаление публикаций';
        $perm3->save();

        $perm4 = new Permission();
        $perm4->name = 'add_user';
        $perm4->description = 'Добавление новых пользователей';
        $perm4->save();

        $perm5 = new Permission();
        $perm5->name = 'delete_user';
        $perm5->description = 'Удаление новых пользователей';
        $perm5->save();

        $perm6 = new Permission();
        $perm6->name = 'update_permissions';
        $perm6->description = 'Редактирование разрешений пользователей';
        $perm6->save();
    }
}
