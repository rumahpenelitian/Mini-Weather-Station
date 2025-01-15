/**
 * Page User List
 */

'use strict';

// Datatable (jquery)
$(function () {

  // CSRF TOKEN
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  // take snapshot
  
  $('#ulangi').hide();
  Webcam.set({
    width: 300,
    height: 400,
    image_format: 'jpeg',
    jpeg_quality: 90,
  });
  Webcam.attach('#my_camera');
  function take_snapshot() {
    Webcam.snap(function (data_uri) {
      $(".image-absen").val(data_uri);
      document.getElementById('results').innerHTML = '<img src="' + data_uri + '"/>';
      $('#my_camera').hide();
      $('#ulangi').show();
    });
  }
  $('#ulangi').on('click', function (e) {
    $('#results').html('');
    $('#my_camera').show();
    $('#ulangi').hide();
    $('.image-absen').val('');
  })
  $('#take_in').on('click', function (e) {
    take_snapshot();
  })
  // ATTENDANCE
  $('#submit').on('click', function (e) {
    e.preventDefault();
    $('#error').html('');
    var urlNya = "attendance";
    var id = $('#id').val();
    // var note_checkout = $('#note_checkout').val();
    let image = $('.image-absen').val();
    var clock = $(this).val();
    if (image == null || image == '') {
      alert('Please take a foto!')
    } else {
      $(this).html('Loading..');
      $(this).attr('disabled', true);
      $.ajax({
        data: {
          id: id,
          image: image,
          // note_checkout: note_checkout,
        },
        url: urlNya,
        type: 'POST',
        dataType: 'json',
        success: function (data) {
          if (data.success) {
            notification('success', 'Berhasil ' + clock);
            location.reload();
          } else {
            notification('error', data.error);
            // $('.note_checkout').addClass('has-error has-feedback');
            $('#submit').html(clock );
            $('#submit').attr('disabled', false);
          }
  
        },
        error: function (data) {
          notification('error', data.responseJSON.error);
          $('#submit').html(clock);
          $('#submit').attr('disabled', false);
        }
      });
    }

  });
  function notification(apa, msg) {
    Swal.fire({
      position: "center",
      icon: apa,
      title: msg,
      showConfirmButton: false,
      timer: 1500
    });
  }

  var userSelect2 = $('#usr_report');
  if (userSelect2.length) {
    var $this = userSelect2;
    $this.wrap('<div class="position-relative"></div>').select2({
      placeholder: 'Select User',
      dropdownParent: $this.parent()
    });
  }

  // PICKER DATE RANEG
  $('#date').flatpickr({
    mode: 'range'
  });

  // PICKER DATE RANEG
  $('#date_report').flatpickr({
    mode: 'range'
  });

  // DATE
  $('#date').on('change', function () {
    filter();
  })
  // DATE Report
  $('#date_report').on('change', function () {
    filter_report();
  })

  // USER
  $('#usr_report').on('change', function () {
    filter_report();
  })

  function filter() {
    var date = $('#date').val();
    load_data(date);
  }

  function filter_report() {
    var date = $('#date_report').val();
    var usr = $('#usr_report').val();
    load_data_report(date, usr);
  }
  load_data();
  function load_data(date = '') {

    var usr = $('#usr').val();
    $('.datatables-users').DataTable({
      ajax: {
        url: baseUrl + 'admin/getattendance',
        method: 'POST',
        data: { date: date, usr: usr }
      },
      processing: true,
      serverSide: true,
      "bPaginate": true,
      "bDestroy": true,
      "lengthMenu": [
        [10, 25, 50, 100, -1],
        [10, 25, 50, 100, "All"]
      ],
      bDestroy: true,
      columns: [
        {
          data: 'DT_RowIndex',
          name: 'DT_RowIndex',
          "sortable": false,
          className: 'dt-center'
        },
        {
          data: 'date',
          name: 'date',
          orderable: false,
          className: 'dt-center'
        },
        {
          data: 'check_in',
          name: 'check_in',
          orderable: false,
          className: 'dt-center'
        },
        {
          data: 'check_out',
          name: 'check_out',
          orderable: false,
          className: 'dt-center'
        },
        // {
        //   data: 'geo_checkin',
        //   name: 'geo_checkin',
        //   orderable: false,
        //   className: 'dt-center'
        // },
        // {
        //   data: 'geo_checkout',
        //   name: 'geo_checkout',
        //   orderable: false,
        //   className: 'dt-center'
        // },
        {
          data: 'shift',
          name: 'shift',
          orderable: false,
          className: 'dt-center'
        },
        {
          data: 'status',
          name: 'status',
          orderable: false,
          className: 'dt-center'
        }
      ],
      order: [],
    });
  }

  load_data_report();
  function load_data_report(date = '', usr = '') {

    $('.datatables-users-report').DataTable({
      ajax: {
        url: baseUrl + 'admin/getattendance',
        method: 'POST',
        data: { date: date, usr: usr }
      },
      processing: true,
      serverSide: true,
      "bPaginate": true,
      "bDestroy": true,
      "lengthMenu": [
        [10, 25, 50, 100, -1],
        [10, 25, 50, 100, "All"]
      ],
      bDestroy: true,
      columns: [
        {
          data: 'DT_RowIndex',
          name: 'DT_RowIndex',
          "sortable": false,
          className: 'dt-center'
        },
        {
          data: 'name',
          name: 'name',
          orderable: false,
          className: 'dt-center'
        },
        {
          data: 'date',
          name: 'date',
          orderable: false,
          className: 'dt-center'
        },
        {
          data: 'check_in',
          name: 'check_in',
          orderable: false,
          className: 'dt-center'
        },
        {
          data: 'check_out',
          name: 'check_out',
          orderable: false,
          className: 'dt-center'
        },
        // {
        //   data: 'geo_checkin',
        //   name: 'geo_checkin',
        //   orderable: false,
        //   className: 'dt-center'
        // },
        // {
        //   data: 'geo_checkout',
        //   name: 'geo_checkout',
        //   orderable: false,
        //   className: 'dt-center'
        // },
        {
          data: 'shift',
          name: 'shift',
          orderable: false,
          className: 'dt-center'
        },
        {
          data: 'status',
          name: 'status',
          orderable: false,
          className: 'dt-center'
        }
      ],
      order: [],
    });
  }
});
