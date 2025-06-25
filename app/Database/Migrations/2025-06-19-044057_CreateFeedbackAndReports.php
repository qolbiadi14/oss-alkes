<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFeedbackAndReports extends Migration
{
    public function up()
    {
        // Feedback
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'unsigned' => true],
            'user_id' => ['type' => 'INT', 'unsigned' => true],
            'order_id' => ['type' => 'INT', 'unsigned' => true],
            'product_id' => ['type' => 'INT', 'unsigned' => true],
            'message' => ['type' => 'TEXT'],
            'rating' => ['type' => 'INT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('order_id', 'orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('feedback');
    }

    public function down()
    {
        $this->forge->dropTable('feedback');
    }
}
