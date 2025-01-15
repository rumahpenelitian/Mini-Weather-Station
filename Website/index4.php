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
                                <div id="container-velo"></div>
                            </div>
                        </div>
                        <table id="example" class="table datatables-simply table-striped table-hover" cellspacing="0" width="100%">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <!--<th>ID</th>-->
                                    <th>Arah Mata Angin</th>
                                    <th>Rps</th>
                                    <th>Velocity ms</th>
                                    <th>Velocity Kmh</th>
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

                        <?php
                        require_once 'config/database.php';

                        $database = new Database();
                        $db = $database->getConnection();

                        // Query untuk mengambil data dari tabel wind_weather
                        $sql = "SELECT * FROM wind_weather ORDER BY id DESC LIMIT 150";
                        $stmt = $db->prepare($sql);
                        $stmt->execute();

                        $data = [
                            'rps_selatan' => [],
                            'rps_utara' => [],
                            'rps_timur' => [],
                            'rps_barat' => [],
                            'rps_barat_laut' => [],
                            'rps_tenggara' => [],
                            'velo_selatan' => [],
                            'velo_utara' => [],
                            'velo_timur' => [],
                            'velo_barat' => [],
                            'velo_barat_laut' => [],
                            'velo_tenggara' => []
                        ];

                        // Mengkategorikan data berdasarkan arah angin
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            switch ($row['arah_angin']) {
                                case 'Selatan':
                                    $data['rps_selatan'][] = $row['rps'];
                                    $dataVelo['velo_selatan'][] = $row['velocity_ms'];
                                    break;
                                case 'Utara':
                                    $data['rps_utara'][] = $row['rps'];
                                    $dataVelo['velo_utara'][] = $row['velocity_ms'];
                                    break;
                                case 'Timur':
                                    $data['rps_timur'][] = $row['rps'];
                                    $dataVelo['velo_timur'][] = $row['velocity_ms'];
                                    break;
                                case 'Barat':
                                    $data['rps_barat'][] = $row['rps'];
                                    $dataVelo['velo_barat'][] = $row['velocity_ms'];
                                    break;
                                case 'Barat laut':
                                    $data['rps_barat_laut'][] = $row['rps'];
                                    $dataVelo['velo_barat_laut'][] = $row['velocity_ms'];
                                    break;
                                case 'Tenggara':
                                    $data['rps_tenggara'][] = $row['rps'];
                                    $dataVelo['velo_tenggara'][] = $row['velocity_ms'];
                                    break;
                            }
                        }

                        function categorizeData($speeds)
                        {
                            $categories = ['< 0.5 m/s' => 0, '0.5-2 m/s' => 0, '2-4 m/s' => 0, '4-6 m/s' => 0, '6-8 m/s' => 0, '8-10 m/s' => 0, '> 10 m/s' => 0];
                            foreach ($speeds as $speed) {
                                $speed = (float)$speed; // Convert speed to float
                                if ($speed < 0.5) $categories['< 0.5 m/s']++;
                                else if ($speed < 2) $categories['0.5-2 m/s']++;
                                else if ($speed < 4) $categories['2-4 m/s']++;
                                else if ($speed < 6) $categories['4-6 m/s']++;
                                else if ($speed < 8) $categories['6-8 m/s']++;
                                else if ($speed < 10) $categories['8-10 m/s']++;
                                else $categories['> 10 m/s']++;
                            }
                            return $categories;
                        }

                        $directions = [
                            'Selatan' => 'rps_selatan',
                            'Utara' => 'rps_utara',
                            'Timur' => 'rps_timur',
                            'Barat' => 'rps_barat',
                            'Tenggara' => 'rps_tenggara',
                            'Barat Laut' => 'rps_barat_laut'
                        ];

                        $directionsVelo = [
                            'Selatan' => 'velo_selatan',
                            'Utara' => 'velo_utara',
                            'Timur' => 'velo_timur',
                            'Barat' => 'velo_barat',
                            'Tenggara' => 'velo_tenggara',
                            'Barat Laut' => 'velo_barat_laut'
                        ];

                        // var_dump($data);
                        ?>

                        <!-- Tabel Frekuensi untuk RPS -->
                        <div style="display:none">
                            <table id="freq" border="0" cellspacing="0" cellpadding="0">
                                <tr nowrap bgcolor="#CCCCFF">
                                    <th colspan="9" class="hdr">Table of Frequencies (percent)</th>
                                </tr>
                                <tr nowrap bgcolor="#CCCCFF">
                                    <th class="freq">Direction</th>
                                    <th class="freq">&lt; 0.5 m/s</th>
                                    <th class="freq">0.5-2 m/s</th>
                                    <th class="freq">2-4 m/s</th>
                                    <th class="freq">4-6 m/s</th>
                                    <th class="freq">6-8 m/s</th>
                                    <th class="freq">8-10 m/s</th>
                                    <th class="freq">&gt; 10 m/s</th>
                                    <th class="freq">Total</th>
                                </tr>
                                <?php
                                var_dump($data);
                                foreach ($directions as $direction => $key) {
                                    $categories = categorizeData($data[$key]);
                                    $total = array_sum($categories);
                                    echo "<tr nowrap>
                                        <td class=\"dir\">$direction</td>
                                        <td class=\"data\">{$categories['< 0.5 m/s']}</td>
                                        <td class=\"data\">{$categories['0.5-2 m/s']}</td>
                                        <td class=\"data\">{$categories['2-4 m/s']}</td>
                                        <td class=\"data\">{$categories['4-6 m/s']}</td>
                                        <td class=\"data\">{$categories['6-8 m/s']}</td>
                                        <td class=\"data\">{$categories['8-10 m/s']}</td>
                                        <td class=\"data\">{$categories['> 10 m/s']}</td>
                                        <td class=\"data\">$total</td>
                                    </tr>";
                                }
                                ?>
                            </table>
                        </div>

                        <!-- Tabel Frekuensi untuk Velocity -->
                        <div style="display:none">
                            <table id="velo" border="0" cellspacing="0" cellpadding="0">
                                <tr nowrap bgcolor="#CCCCFF">
                                    <th colspan="9" class="hdr">Table of Frequencies (percent)</th>
                                </tr>
                                <tr nowrap bgcolor="#CCCCFF">
                                    <th class="velo">Direction</th>
                                    <th class="velo">&lt; 0.5 m/s</th>
                                    <th class="velo">0.5-2 m/s</th>
                                    <th class="velo">2-4 m/s</th>
                                    <th class="velo">4-6 m/s</th>
                                    <th class="velo">6-8 m/s</th>
                                    <th class="velo">8-10 m/s</th>
                                    <th class="velo">&gt; 10 m/s</th>
                                    <th class="velo">Total</th>
                                </tr>
                                <?php
                                var_dump($dataVelo);
                                foreach ($directionsVelo as $directionVelo => $keyVelo) {
                                    $categoriesVelo = categorizeData($dataVelo[$keyVelo]);
                                    $totalVelo = array_sum($categoriesVelo);
                                    echo "<tr nowrap>
                                        <td class=\"dir\">$directionVelo</td>
                                        <td class=\"data\">{$categoriesVelo['< 0.5 m/s']}</td>
                                        <td class=\"data\">{$categoriesVelo['0.5-2 m/s']}</td>
                                        <td class=\"data\">{$categoriesVelo['2-4 m/s']}</td>
                                        <td class=\"data\">{$categoriesVelo['4-6 m/s']}</td>
                                        <td class=\"data\">{$categoriesVelo['6-8 m/s']}</td>
                                        <td class=\"data\">{$categoriesVelo['8-10 m/s']}</td>
                                        <td class=\"data\">{$categoriesVelo['> 10 m/s']}</td>
                                        <td class=\"data\">$totalVelo</td>
                                    </tr>";
                                }
                                ?>
                            </table>
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
    <?php include 'layouts/partials/foot.php'; ?>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <script>
        // Fungsi untuk memuat dan menampilkan data secara realtime
        function loadRealtimeData() {
            $.ajax({
                url: 'realtime_data4.php', // Ganti dengan URL yang sesuai
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
                url: 'realtime_data4.php', // Ganti dengan URL yang sesuai
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

        // Parse the data from an inline table using the Highcharts Data plugin
        Highcharts.chart('container', {
            data: {
                table: 'freq',
                startRow: 1,
                endRow: 17,
                endColumn: 7
            },

            chart: {
                polar: true,
                type: 'column'
            },

            title: {
                text: 'Wind rose Arah Mata Angin',
                align: 'left'
            },

            subtitle: {
                text: 'Source: or.water.usgs.gov',
                align: 'left'
            },

            pane: {
                size: '85%'
            },

            legend: {
                align: 'right',
                verticalAlign: 'top',
                y: 100,
                layout: 'vertical'
            },

            xAxis: {
                tickmarkPlacement: 'on'
            },

            yAxis: {
                min: 0,
                endOnTick: false,
                showLastLabel: true,
                title: {
                    text: 'Frequency (%)'
                },
                labels: {
                    format: '{value}%'
                },
                reversedStacks: false
            },

            tooltip: {
                valueSuffix: '%'
            },

            plotOptions: {
                series: {
                    stacking: 'normal',
                    shadow: false,
                    groupPadding: 0,
                    pointPlacement: 'on'
                }
            }
        });
        Highcharts.chart('container-velo', {
            data: {
                table: 'velo',
                startRow: 1,
                endRow: 17,
                endColumn: 7
            },

            chart: {
                polar: true,
                type: 'column'
            },

            title: {
                text: 'Wind rose Velocity',
                align: 'left'
            },

            subtitle: {
                text: 'Source: or.water.usgs.gov',
                align: 'left'
            },

            pane: {
                size: '85%'
            },

            legend: {
                align: 'right',
                verticalAlign: 'top',
                y: 100,
                layout: 'vertical'
            },

            xAxis: {
                tickmarkPlacement: 'on'
            },

            yAxis: {
                min: 0,
                endOnTick: false,
                showLastLabel: true,
                title: {
                    text: 'Frequency (%)'
                },
                labels: {
                    format: '{value}%'
                },
                reversedStacks: false
            },

            tooltip: {
                valueSuffix: '%'
            },

            plotOptions: {
                series: {
                    stacking: 'normal',
                    shadow: false,
                    groupPadding: 0,
                    pointPlacement: 'on'
                }
            }
        });
    </script>
</body>

</html>