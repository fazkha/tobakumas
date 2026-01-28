<?php

use App\Http\Controllers\AreaOfficerController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\BrandivjabController;
use App\Http\Controllers\BrandivjabkecController;
use App\Http\Controllers\BrandivjabpegController;
use App\Http\Controllers\CoaController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DeliveryOfficerController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KabupatenController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\KonversiController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\ProdOrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropinsiController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\PurchasePlanController;
use App\Http\Controllers\PurchaseReceiptController;
use App\Http\Controllers\QRController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\SaleInvoiceController;
use App\Http\Controllers\SaleOrderController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\StockOpnameController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

Route::get('locale/{lang}', [LocaleController::class, 'setLocale']);

Route::get('documents/{filename}', [PdfController::class, 'show']);

Route::prefix('admin')->get('dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Role & Permission
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::resource('users', UserController::class);
    Route::get('users/{user}/delete', [UserController::class, 'delete'])->name('users.delete');
    Route::get('users/fetchdb/{pp}/{isactive}/{name}/{email}', [UserController::class, 'fetchdb'])->defaults('name', '_')->defaults('email', '_');
})->missing(function (Request $request) {
    return Redirect::route('dashboard');
});
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('roles/create-permission/{newpermission}', [RoleController::class, 'createPermission'])->name('roles.create-permission');
    Route::get('roles/init-permission', [RoleController::class, 'initPermission'])->name('roles.init-permission');
    Route::resource('roles', RoleController::class);
    Route::get('roles/{role}/delete', [RoleController::class, 'delete'])->name('roles.delete');
    Route::get('roles/fetchdb/{pp}/{isactive}/{name}', [RoleController::class, 'fetchdb'])->defaults('name', '_');
})->missing(function (Request $request) {
    return Redirect::route('dashboard');
});
// End of Role & Permission

Route::prefix('purchase')->middleware('auth')->group(function () {
    Route::resource('supplier', SupplierController::class);
    Route::get('supplier/{supplier}/delete', [SupplierController::class, 'delete'])->name('supplier.delete');
    Route::get('supplier/fetchdb/{pp}/{isactive}/{kode}/{nama}/{alamat}', [SupplierController::class, 'fetchdb'])->defaults('kode', '_')->defaults('nama', '_')->defaults('alamat', '_');
    Route::get('supplier/get-code/{id}/{tahun}/{bulan}', [SupplierController::class, 'getCode'])->defaults('tahun', '_')->defaults('bulan', '_');

    Route::resource('order', PurchaseOrderController::class)->names('purchase-order');
    Route::get('order/{order}/delete', [PurchaseOrderController::class, 'delete'])->name('purchase-order.delete');
    Route::get('order/fetchdb/{pp}/{isactive}/{tunai}/{supplier}/{no_order}/{tanggal}', [PurchaseOrderController::class, 'fetchdb'])->defaults('no_order', '_')->defaults('tanggal', '_');
    Route::post('order/store-detail/{detail}', [PurchaseOrderController::class, 'storeDetail']);
    Route::delete('order/delete-detail/{detail}', [PurchaseOrderController::class, 'deleteDetail']);
    Route::get('order/update-detail/{id}/{field}/{nilai}', [PurchaseOrderController::class, 'updateDetail']);

    Route::resource('plan', PurchasePlanController::class)->names('purchase-plan');
    Route::get('plan/{plan}/delete', [PurchasePlanController::class, 'delete'])->name('purchase-plan.delete');
    Route::get('plan/fetchdb/{pp}/{isactive}/{supplier}/{bulan}/{tahun}', [PurchasePlanController::class, 'fetchdb'])->defaults('tahun', '_');
    Route::post('plan/store-detail/{detail}', [PurchasePlanController::class, 'storeDetail']);
    Route::delete('plan/delete-detail/{detail}', [PurchasePlanController::class, 'deleteDetail']);
})->missing(function (Request $request) {
    return Redirect::route('dashboard');
});

