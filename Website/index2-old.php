<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contoh Tampilan Data Realtime</title>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body style="margin: 2em">
<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link" aria-current="page" href="index-old.php">Weather Station</a>
  </li>
  <li class="nav-item">
    <a class="nav-link active" href="index2-old.php">Monitoring Energi Panel Surya</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="index3-old.php">Monitoring Tip Bucket</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="index4-old.php">Monitoring Wind weather</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="index5-old.php">Status Motor Driver & LDR</a>
  </li>
</ul>
    <table id="example" class="table table-striped table-hover" cellspacing="0" width="100%">
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
    <nav aria-label="Page navigation example pagination">
        <ul class="pagination justify-content-end">
            <li class="page-item">
            <button class="page-link" id="prev">Previous</button>
            </li>
            <li class="page-item"><a class="page-link" id="page-number"></a></li>
            <li class="page-item">
            <button class="page-link" id="next">Next</button>
            </li>
        </ul>
    </nav>


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
        setInterval(loadRealtimeData, 60000); // 60000 milliseconds = 60 detik

        // Fungsi untuk mengambil data dari halaman tertentu
        function loadDataByPage(page) {
            $.ajax({
                url: 'realtime_data2.php', // Ganti dengan URL yang sesuai
                method: 'GET',
                data: { page: page },
                dataType: 'html',
                success: function(response) {
                    $('#realtime-data').html(response);
                    $('#page-number').text(page);
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
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>
