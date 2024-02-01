<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SsoController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Sso\SsoDataController;
use App\Http\Controllers\Api\AndinApiController;
use App\Http\Controllers\GenerateQrAssetController;
use App\Http\Controllers\User\ScanQrCodeController;
use App\Http\Controllers\User\AssetOpnameController;
use App\Http\Controllers\TestFront\TestingController;
use App\Http\Controllers\User\AssetPengaduanController;
use App\Http\Controllers\Admin\Setting\LokasiController;
use App\Http\Controllers\Admin\Setting\VendorController;
use App\Http\Controllers\User\BahanHabisPakaiController;
use App\Http\Controllers\Admin\Keluhan\KeluhanController;
use App\Http\Controllers\Admin\Services\ServicesController;
use App\Http\Controllers\Admin\History\DepresiasiController;
use App\Http\Controllers\Admin\Setting\KelasAssetController;
use App\Http\Controllers\Admin\Setting\SatuanAssetController;
use App\Http\Controllers\Admin\History\SummaryAssetController;
use App\Http\Controllers\Admin\Setting\SistemConfigController;
use App\Http\Controllers\Admin\Setting\KategoriAssetController;
use App\Http\Controllers\Admin\History\HistoryServiceController;
use App\Http\Controllers\Admin\Setting\KategoriServiceController;
use App\Http\Controllers\Admin\Setting\SatuanInventoriController;
use App\Http\Controllers\Admin\Approval\HistoryApprovalController;
use App\Http\Controllers\Admin\History\HistoryPengaduanController;
use App\Http\Controllers\Admin\ListingAsset\MasterAssetController;
use App\Http\Controllers\Admin\History\HistoryPeminjamanController;
use App\Http\Controllers\Admin\ListingAsset\AssetServiceController;
use App\Http\Controllers\Admin\Setting\KategoriInventoriController;
use App\Http\Controllers\Admin\Setting\GroupKategoriAssetController;
use App\Http\Controllers\Admin\Inventaris\MasterInventarisController;
use App\Http\Controllers\User\AssetController as UserAssetController;
use App\Http\Controllers\Admin\ListingAsset\PemindahanAssetController;
use App\Http\Controllers\Admin\PemutihanAsset\PemutihanAssetController;
use App\Http\Controllers\Admin\History\HistoryBahanHabisPakaiController;
use App\Http\Controllers\Admin\Inventaris\RequestBahanHabisPakaiController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\PengaduanController as UserPengaduanController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\User\Approval\ApprovalController as UserApprovalController;
use App\Http\Controllers\User\AssetServicesController as UserAssetServicesController;
use App\Http\Controllers\Admin\Approval\ApprovalController as AdminApprovalController;
use App\Http\Controllers\User\PemindahanAssetController as UserPemindahanAssetController;
use App\Http\Controllers\User\PeminjamanAssetController as UserPeminjamanAssetController;
use App\Http\Controllers\Admin\Approval\PemutihanController as AdminPemutihanAssetController;
use App\Http\Controllers\Admin\PemutihanAsset\AssetController as AdminAssetPemutihanController;
use App\Http\Controllers\Admin\Approval\PemindahanController as AdminApprovalPemindahanController;
use App\Http\Controllers\Admin\Approval\PeminjamanController as AdminApprovalPeminjamanController;
use App\Http\Controllers\Admin\UserManagement\UserController as AdminUserManagementUserController;
use App\Http\Controllers\Admin\Inventaris\RequestInventoriController as AdminRequestInventoriController;
use App\Http\Controllers\Admin\PeminjamanAsset\PeminjamanAssetController as AdminPeminjamanAssetController;
use App\Http\Controllers\Admin\Approval\RequestInventoriController as AdminApprovalRequestInventoriController;

use App\Http\Controllers\wahyuController as wahyu;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/twa', [wahyu::class, 'wahyu']);

Route::get('/loginSSO', 'SsoUpController@handleToken')->name('sso-up.handle-token');
Route::get('/sso/redirect', [SsoController::class, 'redirectSso'])->name('sso.redirect');
Route::get('/generate-qr-asset', [GenerateQrAssetController::class, 'index'])->name('generate-qr-asset.index');
Route::post('/generate-qr-asset', [GenerateQrAssetController::class, 'generateQrAsset'])->name('generate-qr-asset.post');
Route::get('/generate-qr-asset/queue', [GenerateQrAssetController::class, 'generateQueueQrAsset'])->name('generate-qr-asset.queue');

Route::get('/callback', [SsoController::class, 'callback']);

Route::get('/', 'SsoUpController@handleToken');

Route::get('/login', [LoginController::class, 'loginForm'])->name('login');//->middleware('guest');
Route::post('/login', [LoginController::class, 'loginStore'])->name('login.action');//->middleware('guest');
Route::get('/redirect', [LoginController::class, 'redirect'])->name('login.redirect');//->middleware(['sso_up:web', 'auth']);
Route::post('/logout', [SsoController::class, 'logoutSso'])->name('sso.logout');//->middleware(['sso_up:web', 'auth']);

