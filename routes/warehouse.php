<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\KonversiController;
use App\Http\Controllers\PurchaseReceiptController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\StockOpnameController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

Route::prefix('warehouse')->middleware('auth')->group(function () {
    Route::resource('units', SatuanController::class);
    Route::get('units/{unit}/delete', [SatuanController::class, 'delete'])->name('units.delete');
    Route::get('units/fetchdb/{pp}/{isactive}/{singkatan}/{nama_lengkap}', [SatuanController::class, 'fetchdb'])->defaults('singkatan', '_')->defaults('nama_lengkap', '_');

    Route::resource('conversions', KonversiController::class);
    Route::get('conversions/{conversion}/delete', [KonversiController::class, 'delete'])->name('conversions.delete');
    Route::get('conversions/fetchdb/{pp}/{isactive}', [KonversiController::class, 'fetchdb']);

    Route::resource('gudang', GudangController::class);
    Route::get('gudang/{gudang}/delete', [GudangController::class, 'delete'])->name('gudang.delete');
    Route::get('gudang/fetchdb/{pp}/{isactive}/{kode}/{nama}/{alamat}', [GudangController::class, 'fetchdb'])->defaults('kode', '_')->defaults('nama', '_')->defaults('alamat', '_');

    Route::get('goods/print-mutasi', [BarangController::class, 'printMutasi']);
    Route::get('goods/print-one-mutasi/{id}', [BarangController::class, 'printOneMutasi']);
    Route::resource('goods', BarangController::class);
    Route::get('goods/{good}/delete', [BarangController::class, 'delete'])->name('goods.delete');
    Route::get('goods/fetchdb/{pp}/{isactive}/{satuan}/{jenis_barang}/{nama}/{merk}', [BarangController::class, 'fetchdb'])->defaults('nama', '_')->defaults('merk', '_');
    Route::get('goods/get-goods-buy/{id}', [BarangController::class, 'getGoodsBuy']);
    Route::get('goods/get-goods-sell/{id}', [BarangController::class, 'getGoodsSell']);
    Route::get('goods/get-goods-stock/{id}', [BarangController::class, 'getGoodsStock']);

    Route::resource('stock-opname', StockOpnameController::class);
    Route::get('stock-opname/{stock_opname}/delete', [StockOpnameController::class, 'delete'])->name('stock-opname.delete');
    Route::get('stock-opname/{stock_opname}/print', [StockOpnameController::class, 'print'])->name('stock-opname.print');
    Route::get('stock-opname/fetchdb/{pp}/{gudang}/{tanggal}', [StockOpnameController::class, 'fetchdb'])->defaults('tanggal', '_');
    Route::post('stock-opname/store-detail/{detail}', [StockOpnameController::class, 'storeDetail']);
    Route::delete('stock-opname/delete-detail/{detail}', [StockOpnameController::class, 'deleteDetail']);

    Route::resource('stock-adjustment', StockAdjustmentController::class);
    Route::get('stock-adjustment/{stock_adjustment}/print', [StockAdjustmentController::class, 'print'])->name('stock-adjustment.print');
    Route::get('stock-adjustment/fetchdb/{pp}/{gudang}/{tanggal}', [StockAdjustmentController::class, 'fetchdb'])->defaults('tanggal', '_');
    Route::post('stock-adjustment/update-detail/{detail}', [StockAdjustmentController::class, 'updateDetail']);

    Route::resource('purchase-receipt', PurchaseReceiptController::class)->names('purchase-receipt');
    Route::get('purchase-receipt/fetchdb/{pp}/{isactive}/{tunai}/{supplier}/{no_order}/{tanggal}', [PurchaseReceiptController::class, 'fetchdb'])->defaults('no_order', '_')->defaults('tanggal', '_');
    Route::post('purchase-receipt/update-detail/{detail}', [PurchaseReceiptController::class, 'updateDetail']);
})->missing(function (Request $request) {
    return Redirect::route('dashboard');
});
