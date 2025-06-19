<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'        => ['type' => 'INT', 'auto_increment' => true],
            'username' => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'fullname' => ['type' => 'VARCHAR', 'constraint' => 150],
            'email'     => ['type' => 'VARCHAR', 'constraint' => 100],
            'password'  => ['type' => 'VARCHAR', 'constraint' => 255],
            'role'      => ['type' => 'ENUM', 'constraint' => ['customer', 'admin'], 'default' => 'customer'],
            'birth_date' => ['type' => 'DATE', 'null' => true],
            'gender'     => ['type' => 'ENUM', 'constraint' => ['L', 'P'], 'null' => true],
            'address'    => ['type' => 'TEXT', 'null' => true],
            'city'       => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'phone'      => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
