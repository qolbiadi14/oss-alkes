<?php

namespace App\Controllers;

use App\Models\OrderModel;
use CodeIgniter\RESTful\ResourceController;

class Midtrans extends ResourceController
{
    /**
     * Endpoint untuk menerima notifikasi pembayaran dari Midtrans
     * dan update status order serta payment_gateway.
     */
    public function notification()
    {
        $json = $this->request->getJSON(true);
        if (!$json || !isset($json['order_id'])) {
            return $this->fail('Invalid notification', 400);
        }

        $orderModel = new OrderModel();
        $order = $orderModel->where('order_number', $json['order_id'])->first();
        if (!$order) {
            return $this->failNotFound('Order not found');
        }

        // Ambil channel pembayaran dari notifikasi
        $paymentGateway = $json['payment_type'] ?? 'unknown';
        $transactionStatus = $json['transaction_status'] ?? '';
        $paymentStatus = 'pending';
        $orderStatus = 'pending';
        if ($transactionStatus === 'settlement' || $transactionStatus === 'capture') {
            $paymentStatus = 'success';
            $orderStatus = 'paid';
        } elseif ($transactionStatus === 'pending') {
            $paymentStatus = 'pending';
            $orderStatus = 'pending';
        } elseif ($transactionStatus === 'expire' || $transactionStatus === 'cancel') {
            $paymentStatus = 'failed';
            $orderStatus = 'cancelled';
        }

        $orderModel->update($order['id'], [
            'payment_gateway' => $paymentGateway,
            'payment_status' => $paymentStatus,
            'status' => $orderStatus,
        ]);

        return $this->respond(['message' => 'Notification processed']);
    }
}
