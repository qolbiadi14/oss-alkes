<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'        => ['type' => 'INT', 'auto_increment' => true, 'unsigned' => true],
            'username' => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'fullname' => ['type' => 'VARCHAR', 'constraint' => 150],
            'email'     => ['type' => 'VARCHAR', 'constraint' => 100],
            'password'  => ['type' => 'VARCHAR', 'constraint' => 255],
            'role'      => ['type' => 'ENUM', 'constraint' => ['customer', 'admin','vendor'], 'default' => 'customer'],
            'birth_date' => ['type' => 'DATE', 'null' => true],
            'gender'     => ['type' => 'ENUM', 'constraint' => ['L', 'P'], 'null' => true],
            'address'    => ['type' => 'TEXT', 'null' => true],
            'city_id'    => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true
            ],
            'phone'      => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'status'     => ['type' => 'ENUM', 'constraint' => ['active', 'inactive'], 'default' => 'active'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('city_id', 'cities', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
