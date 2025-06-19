<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrdersAndItems extends Migration
{
    public function up()
    {
        // Orders
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true],
            'user_id' => ['type' => 'INT'],
            'total_amount' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'status' => ['type' => 'ENUM', 'constraint' => ['pending', 'paid', 'shipped', 'cancelled'], 'default' => 'pending'],
            'payment_method' => ['type' => 'ENUM', 'constraint' => ['xendit', 'cod']],
            'city' => ['type' => 'VARCHAR', 'constraint' => 100],

            // Kolom transaksi
            'transaction_id' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'payment_gateway' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'payment_status' => ['type' => 'ENUM', 'constraint' => ['pending', 'success', 'failed'], 'default' => 'pending'],

            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('orders');

        // Order Items
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true],
            'order_id' => ['type' => 'INT'],
            'product_id' => ['type' => 'INT'],
            'quantity' => ['type' => 'INT'],
            'price' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('order_id', 'orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('order_items');
    }

    public function down()
    {
        $this->forge->dropTable('order_items');
        $this->forge->dropTable('orders');
    }
}
