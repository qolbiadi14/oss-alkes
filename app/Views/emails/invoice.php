<h2>Invoice Order #<?= esc($order['order_number']) ?></h2>
<p>Halo <?= esc($customer['fullname'] ?? $customer['username']) ?>,</p>
<p>Terima kasih telah melakukan pemesanan. Berikut detail pesanan Anda:</p>
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>No.</th>
            <th>Nama Barang</th>
            <th>Jumlah</th>
            <th>Harga Satuan</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1;
        foreach ($items as $item): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= esc($item['product_name']) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td>Rp. <?= number_format($item['price'], 0, ',', '.') ?></td>
                <td>Rp. <?= number_format($item['quantity'] * $item['price'], 0, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<p><strong>Total: Rp. <?= number_format($order['total_amount'], 0, ',', '.') ?></strong></p>
<p>Status: <?= esc(ucfirst($order['status'])) ?></p>
<p>Terima kasih telah berbelanja di toko kami.</p>