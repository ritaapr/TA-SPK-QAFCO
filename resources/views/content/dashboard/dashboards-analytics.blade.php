@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Analytics')

@section('vendor-style')
    @vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
@endsection

@section('vendor-script')
    @vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
@endsection

@section('content')
    <div class="row">
        <!-- Card Selamat Datang -->
        <div class="col-lg-4 col-md-6 col-12 mb-4">
            <div class="card h-100">
                <div class="d-flex align-items-start row h-100">
                    <div class="col-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary mb-3">
                                Selamat Datang, {{ auth()->user()->name }}! 🎉
                            </h5>
                            <p class="mb-0">
                                Anda berhasil masuk sebagai {{ auth()->user()->role }}.
                            </p>
                        </div>
                    </div>
                    <div class="col-5 text-center text-sm-left align-self-end">
                        <div class="card-body pb-0 px-0 px-md-6">
                            <img src="{{ asset('assets/img/illustrations/man-with-laptop.png') }}" height="120"
                                class="scaleX-n1-rtl" alt="View Badge User">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Jumlah Rekomendasi -->
        <div class="col-lg-4 col-md-6 col-12 mb-4">
            <div class="card h-100">
                <div class="card-body">

                    <p class="mb-1">Jumlah Rekomendasi</p>
                    <h4 class="card-title mb-3">{{ $totalRekomendasi }}</h4>
                    <small class="text-primary fw-medium">
                        <i class='bx bx-user'></i>
                        Rekomendasi CPMI
                    </small>
                </div>
            </div>
        </div>

        <!-- Card Jumlah Kriteria -->
        <div class="col-lg-4 col-md-6 col-12 mb-4">
            <div class="card h-100">
                <div class="card-body">

                    <p class="mb-1">Jumlah Kriteria</p>
                    <h4 class="card-title mb-3">{{ $totalKriteria }}</h4>
                    <small class="text-warning fw-medium">
                        <i class='bx bx-slider-alt'></i>
                        Kriteria penilaian SPK
                    </small>
                </div>
            </div>
        </div>

            <div class="col-12 mt-2">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Jumlah Rekomendasi per Bulan</h5>
                    </div>
                    <div class="card-body">
                        <div id="chartRekomendasi"></div>
                    </div>
                </div>
            </div>
  

    </div>

@section('page-script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const options = {
                chart: {
                    type: 'line',
                    height: 350
                },
                series: [{
                    name: 'Rekomendasi',
                    data: @json($chartData)
                }],
                xaxis: {
                    categories: @json($chartLabels),
                },
                yaxis: {
                    labels: {
                        formatter: function(value) {
                            return Math.round(value);
                        }
                    },
                },
                stroke: {
                    curve: 'smooth'
                },
                colors: ['#696CFF']
            };

            const chart = new ApexCharts(document.querySelector("#chartRekomendasi"), options);
            chart.render();
        });
    </script>
@endsection

@endsection