Route::prefix('delivery')->middleware('auth')->group(function () {
    Route::resource('order', DeliveryOfficerController::class)->names('delivery-order');
    Route::get('order/fetchdb/{pp}/{isdone}/{propinsi}/{kabupaten}', [DeliveryOfficerController::class, 'fetchdb']);
    Route::get('order/{order}/delete', [DeliveryOfficerController::class, 'delete'])->name('delivery-order.delete');
    Route::post('order/store-package/{package}', [DeliveryOfficerController::class, 'storePackage']);
    Route::delete('order/delete-package/{package}', [DeliveryOfficerController::class, 'deletePackage']);
    Route::get('order/print-one/{year}/{month}/{id}', [DeliveryOfficerController::class, 'printOne']);
    Route::get('order/print-rekap/{year}/{month}', [DeliveryOfficerController::class, 'printRekap']);

    Route::resource('officer', AreaOfficerController::class)->names('area-officer');
    Route::get('officer/fetchdb/{pp}/{isactive}/{propinsi}/{kabupaten}', [AreaOfficerController::class, 'fetchdb']);
    Route::get('officer/{officer}/delete', [AreaOfficerController::class, 'delete'])->name('area-officer.delete');
    Route::post('officer/updateDetail/{officer}', [AreaOfficerController::class, 'updateDetail'])->name('area-officer.updateDetail');
})->missing(function (Request $request) {
    return Redirect::route('dashboard');
});

Route::prefix('human-resource')->middleware('auth')->group(function () {
    Route::resource('employee', PegawaiController::class);
    Route::get('employee/{employee}/delete', [PegawaiController::class, 'delete'])->name('employee.delete');
    Route::get('employee/fetchdb/{pp}/{isactive}/{kelamin}/{nama_lengkap}/{alamat_tinggal}/{telpon}/{cabang}/{jabatan}', [PegawaiController::class, 'fetchdb'])->defaults('nama_lengkap', '_')->defaults('alamat_tinggal', '_')->defaults('telpon', '_');

    Route::post('employee/store-jabatan/{employee}', [BrandivjabpegController::class, 'storeJabatan']);
    Route::delete('employee/delete-jabatan/{jabatan}', [BrandivjabpegController::class, 'deleteJabatan']);

    Route::resource('mitra', MitraController::class);
    Route::get('mitra/{mitra}/delete', [MitraController::class, 'delete'])->name('mitra.delete');
    Route::get('mitra/fetchdb/{pp}/{isactive}/{kelamin}/{nama_lengkap}/{alamat_tinggal}/{telpon}/{cabang}/{jabatan}', [MitraController::class, 'fetchdb'])->defaults('nama_lengkap', '_')->defaults('alamat_tinggal', '_')->defaults('telpon', '_');

    Route::post('mitra/store-jabatan/{mitra}', [BrandivjabpegController::class, 'storeJabatan']);
    Route::delete('mitra/delete-jabatan/{jabatan}', [BrandivjabpegController::class, 'deleteJabatan']);

    Route::resource('pengumuman', PengumumanController::class);
})->missing(function (Request $request) {
    return Redirect::route('dashboard');
});

Route::prefix('marketing')->middleware('auth')->group(function () {
    Route::resource('propinsi', PropinsiController::class);
    Route::get('propinsi/{propinsi}/delete', [PropinsiController::class, 'delete'])->name('propinsi.delete');
    Route::get('propinsi/fetchdb/{pp}/{isactive}/{nama}', [PropinsiController::class, 'fetchdb'])->defaults('nama', '_');

    Route::resource('kabupaten', KabupatenController::class);
    Route::get('kabupaten/{kabupaten}/delete', [KabupatenController::class, 'delete'])->name('kabupaten.delete');
    Route::get('kabupaten/fetchdb/{pp}/{isactive}/{nama}', [KabupatenController::class, 'fetchdb'])->defaults('nama', '_');

    Route::resource('kecamatan', KecamatanController::class);
    Route::get('kecamatan/{kecamatan}/delete', [KecamatanController::class, 'delete'])->name('kecamatan.delete');
    Route::get('kecamatan/fetchdb/{pp}/{isactive}/{nama}', [KecamatanController::class, 'fetchdb'])->defaults('nama', '_');
    Route::get('kecamatan/depend-drop-kab/{pr}', [KecamatanController::class, 'dependDropKab'])->defaults('pr', '_');
    Route::get('kecamatan/depend-drop-kec/{pr}/{kb}', [KecamatanController::class, 'dependDropKec'])->defaults('pr', '_')->defaults('kb', '_');

    Route::resource('brandivjabkec', BrandivjabkecController::class);
    Route::get('brandivjabkec/{brandivjabkec}/delete', [BrandivjabkecController::class, 'delete'])->name('brandivjabkec.delete');
    Route::get('brandivjabkec/fetchdb/{pp}/{isactive}/{propinsi}/{kabupaten}', [BrandivjabkecController::class, 'fetchdb']);
    Route::post('brandivjabkec/updateDetail/{brandivjabkec}', [BrandivjabkecController::class, 'updateDetail'])->name('brandivjabkec.updateDetail');
})->missing(function (Request $request) {
    return Redirect::route('dashboard');
});

