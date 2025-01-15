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
                                <div id="container"></div>
                                <div id="container-alt-pressure"></div>
                                <div id="container-altitude"></div>
                                <div id="container-lux"></div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <table id="example" class="table datatables-simply table-striped table-hover" cellspacing="0" width="100%">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No</th>
                                            <!--<th>ID</th>-->
                                            <th>Temperature °C</th>
                                            <th>Altitude</th>
                                            <th>Pressure hPa</th>
                                            <th>Humadity %</th>
                                            <th>Lux</th>
                                            <th>Rain Drop</th>
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
                        </div>
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

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/series-label.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <?php include 'layouts/partials/foot.php'; ?>

    <script>
        // Fungsi untuk memuat dan menampilkan data secara realtime
        function loadRealtimeData() {
            $.ajax({
                url: 'realtime_data.php', // Ganti dengan URL yang sesuai
                method: 'GET',
                dataType: 'html',
                success: function(response) {
                    $('#realtime-data').html(response);
                }
            });
        }

        // Jalankan fungsi loadRealtimeData setiap 5 detik
        setInterval(loadRealtimeData, 3600000); // 60000 milliseconds = 60 detik
        3,6e+6

        // Fungsi untuk mengambil data dari halaman tertentu
        function loadDataByPage(page) {
            $.ajax({
                url: 'realtime_data.php', // Ganti dengan URL yang sesuai
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

        // Data retrieved https://en.wikipedia.org/wiki/List_of_cities_by_average_temperature
        $(document).ready(function() {
            $.ajax({
                url: 'realtime_data_chat.php', // Ganti dengan URL yang sesuai untuk mendapatkan data JSON
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log(data.temperature);
                    console.log([21,23,12,321]);
                    // Chart A: Temperature vs Humidity
                    Highcharts.chart('container', {
                        chart: {
                            type: 'areaspline'
                        },
                        title: {
                            text: 'Temperature vs Humidity'
                        },
                        xAxis: {
                            categories: data.realtime
                        },
                        yAxis: {
                            title: {
                                text: 'Value'
                            }
                        },
                        series: [{
                            name: 'Temperature',
                            data: data.temperature
                        }, {
                            name: 'Humidity',
                            data: data.humidity
                        }]
                    });

                    // Chart B: Altitude vs Pressure
                    Highcharts.chart('container-alt-pressure', {
                        chart: {
                            type: 'line'
                        },
                        title: {
                            text: 'Altitude vs Pressure'
                        },
                        xAxis: {
                            categories: data.realtime
                        },
                        yAxis: {
                            title: {
                                text: 'Value'
                            }
                        },
                        series: [{
                            name: 'Altitude',
                            data: data.altitude
                        }, {
                            name: 'Pressure',
                            data: data.pressure
                        }]
                    });

                    Highcharts.chart('container-altitude', {
                        chart: {
                            type: 'spline'
                        },
                        title: {
                            text: 'Altitude'
                        },
                        xAxis: {
                            categories: data.realtime,
                            min: 0,
                            max: 12  
                        },
                        yAxis: {
                            title: {
                                text: 'Altitude'
                            },
                            max: 250
                        },
                        series: [{
                            name: 'Altitude',
                            data: data.altitude
                        }]
                    });

                    // Chart C: Lux
                    Highcharts.chart('container-lux', {
                        chart: {
                            type: 'line'
                        },
                        title: {
                            text: 'Lux'
                        },
                        xAxis: {
                            categories: data.realtime,
                            min: 0,
                            max: 12  
                        },
                        yAxis: {
                            title: {
                                text: 'Lux'
                            },
                            max: 75000
                        },
                        series: [{
                            name: 'Lux',
                            data: data.lux
                        }]
                    });
                }
            });
        });
    </script>
</body>

</html>