//Route::group(['prefix' => 'admin', 'middleware' => ['sso_up:web', 'auth', 'role:manager_asset|manager_it|staff_asset|staff_it|admin']], function () {
Route::group(['prefix' => 'admin'], function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/getDaftarApproval', [AdminDashboardController::class, 'getDaftarApproval'])->name('admin.dashboard.approval');
    Route::group(['prefix' => 'summary'], function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'getSummaryDashboard'])->name('admin.get-summary-dashboard');
    });
    Route::group(['prefix' => 'notification'], function () {
        Route::get('/get-data', [AdminDashboardController::class, 'getNotificationData'])->name('admin.notification.get-data');
        Route::get('/count', [AdminDashboardController::class, 'countNotification'])->name('admin.notification.count');
        Route::post('/read-notif', [AdminDashboardController::class, 'readNotification'])->name('admin.notification.read');
    });
    # Approval
    Route::group(['prefix' => 'approval'], function () {
        Route::get('/datatable', [AdminApprovalController::class, 'datatable'])->name('admin.approval.datatable');
        Route::get('/detail/{id}', [AdminApprovalController::class, 'show'])->name('admin.approval.show');
        Route::group(['prefix' => 'peminjaman'], function () {
            Route::get('/', [AdminApprovalPeminjamanController::class, 'index'])->name('admin.approval.peminjaman.index');
            Route::post('/change-status/{id}', [AdminApprovalPeminjamanController::class, 'changeStatusApproval'])->name('admin.approval.peminjaman.change-status');
            Route::post('/change-status-perpanjangan/{id}', [AdminApprovalPeminjamanController::class, 'changeStatusApprovaPerpanjangan'])->name('admin.approval.peminjaman.change-status-perpanjangan');
        });
        Route::group(['prefix' => 'pemutihan'], function () {
            Route::get('/', [AdminPemutihanAssetController::class, 'index'])->name('admin.approval.pemutihan.index');
            Route::post('/change-status/{id}', [AdminPemutihanAssetController::class, 'changeStatusApproval'])->name('admin.approval.pemutihan.change-status');
        });
        Route::group(['prefix' => 'pemindahan'], function () {
            Route::get('/', [AdminApprovalPemindahanController::class, 'index'])->name('admin.approval.pemindahan.index');
            Route::post('/change-status/{id}', [AdminApprovalPemindahanController::class, 'changeStatusApproval'])->name('admin.approval.pemindahan.change-status');
        });
        Route::group(['prefix' => 'request-inventori'], function () {
            Route::get('/', [AdminApprovalRequestInventoriController::class, 'index'])->name('admin.approval.request-inventori.index');
            Route::post('/change-status/{id}', [AdminApprovalRequestInventoriController::class, 'changeStatusApproval'])->name('admin.approval.request-inventori.change-status');
        });
        Route::group(['prefix' => 'history'], function () {
            Route::get('/', [HistoryApprovalController::class, 'index'])->name('admin.approval.history.index');
        });
    });
    # Listing Asset
    Route::group(['prefix' => 'listing-asset'], function () {
        Route::get('/', [MasterAssetController::class, 'index'])->name('admin.listing-asset.index');
        Route::get('/datatable', [MasterAssetController::class, 'datatable'])->name('admin.listing-asset.datatable');
        Route::get('/datatable-report', [MasterAssetController::class, 'datatableReport'])->name('admin.listing-asset.datatable.report');
        Route::post('/store', [MasterAssetController::class, 'store'])->name('admin.listing-asset.store');
        Route::post('/store-twa', [MasterAssetController::class, 'store_twa'])->name('admin.listing-asset.store.twa');
        Route::get('/show/{id}', [MasterAssetController::class, 'show'])->name('admin.listing-asset.show');
        Route::get('/detail/{id}', [MasterAssetController::class, 'detail'])->name('admin.listing-asset.detail');
        Route::post('/update/{id}', [MasterAssetController::class, 'update'])->name('admin.listing-asset.update');
        Route::post('/update-draft/{id}', [MasterAssetController::class, 'updateDraft'])->name('admin.listing-asset.update.draft');
        Route::post('/destroy/{id}', [MasterAssetController::class, 'destroy'])->name('admin.listing-asset.destroy');
        Route::post('/put-to-trash/{id}', [MasterAssetController::class, 'putToTrash'])->name('admin.listing-asset.putToTrash')->middleware('role:admin');
        Route::get('/download-template-import', [MasterAssetController::class, 'downloadTemplateImport'])->name('admin.listing-asset.download-template-import');
        Route::get('/get-all-data-owner-select2', [MasterAssetController::class, 'getDataAllOwnerSelect2'])->name('admin.listing-asset.get-all-data-owner-select2');
        Route::get('/get-all-data-asset-select2', [MasterAssetController::class, 'getDataAllAssetSelect2'])->name('admin.listing-asset.get-all-data-asset-select2');
        Route::get('/get-all-data-unit-kerja-select2', [MasterAssetController::class, 'getDataAllUnitkerjaSelect2'])->name('admin.listing-asset.get-all-data-unit-kerja-select2');
        Route::post('/import-asset-data', [MasterAssetController::class, 'importAssetData'])->name('admin.listing-asset.import-asset-data');
        Route::get('/preview-qr', [MasterAssetController::class, 'previewQr'])->name('admin.listing-asset.preview-qr');
        Route::get('/download-qr', [MasterAssetController::class, 'downloadQr'])->name('admin.listing-asset.download-qr');
        Route::get('/download-qr-all', [MasterAssetController::class, 'downloadZipQr'])->name('admin.listing-asset.download-qr-all');
        Route::get('/print-qr-all', [MasterAssetController::class, 'printAllQr'])->name('admin.listing-asset.print-qr-all');
        Route::get('/get-no-urut-by-kelompok-id/{id}/{id_asset?}', [MasterAssetController::class, 'getMaxValueNoUrutAssetByKelompokId'])->name('admin.listing-asset.get-no-urut-by-kelompok-id');
        Route::get('/get-no-urut-by-kelompok-id-twa/{id}', [MasterAssetController::class, 'getNoUrutTwa'])->name('admin.listing-asset.get-no-urut-by-kelompok-id.twa');

        Route::group(['prefix' => 'draft'], function () {
            Route::get('/', [MasterAssetController::class, 'indexDraft'])->name('admin.listing-asset.draft.index');
            Route::get('/add', [MasterAssetController::class, 'addDraft'])->name('admin.listing-asset.draft.add'); //wahyu tambah
            Route::get('/edit/{id}', [MasterAssetController::class, 'editDraft'])->name('admin.listing-asset.draft.edit'); //wahyu tambah
            Route::post('/checkDuplicateAssetCode', [MasterAssetController::class, 'checkDuplicateAssetCode'])->name('admin.check-duplicate-asset-code'); //wahyu tambah
            Route::get('/get-jenis-aset/{group}', [MasterAssetController::class, 'getJenisAset'])->name('get.jenis.aset'); //wahyu tambah
            Route::post('/delete-many-asset', [MasterAssetController::class, 'deleteManyAsset'])->name('admin.listing-asset.draft.delete-many-asset');
            Route::post('/delete-all-draft-asset', [MasterAssetController::class, 'deleteAllDraftAsset'])->name('admin.listing-asset.draft.delete-all-draft-asset');
            Route::post('/publish-many-asset', [MasterAssetController::class, 'publishManyAsset'])->name('admin.listing-asset.draft.publish-many-asset');
            Route::post('/publish-all-draft-asset', [MasterAssetController::class, 'publishAllDraftAsset'])->name('admin.listing-asset.draft.publish-all-draft-asset');
        });

        # Asset Image
        Route::group(['prefix' => 'asset-image'], function () {
            Route::get('/preview', [MasterAssetController::class, 'previewImage'])->name('admin.listing-asset.image.preview');
            Route::get('/preview-service', [AssetServiceController::class, 'previewImage'])->name('admin.listing-asset.service.image.preview');
            Route::get('/preview-opname', [MasterAssetController::class, 'previewImageOpname'])->name('admin.listing-asset.opname.image.preview');
            Route::get('/preview-pemutihan', [PemutihanAssetController::class, 'editListingAssetShowImg'])->name('admin.listing-asset.pemutihan.image.preview');
            Route::get('/preview-pengaduan', [KeluhanController::class, 'previewImg'])->name('admin.keluhan.image.preview');
        });

        # Service Asset
        Route::group(['prefix' => 'service-asset'], function () {
            Route::post('/store/{id}', [AssetServiceController::class, 'store'])->name('admin.listing-asset.service-asset.store');
            Route::get('/datatable', [AssetServiceController::class, 'datatable'])->name('admin.listing-asset.service-asset.datatable');
            Route::get('/show/{id}', [AssetServiceController::class, 'show'])->name('admin.listing-asset.service-asset.show');
        });

        # Pemindahan Asset
        Route::group(['prefix' => 'pemindahan-asset'], function () {
            Route::post('/store', [PemindahanAssetController::class, 'store'])->name('admin.listing-asset.pemindahan-asset.store');
            Route::get('/datatable', [PemindahanAssetController::class, 'datatable'])->name('admin.listing-asset.pemindahan-asset.datatable');
            Route::get('/show/{id}', [PemindahanAssetController::class, 'show'])->name('admin.listing-asset.pemindahan-asset.show');
            Route::get('/print-bast/{id}', [PemindahanAssetController::class, 'printBast'])->name('admin.listing-asset.pemindahan-asset.print-bast');
        });

        # Log Asset
        Route::group(['prefix' => 'log-asset'], function () {
            Route::get('/datatable', [MasterAssetController::class, 'log_asset_dt'])->name('admin.listing-asset.log-asset.datatable');
        });

        # Image Asset
        Route::group(['prefix' => 'image-asset'], function () {
            Route::get('/datatable', [MasterAssetController::class, 'image_asset_dt'])->name('admin.listing-asset.image-asset.datatable');
            Route::post('/store', [MasterAssetController::class, 'store_image_asset_dt'])->name('admin.listing-asset.image-asset.store');
            Route::get('/detail/{id}', [MasterAssetController::class, 'detail_image_asset_dt'])->name('admin.listing-asset.image-asset.detail');
            Route::post('/update/{id}', [MasterAssetController::class, 'update_image_asset_dt'])->name('admin.listing-asset.image-asset.update');
            Route::post('/delete/{id}', [MasterAssetController::class, 'delete_image_asset_dt'])->name('admin.listing-asset.image-asset.delete');
        });

        # Opname Asset
        Route::group(['prefix' => 'opname-asset'], function () {
            Route::post('/store/{id}', [AssetOpnameController::class, 'store'])->name('admin.listing-asset.opname-asset.store');
        });

        # Log Opname
        Route::group(['prefix' => 'log-opname'], function () {
            Route::get('/datatable', [MasterAssetController::class, 'log_opname_dt'])->name('admin.listing-asset.log-opname.datatable');
            Route::get('/show/{id}', [MasterAssetController::class, 'log_opname_show'])->name('admin.listing-asset.log-opname.show');
        });
    });

    #Report
    Route::group(['prefix' => 'report'], function () {
        Route::group(['prefix' => 'summary-asset'], function () {
            Route::get('/', [SummaryAssetController::class, 'index'])->name('admin.report.summary-asset.index');
            Route::get('/get-summary-overview', [SummaryAssetController::class, 'getSummaryAsset'])->name('admin.report.summary-asset.get-summary-overview');
            Route::get('/download/export', [SummaryAssetController::class, 'download'])->name('admin.report.summary-asset.download-export');
        });
        Route::group(['prefix' => 'depresiasi'], function () {
            Route::get('/', [DepresiasiController::class, 'index'])->name('admin.report.depresiasi.index');
            Route::get('/download/export', [DepresiasiController::class, 'download'])->name('admin.report.depresiasi.download-export');
            Route::get('/datatable', [DepresiasiController::class, 'datatable'])->name('admin.report.depresiasi.datatable');
        });
        Route::group(['prefix' => 'history-pengaduan'], function () {
            Route::get('/', [HistoryPengaduanController::class, 'index'])->name('admin.report.history-pengaduan.index');
            Route::get('/download/export', [HistoryPengaduanController::class, 'download'])->name('admin.report.history-pengaduan.download-export');
            Route::get('/datatable', [HistoryPengaduanController::class, 'datatable'])->name('admin.report.history-pengaduan.datatable');
        });

        Route::group(['prefix' => 'history-peminjaman'], function () {
            Route::get('/', [HistoryPeminjamanController::class, 'index'])->name('admin.report.history-peminjaman.index');
            Route::get('/datatable', [HistoryPeminjamanController::class, 'datatable'])->name('admin.report.history-peminjaman.datatable');
            Route::get('/download/export', [HistoryPeminjamanController::class, 'export'])->name('admin.report.history-peminjaman.download-export');
        });

        Route::group(['prefix' => 'history-service'], function () {
            Route::get('/', [HistoryServiceController::class, 'index'])->name('admin.report.history-service.index');
            Route::get('/download/export', [HistoryServiceController::class, 'download'])->name('admin.report.history-service.download-export');
            Route::get('/datatable', [HistoryServiceController::class, 'datatable'])->name('admin.report.history-service.datatable');
        });

        Route::group(['prefix' => 'history-bahan-habis-pakai'], function () {
            Route::get('/', [HistoryBahanHabisPakaiController::class, 'index'])->name('admin.report.history-bahan-habis-pakai.index');
            Route::get('/download/export', [HistoryBahanHabisPakaiController::class, 'download'])->name('admin.report.history-bahan-habis-pakai.download-export');
            Route::get('/datatable', [HistoryBahanHabisPakaiController::class, 'datatable'])->name('admin.report.history-bahan-habis-pakai.datatable');
        });
    });

    # Pemutihan Asset
    Route::group(['prefix' => 'pemutihan-asset'], function () {
        Route::group(['prefix' => 'bast'], function () {
            Route::get('/', [PemutihanAssetController::class, 'index'])->name('admin.pemutihan-asset.index');
            Route::get('/datatable', [PemutihanAssetController::class, 'datatable'])->name('admin.pemutihan-asset.datatable');
            Route::get('/detail/{id}', [PemutihanAssetController::class, 'detail'])->name('admin.pemutihan-asset.detail');
            Route::get('/show/{id}', [PemutihanAssetController::class, 'show'])->name('admin.pemutihan-asset.show');
            Route::post('/delete/{id}', [PemutihanAssetController::class, 'destroy'])->name('admin.pemutihan-asset.delete');
            Route::get('/datatable-asset', [PemutihanAssetController::class, 'datatableAsset'])->name('admin.pemutihan-asset.datatable.asset');
            Route::get('/datatable-detail', [PemutihanAssetController::class, 'datatableDetail'])->name('admin.pemutihan-asset.datatable.detail');

            # Store Pemutihan Asset
            Route::group(['prefix' => 'store'], function () {
                Route::post('/', [PemutihanAssetController::class, 'store'])->name('admin.pemutihan-asset.store');
                Route::get('/detail/{id}', [PemutihanAssetController::class, 'storeDetail'])->name('admin.pemutihan-asset.store.detail');
                Route::post('/detail/{id}', [PemutihanAssetController::class, 'storeDetailUpdate'])->name('admin.pemutihan-asset.store.detail.update');
                Route::get('/download-berita-acara', [PemutihanAssetController::class, 'downloadBeritaAcara'])->name('admin.pemutihan-asset.store.detail.download');
                Route::post('/detail/cancel/{id}', [PemutihanAssetController::class, 'storeDetailCancel'])->name('admin.pemutihan-asset.store.detail.cancel');
            });

            # Edit Pemutihan Asset
            Route::group(['prefix' => 'edit'], function () {
                Route::get('/{id}', [PemutihanAssetController::class, 'edit'])->name('admin.pemutihan-asset.edit');
                Route::get('/ditolak/{id}', [PemutihanAssetController::class, 'editDitolak'])->name('admin.pemutihan-asset.edit.ditolak');
                Route::post('/update/{id}', [PemutihanAssetController::class, 'update'])->name('admin.pemutihan-asset.update');
                Route::get('/listing-asset/{id}', [PemutihanAssetController::class, 'editListingAsset'])->name('admin.pemutihan-asset.edit.listing-asset');
                Route::post('/listing-asset/update/{id}', [PemutihanAssetController::class, 'editListingAssetUpdate'])->name('admin.pemutihan-asset.edit.listing-asset.update');
                Route::get('/listing-asset/get-image/{id}', [PemutihanAssetController::class, 'editListingAssetGetImg'])->name('admin.pemutihan-asset.edit.listing-asset.get-image');
            });
        });
        Route::group(['prefix' => 'asset'], function () {
            Route::get('/', [AdminAssetPemutihanController::class, 'index'])->name('admin.pemutihan-asset.asset.index');
        });
    });

    # Services
    Route::group(['prefix' => 'services'], function () {
        Route::get('/', [ServicesController::class, 'index'])->name('admin.services.index');
        Route::get('/datatable', [ServicesController::class, 'datatable'])->name('admin.services.datatable');
        Route::get('/datatable-log', [ServicesController::class, 'datatableLog'])->name('admin.services.datatable.log');
        Route::post('/store', [ServicesController::class, 'store'])->name('admin.services.store');
        Route::get('/edit/{id}', [ServicesController::class, 'edit'])->name('admin.services.edit');
        Route::get('/edit-status/{id}', [ServicesController::class, 'editStatus'])->name('admin.services.edit.status');
        Route::get('/detail/{id}', [ServicesController::class, 'detail'])->name('admin.services.detail');
        Route::post('/update/{id}', [ServicesController::class, 'update'])->name('admin.services.update');
        Route::post('/update-status/{id}', [ServicesController::class, 'updateStatus'])->name('admin.services.update.status');
        Route::get('/get-data-chart', [ServicesController::class, 'getDataChartServices'])->name('admin.services.get-data-chart');
        Route::get('/get-data-perencanaan-service', [ServicesController::class, 'getDataPerencanaanService'])->name('admin.services.get-data-perencanaan-service');
        Route::get('/datatable-perencanaan-service', [ServicesController::class, 'datatablePerencanaanServices'])->name('admin.services.datatable-perencanaan-service');
        Route::get('/perencanaan-service/show/{id}', [ServicesController::class, 'findPerencanaanServicesById'])->name('admin.services.find-perencanaan-service');
    });

    # Keluhan
    Route::group(['prefix' => 'keluhan'], function () {
        Route::get('/', [KeluhanController::class, 'index'])->name('admin.keluhan.index');
        Route::get('/edit/{id}', [KeluhanController::class, 'edit'])->name('admin.keluhan.edit');
        Route::get('/detail/{id}', [KeluhanController::class, 'detail'])->name('admin.keluhan.detail');
        Route::post('/update/{id}', [KeluhanController::class, 'update'])->name('admin.keluhan.update');
        Route::get('/image/{id}', [KeluhanController::class, 'getImg'])->name('admin.keluhan.get-image');
        Route::get('/datatable', [KeluhanController::class, 'datatable'])->name('admin.keluhan.datatable');
        Route::get('/datatable-log', [KeluhanController::class, 'datatableLog'])->name('admin.keluhan.datatable.log');
    });
    # Peminjaman
    Route::group(['prefix' => 'peminjaman'], function () {
        Route::get('/', [AdminPeminjamanAssetController::class, 'index'])->name('admin.peminjaman.index');
        Route::get('/datatable', [AdminPeminjamanAssetController::class, 'datatable'])->name('admin.peminjaman.datatable');
        Route::get('/show/{id}', [AdminPeminjamanAssetController::class, 'show'])->name('admin.peminjaman.show');
        Route::get('/detail/{id}', [AdminPeminjamanAssetController::class, 'detail'])->name('admin.peminjaman.detail');
        Route::get('/get-data-select2', [AdminPeminjamanAssetController::class, 'getDataPeminjamSelect2'])->name('admin.peminjaman.get-data-select2');
        Route::group(['prefix' => 'detail-asset'], function () {
            Route::get('/datatable', [AdminPeminjamanAssetController::class, 'detailPeminjamanDatatable'])->name('admin.peminjaman.detail-asset.datatable');
            Route::post('/delete/{id}', [AdminPeminjamanAssetController::class, 'deleteDetailPeminjaman'])->name('admin.peminjaman.detail-asset.delete');
            Route::post('/change-status/{id}', [AdminPeminjamanAssetController::class, 'changeStatus'])->name('admin.peminjaman.detail-asset.change-status');
            Route::post('/store-many', [AdminPeminjamanAssetController::class, 'storeManyDetailPeminjaman'])->name('admin.peminjaman.detail-asset.store-many');
        });
    });

    # Inventaris
    Route::group(['prefix' => 'bahan-habis-pakai'], function () {
        Route::group(['prefix' => 'listing-data'], function () {
            Route::get('/', [MasterInventarisController::class, 'index'])->name('admin.listing-inventaris.index');
            Route::get('/get-one-inventaris', [MasterInventarisController::class, 'getOne'])->name('admin.listing-inventaris.get.one');
            Route::get('/datatable', [MasterInventarisController::class, 'datatable'])->name('admin.listing-inventaris.datatable');
            Route::post('/store', [MasterInventarisController::class, 'store'])->name('admin.listing-inventaris.store');
            Route::post('/store-and-update', [MasterInventarisController::class, 'storeUpdate'])->name('admin.listing-inventaris.store.update');
            Route::get('/edit/{id}', [MasterInventarisController::class, 'edit'])->name('admin.listing-inventaris.edit');
            Route::post('/update/{id}', [MasterInventarisController::class, 'update'])->name('admin.listing-inventaris.update');
            Route::post('/delete/{id}', [MasterInventarisController::class, 'delete'])->name('admin.listing-inventaris.delete')->middleware('role:admin');
            Route::get('/detail/{id}', [MasterInventarisController::class, 'detail'])->name('admin.listing-inventaris.detail');
            Route::post('/update-stok/{id}', [MasterInventarisController::class, 'updateStok'])->name('admin.listing-inventaris.update.stok');
            Route::get('/edit-stok/{id}', [MasterInventarisController::class, 'editStok'])->name('admin.listing-inventaris.edit.stok');
            Route::get('/datatable-penambahan', [MasterInventarisController::class, 'datatablePenambahan'])->name('admin.listing-inventaris.datatable.penambahan');
            Route::get('/datatable-pengurangan', [MasterInventarisController::class, 'datatablePengurangan'])->name('admin.listing-inventaris.datatable.pengurangan');
            Route::get('/get-data-select2', [MasterInventarisController::class, 'getDataSelect2'])->name('admin.listing-inventaris.get-data-select2');
        });
        Route::group(['prefix' => 'permintaan'], function () {
            Route::get('/', [RequestBahanHabisPakaiController::class, 'index'])->name('admin.permintaan-inventaris.index');
            Route::get('/datatable', [RequestBahanHabisPakaiController::class, 'datatable'])->name('admin.permintaan-inventaris.datatable');
            Route::get('/realisasi/{id}', [RequestBahanHabisPakaiController::class, 'realisasi'])->name('admin.permintaan-inventaris.realisasi');
            Route::post('/realisasi/{id}', [RequestBahanHabisPakaiController::class, 'storeRealisasi'])->name('admin.permintaan-inventaris.realisasi.store');
            Route::get('/log-permintaan', [RequestBahanHabisPakaiController::class, 'datatableLog'])->name('admin.permintaan-inventaris.datatable.log');
        });

        Route::group(['prefix' => 'approval-permintaan'], function () {
            Route::get('/', [AdminRequestInventoriController::class, 'index'])->name('admin.listing-inventaris.request-inventori.index');
            Route::get('/datatable', [AdminRequestInventoriController::class, 'datatable'])->name('admin.listing-inventaris.request-inventori.datatable');
            Route::get('/show/{id}', [AdminRequestInventoriController::class, 'show'])->name('admin.listing-inventaris.request-inventori.show');
            Route::post('/update/{id}', [AdminRequestInventoriController::class, 'update'])->name('admin.listing-inventaris.request-inventori.update');
        });
    });
    # User Management
    Route::group(['prefix' => 'user-management'], function () {
        Route::group(['prefix' => 'user'], function () {
            Route::get('/', [AdminUserManagementUserController::class, 'index'])->name('admin.user-management.user.index');
            Route::post('/store', [AdminUserManagementUserController::class, 'store'])->name('admin.user-management.user.store');
            Route::post('/import', [AdminUserManagementUserController::class, 'import'])->name('admin.user-management.user.import');
            Route::get('/download-template-import', [AdminUserManagementUserController::class, 'downloadTemplateImport'])->name('admin.user-management.user.download-template-import');
            Route::get('/show/{id}', [AdminUserManagementUserController::class, 'show'])->name('admin.user-management.user.show');
            Route::post('/update/{id}', [AdminUserManagementUserController::class, 'update'])->name('admin.user-management.user.update');
            Route::post('/delete/{id}', [AdminUserManagementUserController::class, 'delete'])->name('admin.user-management.user.delete');
            Route::post('/change-password/{id}', [AdminUserManagementUserController::class, 'changePassword'])->name('admin.user-management.user.change-password');
            Route::get('/datatable', [AdminUserManagementUserController::class, 'datatable'])->name('admin.user-management.user.datatable');
            Route::get('/get-data-select2', [AdminUserManagementUserController::class, 'getDataUserSelect2'])->name('admin.user-management.user.get-data-select2');
        });
    });

    # Setting
    Route::group(['prefix' => 'setting'], function () {
        // #Sistem Config
        Route::group(['prefix' => 'sistem-config'], function () {
            Route::get('/', [SistemConfigController::class, 'index'])->name('admin.sistem-config.index');
            Route::post('/store', [SistemConfigController::class, 'update'])->name('admin.sistem-config.update');
        });
        // #Lokasi
        Route::group(['prefix' => 'lokasi'], function () {
            Route::get('/', [LokasiController::class, 'index'])->name('admin.setting.lokasi.index');
            Route::post('/store', [LokasiController::class, 'store'])->name('admin.setting.lokasi.store');
            Route::get('/edit/{id}', [LokasiController::class, 'edit'])->name('admin.setting.lokasi.edit');
            Route::post('/update/{id}', [LokasiController::class, 'update'])->name('admin.setting.lokasi.update');
            Route::post('/delete/{id}', [LokasiController::class, 'destroy'])->name('admin.setting.lokasi.delete');
            Route::get('/datatable', [LokasiController::class, 'datatable'])->name('admin.setting.lokasi.datatable');
            Route::get('/get-node-tree', [LokasiController::class, 'getNodeTree'])->name('admin.setting.lokasi.get-node-tree');
            Route::get('/get-select2', [LokasiController::class, 'getAllSelect2'])->name('admin.setting.lokasi.get-select2');
        });
        // #Group-Kategori-Asset
        Route::group(['prefix' => 'group-kategori-asset'], function () {
            Route::get('/', [GroupKategoriAssetController::class, 'index'])->name('admin.setting.group-kategori-asset.index');
            Route::post('/store', [GroupKategoriAssetController::class, 'store'])->name('admin.setting.group-kategori-asset.store');
            Route::get('/edit/{id}', [GroupKategoriAssetController::class, 'edit'])->name('admin.setting.group-kategori-asset.edit');
            Route::post('/update/{id}', [GroupKategoriAssetController::class, 'update'])->name('admin.setting.group-kategori-asset.update');
            Route::post('/delete/{id}', [GroupKategoriAssetController::class, 'destroy'])->name('admin.setting.group-kategori-asset.delete');
            Route::get('/datatable', [GroupKategoriAssetController::class, 'datatable'])->name('admin.setting.group-kategori-asset.datatable');
            Route::get('/find-all', [GroupKategoriAssetController::class, 'findAll'])->name('admin.setting.group-kategori-asset.find-all');
            Route::get('/get-data-select2', [GroupKategoriAssetController::class, 'getDataSelect2'])->name('admin.setting.group-kategori-asset.get-data-select2');
        });
        // #Kategori-Asset
        Route::group(['prefix' => 'kategori-asset'], function () {
            Route::get('/', [KategoriAssetController::class, 'index'])->name('admin.setting.kategori-asset.index');
            Route::post('/store', [KategoriAssetController::class, 'store'])->name('admin.setting.kategori-asset.store');
            Route::get('/edit/{id}', [KategoriAssetController::class, 'edit'])->name('admin.setting.kategori-asset.edit');
            Route::post('/update/{id}', [KategoriAssetController::class, 'update'])->name('admin.setting.kategori-asset.update');
            Route::post('/delete/{id}', [KategoriAssetController::class, 'destroy'])->name('admin.setting.kategori-asset.delete');
            Route::get('/datatable', [KategoriAssetController::class, 'datatable'])->name('admin.setting.kategori-asset.datatable');
            Route::get('/get-data-select2', [KategoriAssetController::class, 'getDataSelect2'])->name('admin.setting.kategori-asset.get-data-select2');
        });
        // # Satuan Asset
        Route::group(['prefix' => 'satuan-asset'], function () {
            Route::get('/', [SatuanAssetController::class, 'index'])->name('admin.setting.satuan-asset.index');
            Route::post('/store', [SatuanAssetController::class, 'store'])->name('admin.setting.satuan-asset.store');
            Route::get('/edit/{id}', [SatuanAssetController::class, 'edit'])->name('admin.setting.satuan-asset.edit');
            Route::post('/update/{id}', [SatuanAssetController::class, 'update'])->name('admin.setting.satuan-asset.update');
            Route::post('/delete/{id}', [SatuanAssetController::class, 'destroy'])->name('admin.setting.satuan-asset.delete');
            Route::get('/datatable', [SatuanAssetController::class, 'datatable'])->name('admin.setting.satuan-asset.datatable');
            Route::get('/get-data-select2', [SatuanAssetController::class, 'getDataSelect2'])->name('admin.setting.satuan-asset.get-data-select2');
        });
        // # Vendor
        Route::group(['prefix' => 'vendor'], function () {
            Route::get('/', [VendorController::class, 'index'])->name('admin.setting.vendor.index');
            Route::post('/store', [VendorController::class, 'store'])->name('admin.setting.vendor.store');
            Route::get('/edit/{id}', [VendorController::class, 'edit'])->name('admin.setting.vendor.edit');
            Route::post('/update/{id}', [VendorController::class, 'update'])->name('admin.setting.vendor.update');
            Route::post('/delete/{id}', [VendorController::class, 'destroy'])->name('admin.setting.vendor.delete');
            Route::get('/datatable', [VendorController::class, 'datatable'])->name('admin.setting.vendor.datatable');
            Route::get('/get-data-select2', [VendorController::class, 'getDataSelect2'])->name('admin.setting.vendor.get-data-select2');
        });
        // #Kategori Inventori
        Route::group(['prefix' => 'kategori-inventori'], function () {
            Route::get('/', [KategoriInventoriController::class, 'index'])->name('admin.setting.kategori-inventori.index');
            Route::post('/store', [KategoriInventoriController::class, 'store'])->name('admin.setting.kategori-inventori.store');
            Route::get('/edit/{id}', [KategoriInventoriController::class, 'edit'])->name('admin.setting.kategori-inventori.edit');
            Route::post('/update/{id}', [KategoriInventoriController::class, 'update'])->name('admin.setting.kategori-inventori.update');
            Route::post('/delete/{id}', [KategoriInventoriController::class, 'destroy'])->name('admin.setting.kategori-inventori.delete');
            Route::get('/datatable', [KategoriInventoriController::class, 'datatable'])->name('admin.setting.kategori-inventori.datatable');
            Route::get('/get-data-select2', [KategoriInventoriController::class, 'getDataSelect2'])->name('admin.setting.kategori-inventori.get-data-select2');
        });
        // # Satuan Inventori
        Route::group(['prefix' => 'satuan-inventori'], function () {
            Route::get('/', [SatuanInventoriController::class, 'index'])->name('admin.setting.satuan-inventori.index');
            Route::post('/store', [SatuanInventoriController::class, 'store'])->name('admin.setting.satuan-inventori.store');
            Route::get('/edit/{id}', [SatuanInventoriController::class, 'edit'])->name('admin.setting.satuan-inventori.edit');
            Route::post('/update/{id}', [SatuanInventoriController::class, 'update'])->name('admin.setting.satuan-inventori.update');
            Route::post('/delete/{id}', [SatuanInventoriController::class, 'destroy'])->name('admin.setting.satuan-inventori.delete');
            Route::get('/datatable', [SatuanInventoriController::class, 'datatable'])->name('admin.setting.satuan-inventori.datatable');
            Route::get('/get-data-select2', [SatuanInventoriController::class, 'getDataSelect2'])->name('admin.setting.satuan-inventori.get-data-select2');
        });
        // # Kelas Asset
        Route::group(['prefix' => 'kelas-asset'], function () {
            Route::get('/', [KelasAssetController::class, 'index'])->name('admin.setting.kelas-asset.index');
            Route::post('/store', [KelasAssetController::class, 'store'])->name('admin.setting.kelas-asset.store');
            Route::get('/edit/{id}', [KelasAssetController::class, 'edit'])->name('admin.setting.kelas-asset.edit');
            Route::post('/update/{id}', [KelasAssetController::class, 'update'])->name('admin.setting.kelas-asset.update');
            Route::post('/delete/{id}', [KelasAssetController::class, 'destroy'])->name('admin.setting.kelas-asset.delete');
            Route::get('/datatable', [KelasAssetController::class, 'datatable'])->name('admin.setting.kelas-asset.datatable');
            Route::get('/get-data-select2', [KelasAssetController::class, 'getDataSelect2'])->name('admin.setting.kelas-asset.get-data-select2');
        });
        // # Kategori Service
        Route::group(['prefix' => 'kategori-service'], function () {
            Route::get('/', [KategoriServiceController::class, 'index'])->name('admin.setting.kategori-service.index');
            Route::post('/store', [KategoriServiceController::class, 'store'])->name('admin.setting.kategori-service.store');
            Route::get('/edit/{id}', [KategoriServiceController::class, 'edit'])->name('admin.setting.kategori-service.edit');
            Route::post('/update/{id}', [KategoriServiceController::class, 'update'])->name('admin.setting.kategori-service.update');
            Route::post('/delete/{id}', [KategoriServiceController::class, 'destroy'])->name('admin.setting.kategori-service.delete');
            Route::get('/datatable', [KategoriServiceController::class, 'datatable'])->name('admin.setting.kategori-service.datatable');
            Route::get('/get-data-select2', [KategoriServiceController::class, 'getDataSelect2'])->name('admin.setting.kategori-service.get-data-select2');
        });
    });
});

