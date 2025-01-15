$(function () {
    var dt_basic_table = $(".datatables-basic"),
        dt_basic;
    var dt_basic_table2 = $(".datatables-basic2"),
        dt_basic2;
    var dt_basic_table_client = $(".datatables-basic-client"),
        dt_basic_client;

    if (dt_basic_table.length) {
        dt_basic = dt_basic_table.DataTable({
            // dom: '<"card-header flex-column flex-md-row"<"row"<"col-sm-12 col-md-4"l><"col-sm-12 col-md-4 d-flex justify-content-center"f><"col-sm-12 col-md-4 dt-action-buttons text-end pt-3 pt-md-0"B>>><"row"<"col-sm-12"t>><"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            dom:
            '<"row mx-2"' +
            '<"col-md-2"<"me-3"l>>' +
            '<"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0"fB>>' +
            '>t' +
            '<"row mx-2"' +
            '<"col-sm-12 col-md-6"i>' +
            '<"col-sm-12 col-md-6"p>' +
            '>',
            buttons: [
                {
                    extend: "collection",
                    className: "btn btn-label-primary dropdown-toggle me-2",
                    text: '<i class="bx bx-export me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
                    buttons: [
                        {
                            extend: "print",
                            text: '<i class="bx bx-printer me-1"></i>Print',
                            className: "dropdown-item",
                            exportOptions: {
                                columns: ":not(.no-print)", // Mengabaikan kolom dengan kelas no-print
                            },
                            customize: function (win) {
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
                    text: '<i class="bx bx-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Add New Data</span>',
                    className: "create-new btn btn-primary",
                    attr: {
                        onClick: "showCreateButton()",
                    },
                },
            ],
            order: [[2, "desc"]], // Contoh pengurutan
            displayLength: 7,
            lengthMenu: [7, 10, 25, 50, 75, 100],
        });
    }
    if (dt_basic_table2.length) {
        dt_basic2 = dt_basic_table2.DataTable({
            dom: '<"card-header flex-column flex-md-row"<"row"<"col-sm-12 col-md-4"l><"col-sm-12 col-md-4 d-flex justify-content-center"f><"col-sm-12 col-md-4 dt-action-buttons text-end pt-3 pt-md-0"B>>><"row"<"col-sm-12"t>><"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            buttons: [
                {
                    extend: "collection",
                    className: "btn btn-label-primary dropdown-toggle me-2",
                    text: '<i class="bx bx-export me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
                    buttons: [
                        {
                            extend: "print",
                            text: '<i class="bx bx-printer me-1"></i>Print',
                            className: "dropdown-item",
                            exportOptions: {
                                columns: ":not(.no-print)", // Mengabaikan kolom dengan kelas no-print
                            },
                            customize: function (win) {
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
                    text: '<i class="bx bx-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Add New Data</span>',
                    className: "create-new btn btn-primary",
                    attr: {
                        onClick: "showCreateButtonv2()",
                    },
                },
            ],
            order: [[2, "desc"]], // Contoh pengurutan
            displayLength: 7,
            lengthMenu: [7, 10, 25, 50, 75, 100],
        });
    }
    if (dt_basic_table_client.length) {
        dt_basic_client = dt_basic_table_client.DataTable({
            dom: '<"card-header flex-column flex-md-row"<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-end"fB>>><"row"<"col-sm-12"t>><"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            buttons: [
                {
                    extend: "collection",
                    className: "btn btn-label-primary dropdown-toggle me-2",
                    text: '<i class="bx bx-export me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
                    buttons: [
                        {
                            extend: "print",
                            text: '<i class="bx bx-printer me-1"></i>Print',
                            className: "dropdown-item",
                            exportOptions: {
                                columns: ":not(.no-print)", // Mengabaikan kolom dengan kelas no-print
                            },
                            customize: function (win) {
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
            ],
            order: [[2, "desc"]], // Contoh pengurutan
            displayLength: 7,
            lengthMenu: [7, 10, 25, 50, 75, 100],
        });
    }

    // Menghilangkan kelas form control keukuran default
    setTimeout(() => {
        $(".dataTables_filter .form-control").removeClass("form-control-sm");
        $(".dataTables_length .form-select").removeClass("form-select-sm");
    }, 300);
});
