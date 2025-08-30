<?php

return [
    // app version
    'version' => '0.1.5',

    // company name
    'company_name' => 'Laris Manis Group',

    // Product name
    'product_short' => 'Tobakumas',
    'product_name' => 'Toko Bahan Baku & Kemasan',

    // always update unit price on purchase
    'selaluUpdateHargaBeli' => true,
    'selaluUpdateHargaJual' => true,

    // purchase order
    'po_prefix' => 'PO',

    // sales order
    'so_prefix' => 'SO',

    // pajak
    'purchase_tax_enable' => false,
    'purchase_tax' => 0.10,
    'sale_tax_enable' => false,
    'sale_tax' => 0.10,

    // maksimum panjang text pada tabel
    'table_max_text_length' => 50,

    // perlu/tidak adanya persetujuan transaksi
    'sale_approval' => false,
    'purchase_approval' => false,
    'stockopname_approval' => true,

    // tingkat approval di master/detail
    'approval_level' => 'detail',

    // operator konversi
    'nilai_tambah' => '1',
    'nilai_kurang' => '2',
    'nilai_bagi' => '3',
    'nilai_kali' => '4',
    'simbol_tambah' => '➕',
    'simbol_kurang' => '➖',
    'simbol_bagi' => '➗',
    'simbol_kali' => '✖️',
];