Route::group(['prefix' => 'user', 'middleware' => ['sso_up:web', 'auth', 'role:user|manager_asset|manager_it|staff_asset|staff_it|admin']], function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard.index');
    Route::get('/profile', [UserDashboardController::class, 'profile'])->name('user.dashboard.profile');
    Route::get('/find-position-by-username', [UserDashboardController::class, 'findPositionByUsername'])->name('user.dashboard.profile.find-position-by-username');
    Route::get('/about', [UserDashboardController::class, 'about'])->name('user.dashboard.about');
    Route::get('/dashboard/get-summary-dashboard', [UserDashboardController::class, 'getDashboardData'])->name('user.get-summary-dashboard');
    Route::group(['prefix' => 'notification'], function () {
        Route::get('/', [UserDashboardController::class, 'notification'])->name('user.notification.index');
        Route::get('/get-data', [UserDashboardController::class, 'getNotificationData'])->name('user.notification.get-data');
        Route::get('/count', [UserDashboardController::class, 'countNotification'])->name('user.notification.count');
        Route::post('/get-data', [UserDashboardController::class, 'readNotification'])->name('user.notification.read');
    });
    Route::group(['prefix' => 'scan-qr'], function () {
        Route::get('/', [ScanQrCodeController::class, 'index'])->name('user.scan-qr.index');
        Route::post('find', [ScanQrCodeController::class, 'find'])->name('user.scan-qr.find');
    });
    Route::group(['prefix' => 'asset-data'], function () {
        Route::get('/get-all-data-asset-by-user', [UserAssetController::class, 'getDataAssetByUser'])->name('user.asset-data.get-all-data-asset-by-user');
        Route::get('/detail/{id}', [UserAssetController::class, 'detail'])->name('user.asset-data.detail');
        Route::get('/get-data-select2', [UserAssetController::class, 'getDataAssetSelect2'])->name('user.asset-data.get-data-select2');
        Route::group(['prefix' => 'pemindahan-asset'], function () {
            Route::get('/print-bast/{id}', [PemindahanAssetController::class, 'printBast'])->name('user.listing-asset.pemindahan-asset.print-bast');
            Route::get('/detail/{id}', [UserPemindahanAssetController::class, 'detail'])->name('user.asset-data.pemindahan.detail');
            Route::post('/approve/{id}', [UserPemindahanAssetController::class, 'approve'])->name('user.asset-data.pemindahan.approve');
        });
        Route::group(['prefix' => 'service', 'middleware' => ['role:manager_asset|manager_it|staff_asset|staff_it|admin']], function () {
            Route::get('/get-data-select2', [KategoriServiceController::class, 'getDataSelect2'])->name('user.asset-data.service.getDataSelect2');
            Route::get('/create/{id}', [UserAssetServicesController::class, 'create'])->name('user.asset-data.service.create');
            Route::post('/store/{id}', [UserAssetServicesController::class, 'store'])->name('user.asset-data.service.store');
            Route::get('/get-data-perencanaan-service', [UserAssetServicesController::class, 'getDataPerencanaanService'])->name('user.services.get-data-perencanaan-service');
        });
        Route::group(['prefix' => 'peminjaman',], function () {
            Route::get('/', [UserPeminjamanAssetController::class, 'index'])->name('user.asset-data.peminjaman.index');
            Route::get('/detail/{id}', [UserPeminjamanAssetController::class, 'detail'])->name('user.asset-data.peminjaman.detail');
            Route::get('/get-all-data', [UserPeminjamanAssetController::class, 'getAllData'])->name('user.asset-data.peminjaman.get-all-data');
            Route::get('/create', [UserPeminjamanAssetController::class, 'create'])->name('user.asset-data.peminjaman.create');
            Route::post('/store', [UserPeminjamanAssetController::class, 'store'])->name('user.asset-data.peminjaman.store');
            Route::post('/perpanjangan/{id}/store', [UserPeminjamanAssetController::class, 'storePerpanjangan'])->name('user.asset-data.peminjaman.perpanjangan.store');
        });
        Route::group(['prefix' => 'opname', 'middleware' => ['role:manager_asset|manager_it|staff_asset|staff_it|admin']], function () {
            Route::get('/create/{id}', [AssetOpnameController::class, 'create'])->name('user.asset-data.opname.create');
            Route::post('/store/{id}', [AssetOpnameController::class, 'store'])->name('user.asset-data.opname.store');
        });
        Route::group(['prefix' => 'pengaduan'], function () {
            Route::get('/create/{id}', [AssetPengaduanController::class, 'create'])->name('user.asset-data.pengaduan.index');
            Route::post('/store/{id}', [AssetPengaduanController::class, 'store'])->name('user.asset-data.pengaduan.store');
        });
        Route::group(['prefix' => 'bahan-habis-pakai'], function () {
            Route::get('/', [BahanHabisPakaiController::class, 'index'])->name('user.asset-data.bahan-habis-pakai.index');
            Route::get('/create', [BahanHabisPakaiController::class, 'create'])->name('user.asset-data.bahan-habis-pakai.create');
            Route::get('/edit/{id}', [BahanHabisPakaiController::class, 'edit'])->name('user.asset-data.bahan-habis-pakai.edit');
            Route::post('/update/{id}', [BahanHabisPakaiController::class, 'update'])->name('user.asset-data.bahan-habis-pakai.update');
            Route::get('/detail/{id}', [BahanHabisPakaiController::class, 'detail'])->name('user.asset-data.bahan-habis-pakai.detail');
            Route::post('/store', [BahanHabisPakaiController::class, 'store'])->name('user.asset-data.bahan-habis-pakai.store');
            Route::get('/get-all-data', [BahanHabisPakaiController::class, 'getAllData'])->name('user.asset-data.bahan-habis-pakai.get-all-data');
            Route::get('/get-all-log-data', [BahanHabisPakaiController::class, 'getAllLogData'])->name('user.asset-data.bahan-habis-pakai.get-all-data-log');
            Route::group(['prefix' => 'master'], function () {
                Route::get('get-data-item-select2', [MasterInventarisController::class, 'getDataSelect2'])->name('user.asset-data.bahan-habis-pakai.item.get-data-select2');
                Route::get('get-data-kategori-select2', [KategoriInventoriController::class, 'getDataSelect2'])->name('user.asset-data.bahan-habis-pakai.kategori.get-data-select2');
            });
        });
    });
    Route::group(['prefix' => 'approval'], function () {
        Route::get('/', [UserApprovalController::class, 'index'])->name('user.approval.index');
        Route::get('/detail/{id}', [UserApprovalController::class, 'detail'])->name('user.approval.detail');
        Route::get('/get-all-data', [UserApprovalController::class, 'getAllData'])->name('user.approval.get-all-data');
    });

    Route::group(['prefix' => 'pengaduan'], function () {
        Route::get('/', [UserPengaduanController::class, 'index'])->name('user.pengaduan.index');
        Route::get('/get-select2', [LokasiController::class, 'getAllSelect2'])->name('user.pengaduan.lokasi.get-select2');
        Route::get('/create', [UserPengaduanController::class, 'create'])->name('user.pengaduan.create');
        Route::get('/edit/{id}', [UserPengaduanController::class, 'edit'])->name('user.pengaduan.edit');
        Route::post('/update/{id}', [UserPengaduanController::class, 'update'])->name('user.pengaduan.update');
        Route::post('/store', [UserPengaduanController::class, 'store'])->name('user.pengaduan.store');
        Route::post('/destroy/{id}', [UserPengaduanController::class, 'destroy'])->name('user.pengaduan.destroy');
        Route::get('/get-all-data', [UserPengaduanController::class, 'getAllData'])->name('user.pengaduan.get-all-data');
        Route::get('/get-all-data-log', [UserPengaduanController::class, 'getAllDataLog'])->name('user.pengaduan.get-all-data-log');
        Route::get('/detail/{id}', [UserPengaduanController::class, 'detail'])->name('user.pengaduan.detail');
        Route::get('/download-gambar', [UserPengaduanController::class, 'download'])->name('user.pengaduan.download-gambar');
    });
    Route::group(['prefix' => 'api-master'], function () {
        Route::get('/group-kategori-asset/get-data-select2', [GroupKategoriAssetController::class, 'getDataSelect2'])->name('user.api-master.group-kategori-asset.get-data-select2');
        Route::get('/kategori-asset/get-data-select2', [KategoriAssetController::class, 'getDataSelect2'])->name('user.api-master.kategori-asset.get-data-select2');
    });
});