Route::prefix('general-affair')->middleware('auth')->group(function () {
    Route::resource('branch', BranchController::class);
    Route::get('branch/{branch}/delete', [BranchController::class, 'delete'])->name('branch.delete');
    Route::get('branch/fetchdb/{pp}/{isactive}/{nama}/{alamat}', [BranchController::class, 'fetchdb'])->defaults('nama', '_')->defaults('alamat', '_');
    Route::get('branch/get-attribute/{id}', [BranchController::class, 'getAttribute'])->defaults('id', '_');

    Route::resource('division', DivisionController::class);
    Route::get('division/{division}/delete', [DivisionController::class, 'delete'])->name('division.delete');
    Route::get('division/fetchdb/{pp}/{isactive}/{nama}', [DivisionController::class, 'fetchdb'])->defaults('nama', '_');

    Route::resource('jabatan', JabatanController::class);
    Route::get('jabatan/{jabatan}/delete', [JabatanController::class, 'delete'])->name('jabatan.delete');
    Route::get('jabatan/fetchdb/{pp}/{isactive}/{nama}', [JabatanController::class, 'fetchdb'])->defaults('nama', '_');

    Route::resource('brandivjab', BrandivjabController::class);
    Route::get('brandivjab/{brandivjab}/delete', [BrandivjabController::class, 'delete'])->name('brandivjab.delete');
    Route::get('brandivjab/fetchdb/{pp}/{isactive}/{branch}/{division}/{jabatan}', [BrandivjabController::class, 'fetchdb']);
})->missing(function (Request $request) {
    return Redirect::route('dashboard');
});

Route::prefix('production')->middleware('auth')->group(function () {
    Route::resource('recipe', RecipeController::class);
    Route::get('recipe/{recipe}/delete', [RecipeController::class, 'delete'])->name('recipe.delete');
    Route::get('recipe/fetchdb/{pp}/{isactive}/{judul}', [RecipeController::class, 'fetchdb'])->defaults('judul', '_');
    Route::post('recipe/store-detail/{recipe}', [RecipeController::class, 'storeDetail']);
    Route::delete('recipe/delete-detail/{recipe}', [RecipeController::class, 'deleteDetail']);
    Route::post('recipe/store-ingoods/{recipe}', [RecipeController::class, 'storeIngoods']);
    Route::delete('recipe/delete-ingoods/{recipe}', [RecipeController::class, 'deleteIngoods']);
    Route::post('recipe/store-outgoods/{recipe}', [RecipeController::class, 'storeOutgoods']);
    Route::delete('recipe/delete-outgoods/{recipe}', [RecipeController::class, 'deleteOutgoods']);
    Route::get('recipe/import-from/{from}/{to}', [RecipeController::class, 'importFrom']);

    Route::resource('order', ProdOrderController::class)->names('production-order');
    Route::get('order/{order}/delete', [ProdOrderController::class, 'delete'])->name('production-order.delete');
    Route::get('order/fetchdb/{pp}/{pr}/{tanggal}/{nomor}/{bulan}/{tahun}/{customer}', [ProdOrderController::class, 'fetchdb'])->defaults('tanggal', '_')->defaults('nomor', '_')->defaults('tahun', '_');
    Route::get('order/combine/{order}/{join}', [ProdOrderController::class, 'combineJoin']);
    Route::get('order/hitung-bahanbaku-produksi/{order}', [ProdOrderController::class, 'hitungBahanbakuProduksi']);
    Route::put('order/finish-order/{order}', [ProdOrderController::class, 'finishOrder']);
    Route::post('order/print-rekap', [ProdOrderController::class, 'PrintRekap']);
    Route::get('order/print-one/{year}/{month}/{id}', [ProdOrderController::class, 'PrintOne']);
})->missing(function (Request $request) {
    return Redirect::route('dashboard');
});

