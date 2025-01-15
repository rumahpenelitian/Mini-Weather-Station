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

                        <div class="card">
                            <div class="card-body">
                                <div id="tegangan"></div>
                                <div id="arus"></div>
                                <div id="power"></div>
                            </div>
                        </div>

                        <table id="example" class="table datatables-simply table-striped table-hover" cellspacing="0" width="100%">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <!--<th>ID</th>-->
                                    <th>Tegangan Dinamis</th>
                                    <th>Tegangan Statis</th>
                                    <th>Arus Dinamis</th>
                                    <th>Arus Statis</th>
                                    <th>Power Dinamis</th>
                                    <th>Power Statis</th>
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
                url: 'realtime_data_chat2.php', // Ganti dengan URL yang sesuai untuk mendapatkan data JSON
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log('====================================');
                    console.log(data);
                    console.log('====================================');
                    Highcharts.chart('tegangan', {
                        chart: {
                            type: 'spline'
                        },
                        title: {
                            text: 'Perbandingan Tegangan Dinamis dan Tegangan Statis'
                        },
                        xAxis: {
                            categories: data.realtime
                        },
                        yAxis: {
                            title: {
                                text: 'Tegangan (V)'
                            }
                        },
                        series: [{
                            name: 'Tegangan Dinamis',
                            data: data.tegangan_dinamis
                        }, {
                            name: 'Tegangan Statis',
                            data: data.tegangan_statis
                        }]
                    });

                    Highcharts.chart('arus', {
                        chart: {
                            type: 'spline'
                        },
                        title: {
                            text: 'Perbandingan Arus Dinamis dan Arus Statis'
                        },
                        xAxis: {
                            categories: data.realtime
                        },
                        yAxis: {
                            title: {
                                text: 'Arus (A)'
                            }
                        },
                        series: [{
                            name: 'Arus Dinamis',
                            data: data.arus_dinamis
                        }, {
                            name: 'Arus Statis',
                            data: data.arus_statis
                        }]
                    });

                    Highcharts.chart('power', {
                        chart: {
                            type: 'spline'
                        },
                        title: {
                            text: 'Perbandingan Power Dinamis dan Power Statis'
                        },
                        xAxis: {
                            categories: data.realtime
                        },
                        yAxis: {
                            title: {
                                text: 'Power (W)'
                            }
                        },
                        series: [{
                            name: 'Power Dinamis',
                            data: data.power_dinamis
                        }, {
                            name: 'Power Statis',
                            data: data.power_statis
                        }]
                    });
                }
            });
        });
    </script>
</body>

</html>