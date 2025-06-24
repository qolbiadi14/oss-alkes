<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrdersAndItems extends Migration
{
    public function up()
    {
        // Orders
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'unsigned' => true],
            'order_number' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'store_id' => ['type' => 'INT', 'unsigned' => true],
            'user_id' => ['type' => 'INT', 'unsigned' => true],
            'total_amount' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'status' => ['type' => 'ENUM', 'constraint' => ['pending', 'paid', 'ready','shipped', 'arrived', 'finish', 'cancelled'], 'default' => 'pending'],
            'payment_method' => ['type' => 'ENUM', 'constraint' => ['midtrans', 'cod']],

            // Kolom transaksi
            'payment_token' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'payment_gateway' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'payment_status' => ['type' => 'ENUM', 'constraint' => ['pending', 'success', 'failed'], 'default' => 'pending'],
            'paid_at' => ['type' => 'DATETIME', 'null' => true],

            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('orders');

        // Order Items
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'unsigned' => true],
            'order_id' => ['type' => 'INT', 'unsigned' => true],
            'product_id' => ['type' => 'INT', 'unsigned' => true],
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