Route::prefix('sale')->middleware('auth')->group(function () {
    Route::resource('customer', CustomerController::class);
    Route::get('customer/{customer}/delete', [CustomerController::class, 'delete'])->name('customer.delete');
    Route::get('customer/fetchdb/{pp}/{isactive}/{group}/{kode}/{nama}/{alamat}/{telpon}/{kontak}', [CustomerController::class, 'fetchdb'])->defaults('kode', '_')->defaults('nama', '_')->defaults('alamat', '_')->defaults('telpon', '_')->defaults('kontak', '_');
    Route::get('customer/get-code/{id}/{tahun}/{bulan}', [CustomerController::class, 'getCode'])->defaults('tahun', '_')->defaults('bulan', '_');

    Route::resource('order', SaleOrderController::class)->names('sale-order');
    Route::get('order/{order}/delete', [SaleOrderController::class, 'delete'])->name('sale-order.delete');
    Route::get('order/fetchdb/{pp}/{isactive}/{tunai}/{customer}/{no_order}/{tanggal}', [SaleOrderController::class, 'fetchdb'])->defaults('no_order', '_')->defaults('tanggal', '_');
    Route::get('order/{order}/approval', [SaleOrderController::class, 'approval'])->name('sale-order.approval');
    Route::post('order/approved/{detail}/{status}', [SaleOrderController::class, 'updateApproval']);
    Route::post('order/store-detail/{detail}', [SaleOrderController::class, 'storeDetail']);
    Route::delete('order/delete-detail/{detail}', [SaleOrderController::class, 'deleteDetail']);
    Route::post('order/store-adonan/{detail}', [SaleOrderController::class, 'storeAdonan']);
    Route::delete('order/delete-adonan/{detail}', [SaleOrderController::class, 'deleteAdonan']);

    Route::get('invoice', [SaleInvoiceController::class, 'index'])->name('sale-invoice.index');
    Route::get('invoice/fetchdb/{pp}/{isactive}/{tunai}/{customer}/{no_order}/{tanggal}', [SaleInvoiceController::class, 'fetchdb'])->defaults('no_order', '_')->defaults('tanggal', '_');
    Route::get('invoice/print/{invoice}', [SaleInvoiceController::class, 'print'])->name('sale-invoice.print');
    Route::post('invoice/print-selected', [SaleInvoiceController::class, 'printSelected'])->name('sale-invoice.print-selected');
})->missing(function (Request $request) {
    return Redirect::route('dashboard');
});

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

    Route::get('goods/print-mutasi/{year}/{month}', [BarangController::class, 'printMutasi']);
    Route::get('goods/print-one-mutasi/{year}/{month}/{id}', [BarangController::class, 'printOneMutasi']);
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

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::resource('coa', CoaController::class);
    Route::get('coa/{coa}/delete', [CoaController::class, 'delete'])->name('coa.delete');
    Route::get('coa/fetchdb/{pp}/{isactive}/{group}/{kode}/{nama}', [CoaController::class, 'fetchdb'])->defaults('group', '_')->defaults('kode', '_')->defaults('nama', '_');
})->missing(function (Request $request) {
    return Redirect::route('dashboard');
});

Route::prefix('admin')->middleware('auth')->group(function () {})->missing(function (Request $request) {
    return Redirect::route('dashboard');
});

Route::prefix('admin')->get('/qrcode', [QRController::class, 'index'])->name('qrcode');

require __DIR__ . '/auth.php';
