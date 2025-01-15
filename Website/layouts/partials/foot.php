<?php
// Tentukan base URL untuk assets
$baseUrl = 'https://rptugas.site/assets/';
?>

<script src="<?php echo $baseUrl; ?>vendor/libs/jquery/jquery.js"></script>
<script src="<?php echo $baseUrl; ?>vendor/libs/popper/popper.js"></script>
<script src="<?php echo $baseUrl; ?>vendor/js/bootstrap.js"></script>
<script src="<?php echo $baseUrl; ?>vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="<?php echo $baseUrl; ?>vendor/libs/hammer/hammer.js"></script>
<script src="<?php echo $baseUrl; ?>vendor/libs/i18n/i18n.js"></script>
<script src="<?php echo $baseUrl; ?>vendor/libs/typeahead-js/typeahead.js"></script>
<script src="<?php echo $baseUrl; ?>vendor/js/menu.js"></script>

<!-- endbuild -->

<!-- Vendors JS -->
<script src="<?php echo $baseUrl; ?>vendor/libs/moment/moment.js"></script>
<script src="<?php echo $baseUrl; ?>vendor/libs/select2/select2.js"></script>
<script src="<?php echo $baseUrl; ?>vendor/libs/@form-validation/popular.js"></script>
<script src="<?php echo $baseUrl; ?>vendor/libs/@form-validation/bootstrap5.js"></script>
<script src="<?php echo $baseUrl; ?>vendor/libs/@form-validation/auto-focus.js"></script>
<script src="<?php echo $baseUrl; ?>vendor/libs/cleavejs/cleave.js"></script>
<script src="<?php echo $baseUrl; ?>vendor/libs/cleavejs/cleave-phone.js"></script>
<script src="<?php echo $baseUrl; ?>js/form-layouts.js"></script>
<script src="<?php echo $baseUrl; ?>js/tables-datatables-basic.js"></script>
<script src="<?php echo $baseUrl; ?>vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>

<script src="<?php echo $baseUrl; ?>vendor/libs/flatpickr/flatpickr.js"></script>
<script src="<?php echo $baseUrl; ?>vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo $baseUrl; ?>vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>
<script src="<?php echo $baseUrl; ?>vendor/libs/jquery-timepicker/jquery-timepicker.js"></script>
<script src="<?php echo $baseUrl; ?>vendor/libs/pickr/pickr.js"></script>
<script src="<?php echo $baseUrl; ?>js/forms-pickers.js"></script>

<script src="<?php echo $baseUrl; ?>js/app-user-list.js"></script>

<!-- Main JS -->
<script src="<?php echo $baseUrl; ?>js/main.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
<!-- Page JS -->

<script>
    // select2 (jquery)
    $(function() {
        // Form sticky actions
        var topSpacing;
        const stickyEl = $('.sticky-element');

        // Init custom option check
        window.Helpers.initCustomOptionCheck();

        // Set topSpacing if the navbar is fixed
        if (Helpers.isNavbarFixed()) {
            topSpacing = $('.layout-navbar').height() + 7;
        } else {
            topSpacing = 0;
        }

        // sticky element init (Sticky Layout)
        if (stickyEl.length) {
            stickyEl.sticky({
                topSpacing: topSpacing,
                zIndex: 9
            });
        }

        var select2 = $('.select2');
        if (select2.length) {
            console.log('leng', select2.length);
            select2.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Select value',
                    dropdownParent: $this.parent()
                });
            });
        }
    });
</script>

<script>
    function confirmDelete(deleteUrl) {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger"
            },
            buttonsStyling: false
        });

        swalWithBootstrapButtons.fire({
            title: "Are you sure delete this data?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to delete URL if confirmed
                window.location.href = deleteUrl;
            }
        });
    }
</script>

<script>
    function initializeDataTable(){

        var textBtnCreate = "<?php echo $title_btn_create ?? 'Add New Data'; ?>";
        var dt_simply_table = $(".datatables-simply"),
            dt_simply; dt_simply = dt_simply_table.DataTable({
            dom: '<"row mx-2"' +
                '<"col-md-2"<"me-3"l>>' +
                '<"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0"fB>>' +
                '>t' +
                '<"row mx-2"' +
                '<"col-sm-12 col-md-6"i>' +
                '<"col-sm-12 col-md-6"p>' +
                '>',
            buttons: [{
                    extend: "collection",
                    className: "btn btn-label-primary dropdown-toggle me-2",
                    text: '<i class="bx bx-export me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
                    buttons: [{
                            extend: "print",
                            text: '<i class="bx bx-printer me-1"></i>Print',
                            className: "dropdown-item",
                            exportOptions: {
                                columns: ":not(.no-print)", // Mengabaikan kolom dengan kelas no-print
                            },
                            customize: function(win) {
                                $(win.document.body)
                                    .css("color", "#000")
                                    .css("border-color", "#000")
                                    .css("background-color", "#fff");
                                $(win.document.body)
                                    .find("table")
                                    .addClass("compact")
                                    .css("color", "inherit")
                                    .css("border-color", "inherit")
                                    .css("background-color", "inherit");
                            },
                        },
                        {
                            extend: "csv",
                            text: '<i class="bx bx-file me-1"></i>Csv',
                            className: "dropdown-item",
                            exportOptions: {
                                columns: ":not(.no-print)", // Mengabaikan kolom dengan kelas no-print
                            },
                        },
                        {
                            extend: "excel",
                            text: '<i class="bx bxs-file-export me-1"></i>Excel',
                            className: "dropdown-item",
                            exportOptions: {
                                columns: ":not(.no-print)", // Mengabaikan kolom dengan kelas no-print
                            },
                        },
                        {
                            extend: "pdf",
                            text: '<i class="bx bxs-file-pdf me-1"></i>Pdf',
                            className: "dropdown-item",
                            exportOptions: {
                                columns: ":not(.no-print)", // Mengabaikan kolom dengan kelas no-print
                            },
                        },
                        {
                            extend: "copy",
                            text: '<i class="bx bx-copy me-1"></i>Copy',
                            className: "dropdown-item",
                            exportOptions: {
                                columns: ":not(.no-print)", // Mengabaikan kolom dengan kelas no-print
                            },
                        },
                    ],
                },
                {
                    text: '<i class="bx bx-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">' + textBtnCreate + '</span>',
                    className: "create-new btn btn-primary",
                    attr: {
                        onClick: "showCreateButton()",
                    },
                },
            ],
            order: [
                // [0, "desc"]
            ], // Contoh pengurutan
            displayLength: 7,
            lengthMenu: [7, 10, 25, 50, 75, 100],
            language: {
                lengthMenu: "<select class='form-select'>" +
                    "<option value='7'>7</option>" +
                    "<option value='10'>10</option>" +
                    "<option value='25'>25</option>" +
                    "<option value='50'>50</option>" +
                    "<option value='75'>75</option>" +
                    "<option value='100'>100</option>" +
                    "</select>"
            }
        });
    }
</script>