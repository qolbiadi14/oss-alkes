<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStoreProfile extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'unsigned' => true],
            'user_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => false
            ],
            'name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'address' => ['type' => 'TEXT'],
            'city_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => false
            ],
            'phone' => ['type' => 'VARCHAR', 'constraint' => 20],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected', 'suspended'],
                'default' => 'pending'
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('city_id', 'cities', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('store_profiles');
    }

    public function down()
    {
        $this->forge->dropTable('store_profiles');
    }
}
