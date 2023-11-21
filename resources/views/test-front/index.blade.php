@extends('layouts.admin.main.master')
@section('custom_js')
    <script>
        const ctxAssetSummary = document.getElementById('chartAssetSummary');
        const chartAssetSummary = new Chart(ctxAssetSummary, {
            type: 'doughnut',
            data: {
                labels: ['Structure', 'Electrical', 'Static', 'Piping', 'Rotating'],
                datasets: [{
                    label: '# of Votes',
                    data: [10, 5, 35, 30, 20],
                    backgroundColor: [
                        '#7C52DC',
                        '#FB9A99',
                        '#1F78B4',
                        '#A6CEE3',
                        '#E6C881'
                    ],
                    borderWidth: 1
                }]
            },
            plugins: [ChartDataLabels],
            options: {
                cutout: '80%',
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 30,
                        right: 30,
                        top: 30,
                        bottom: 30
                    }
                },
                plugins: {
                    legend: {
                        display: false,
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'end',
                        color: 'rgba(118, 118, 118, 1)',
                        font: {
                            size: '10',
                        },
                        formatter: function(value, context) {
                            return context.chart.data.labels[context.dataIndex] + `(${value}%)`;
                        }
                    }
                }
            }
        });
    </script>
@endsection
@section('main-content')
<div class="row">
    <div class="col-12">
        <h5 class="text-primary mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="20.933" height="20.933" viewBox="0 0 20.933 20.933">
                <path id="Icon_material-pie-chart-outlined" data-name="Icon material-pie-chart-outlined" d="M13.466,3A10.466,10.466,0,1,0,23.933,13.466,10.5,10.5,0,0,0,13.466,3Zm1.047,2.167a8.386,8.386,0,0,1,7.253,7.253H14.513Zm-9.42,8.3a8.394,8.394,0,0,1,7.327-8.3v16.61A8.413,8.413,0,0,1,5.093,13.466Zm9.42,8.3V14.513h7.253A8.375,8.375,0,0,1,14.513,21.766Z" transform="translate(-3 -3)" fill="#0067d4"/>
            </svg>
            <strong>Data Summary</strong>
        </h5>
    </div>
    <div class="col-md-6 col-12">
        <div class="row">
            <div class="col-md-6 col-12">
                <div class="kt-portlet shadow-custom">
                    <div class="kt-portlet__head px-4">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Asset Data
                            </h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div style="height: 115px;">
                            <h6>Total Asset Data</h6>
                            <h1 class="text-dark text-right"><strong>1.643</strong></h1>
                        </div>
                        <div style="height: 115px;">
                            <h6>Last Change</h6>
                            <p class="text-primary text-right"><strong>20-Jun-2022</strong></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-12">
                <div class="kt-portlet shadow-custom">
                    <div class="kt-portlet__head px-4">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Asset Summary
                            </h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <canvas id="chartAssetSummary" style="width: 230px; height: 230px;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-12">
                <div class="kt-portlet shadow-custom">
                    <div class="kt-portlet__head px-4">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Asset Value
                            </h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <span class="mr-3 kt-badge kt-badge--unified-brand kt-badge--lg kt-badge--rounded kt-badge--bold">
                                    <i class="fa fa-money-bill-wave"></i>
                                </span>
                                <p class="mb-0 text-dark">Nilai Beli Asset</p>
                            </div>
                            <h2 class="text-dark mb-0"><strong>120 Jt</strong></h2>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <span class="mr-3 kt-badge kt-badge--unified-danger kt-badge--lg kt-badge--rounded kt-badge--bold">
                                    <i class="fa fa-dollar-sign"></i>
                                </span>
                                <p class="mb-0 text-dark">Total Depresiasi</p>
                            </div>
                            <h2 class="text-dark mb-0"><strong>30 Jt</strong></h2>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <span style="background-color: #C3D8EC; border-color: #C3D8EC;" class="mr-3 kt-badge kt-badge--unified-danger kt-badge--lg kt-badge--rounded kt-badge--bold">
                                    <i class="fa fa-dollar-sign text-light"></i>
                                </span>
                                <p class="mb-0 text-dark">Value Asset</p>
                            </div>
                            <h2 class="text-dark mb-0"><strong>30 Jt</strong></h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-12">
                <div class="kt-portlet shadow-custom">
                    <div class="kt-portlet__head px-4">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Kondisi Asset
                            </h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <canvas id="chartCertification" style="width: 230px; height: 100px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-12">
        <div class="kt-portlet shadow-custom">
            <div class="kt-portlet__head px-4">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        Summary Penerimaan Asset
                    </h3>
                </div>
            </div>
            <div class="kt-portlet__body">
                <canvas id="chartPenerimaanAsset" style="width: 230px; height: 230px;"></canvas>
            </div>
        </div>
        <div class="kt-portlet shadow-custom">
            <div class="kt-portlet__head px-4">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        Summary Pemeliharaan
                    </h3>
                </div>
            </div>
            <div class="kt-portlet__body">
                <div class="row">
                    <div class="col-4">
                        <div class="d-flex align-items-center border-bottom border-success py-2">
                            <h2 class="text-success mb-0"><strong>27</strong></h2>
                            <h6 class="text-success ml-2 mb-0"><strong>Total Laporan User</strong></h6>
                        </div>
                        <div class="d-flex align-items-center border-bottom border-success py-2">
                            <h2 class="text-success mb-0"><strong>27</strong></h2>
                            <h6 class="text-success ml-2 mb-0"><strong>Total Ditangani</strong></h6>
                        </div>
                        <div class="d-flex align-items-center border-bottom border-success py-2">
                            <h2 class="text-success mb-0"><strong>27</strong></h2>
                            <h6 class="text-success ml-2 mb-0"><strong>Total Belum Ditangani</strong></h6>
                        </div>
                    </div>
                    <div class="col-8"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection