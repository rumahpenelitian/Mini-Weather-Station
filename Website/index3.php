<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-assets-path="<?php echo htmlspecialchars($assetsPath, ENT_QUOTES, 'UTF-8'); ?>" data-template="vertical-menu-template">

<head>
    <!-- HEAD  -->
    <?php include 'layouts/partials/head.php'; ?>
</head>

<body>
    <style>
        .create-new {
            display: none;
        }
    </style>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            <!-- SIDEBAR  -->
            <?php include 'layouts/partials/sidebar.php'; ?>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                <?php include 'layouts/partials/navbar.php'; ?>
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">

                        <div class="container">
                            <div id="container-total-tip" style="height: 400px; margin: 20px;"></div>
                            <div id="container-curah-hujan-hari-ini" style="height: 400px; margin: 20px;"></div>
                            <div id="container-curah-hujan-per-hari" style="height: 400px; margin: 20px;"></div>
                        </div>
                        <table id="example" class="table datatables-simply table-striped table-hover" cellspacing="0" width="100%">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <!--<th>ID</th>-->
                                    <th>Total Tip</th>
                                    <th>Curah Hujan Hari Ini</th>
                                    <th>Curah Hujan Per Menit</th>
                                    <th>Curah Hujan Per Jam</th>
                                    <th>Curah Hujan Per Hari</th>
                                    <th>Cuaca</th>
                                    <th>Real Time</th>
                                    <th>Tanggal</th>
                                    <!--<th>Created_at</th>-->
                                    <!--<th>Update_at</th>-->
                                </tr>
                            </thead>
                            <tbody id="realtime-data">
                                <!-- Data akan dimasukkan di sini secara dinamis -->
                            </tbody>
                        </table>
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    <?php include 'layouts/partials/footer.php'; ?>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>
        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->
    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <!-- FOOT -->
    <?php include 'layouts/partials/foot.php'; ?>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/series-label.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script>
        // Fungsi untuk memuat dan menampilkan data secara realtime
        function loadRealtimeData() {
            $.ajax({
                url: 'realtime_data2.php', // Ganti dengan URL yang sesuai
                method: 'GET',
                dataType: 'html',
                success: function(response) {
                    $('#realtime-data').html(response);
                }
            });
        }

        // Jalankan fungsi loadRealtimeData setiap 5 detik
        setInterval(loadRealtimeData, 3600000); // 60000 milliseconds = 60 detik

        // Fungsi untuk mengambil data dari halaman tertentu
        function loadDataByPage(page) {
            $.ajax({
                url: 'realtime_data2.php', // Ganti dengan URL yang sesuai
                method: 'GET',
                data: {
                    page: page
                },
                dataType: 'html',
                success: function(response) {
                    $('#realtime-data').html(response);
                    $('#page-number').text(page);

                    $('.datatables-simply').DataTable().destroy();
                    initializeDataTable();
                }
            });
        }

        // Jalankan fungsi loadDataByPage dengan halaman pertama
        loadDataByPage(1);

        // Tambahkan event listener untuk tombol next
        $('#next').click(function() {
            var currentPage = parseInt($('#page-number').text());
            loadDataByPage(currentPage + 1);
        });

        // Tambahkan event listener untuk tombol previous
        $('#prev').click(function() {
            var currentPage = parseInt($('#page-number').text());
            if (currentPage > 1) {
                loadDataByPage(currentPage - 1);
            }
        });

        $(document).ready(function() {
            $.ajax({
                url: 'realtime_data_chat3.php', // Ganti dengan URL yang sesuai untuk mendapatkan data JSON
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Chart A: Total Tip
                    console.log('====================================');
                    console.log(data);
                    console.log('====================================');
                    Highcharts.chart('container-total-tip', {
                        chart: {
                            type: 'spline'
                        },
                        title: {
                            text: 'Total Tip'
                        },
                        xAxis: {
                            categories: data.realtime // Menggunakan data kategori
                        },
                        yAxis: {
                            title: {
                                text: 'Jumlah Tip'
                            }
                        },
                        series: [{
                            name: 'Total Tip',
                            data: data.total_tip,
                            marker: {
                                symbol: 'square'
                            }
                        }]
                    });

                    // Chart B: Curah Hujan Hari Ini
                    Highcharts.chart('container-curah-hujan-hari-ini', {
                        chart: {
                            type: 'spline'
                        },
                        title: {
                            text: 'Curah Hujan Hari Ini'
                        },
                        xAxis: {
                            categories: data.realtime // Menggunakan data kategori
                        },
                        yAxis: {
                            title: {
                                text: 'Curah Hujan (mm)'
                            }
                        },
                        series: [{
                            name: 'Curah Hujan Hari Ini',
                            data: data.curah_hujan_hari_ini,
                            marker: {
                                symbol: 'diamond'
                            }
                        }]
                    });

                    // Chart C: Curah Hujan Per Hari
                    Highcharts.chart('container-curah-hujan-per-hari', {
                        chart: {
                            type: 'spline'
                        },
                        title: {
                            text: 'Curah Hujan Per Hari'
                        },
                        xAxis: {
                            categories: data.realtime // Menggunakan data kategori
                        },
                        yAxis: {
                            title: {
                                text: 'Curah Hujan (mm)'
                            }
                        },
                        series: [{
                            name: 'Curah Hujan Per Hari',
                            data: data.curah_hujan_per_hari,
                            marker: {
                                symbol: 'triangle'
                            }
                        }]
                    });
                },
                error: function(xhr, status, error) {
                    console.log('Error: ' + error);
                }
            });
        });
    </script>
</body>

</html>