Route::group(['prefix' => 'sso-api'], function () {
    Route::get('/get-data-unit', [SsoDataController::class, 'getDataUnit'])->name('sso-api.get-data-unit');
    Route::get('/get-data-position', [SsoDataController::class, 'getDataPosition'])->name('sso-api.get-data-position');
    Route::get('/get-data-position-by-guid', [SsoDataController::class, 'getDataPositionByGuid'])->name('sso-api.get-data-position-by-guid');
    Route::get('/get-data-unit-by-position', [SsoDataController::class, 'getDataUnitByGuid'])->name('sso-api.get-data-unit-by-position');
});

Route::group(['prefix' => 'andin-api'], function () {
    Route::get('/get-data-memorandum', [AndinApiController::class, 'findAllMemorandum'])->name('andin-api.find-data-memorandum');
});

Route::group(['prefix' => 'test-front', 'namespace' => 'TestFront'], function () {
    Route::group(['prefix' => 'print'], function () {
        Route::post('/', [TestingController::class, 'print'])->name('admin.setting.print.index');
    });
    Route::get('/', 'TestingController@index');
    Route::get('/tree', 'TestingController@tree');
    Route::get('/form', 'TestingController@form');
    Route::get('/table', 'TestingController@table');
    Route::post('/form-post', 'TestingController@formPost')->name('test-front.form-post');
    Route::get('/select2-ajax-data', 'TestingController@select2AjaxData')->name('test-front.select2-ajax-data');
    Route::get('/datatable', 'TestingController@datatable')->name('test-front.datatable');

    Route::group(['prefix' => 'user'], function () {
        Route::get('/', [TestingController::class, 'user'])->name('admin.setting.print.user');
    });
});
