$(document).on('blur', '[data-validator]', function () {
    new Validator($(this));
})

$(document).on('keyup', '.chg_password', function () {
    new Validator($(this));
})
// Start: Tooltip
var message_display_time = 5000;
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
    setTimeout(function () {
        if ($('.alert').not('.ajax_message').length) {
            $(".alert").alert('close')
        }
    }, message_display_time);

    $("#search-btn-h").on('click', function () {
        if ($('#searchbox').hasClass('show')) {
            $('#searchbox').removeClass('show');
        }
    });
});
// End: Tooltip

/* Start :: Nevgation Menu slide functionality*/
$('.nav-btn, .navclose').click(function (e) {
    if ($('.sidebar-nav').hasClass("nav-full")) {
        $('.sidebar-nav').removeClass("nav-full");
        $('body').removeClass('no-scroll');
    } else {
        $('.sidebar-nav').addClass("nav-full");
        $('body').addClass('no-scroll');
    }
});

$(".nav-btn").click(function () {
    $("#main").toggleClass("main-space");
    $("footer").toggleClass("main-space");
});


// $(".sidebar-nav").hover(function() {
//     $(this).toggleClass("hover_slider-menu");
// });

$(".navbar-nav > li.nav-item .active").focus();
/* End :: Nevgation Menu slide functionality*/

/* Search Section*/
$(".search-btn").click(function () {
    $("#searchbox").removeClass("hide");
});

/* Search Section*/
$(".search-btn").click(function () {
    $("#searchbox").removeClass("hide");
});

/* Start :: Search Section*/
$(".close-btn").click(function () {
    $(".reset-btn").trigger("click");
    $("#searchbox").addClass("hide");
});
/* Emd :: Search Section*/

$(document).on("change", "input.action-checkbox[name='id[]']", function () {
    if ($("input.action-checkbox[name='id[]']").filter(':checked').length > 0) {
        $("#normal_btns").addClass("hide");
        $("#action_btns").removeClass("hide");
    } else {
        $("#action_btns").addClass("hide");
        $("#normal_btns").removeClass("hide");
    }

    if ($("input.action-checkbox[name='id[]']").filter(':checked').length == $("input.action-checkbox[name='id[]']").length) {
        $("#selectAll").prop('checked', true);
    } else {
        $("#selectAll").prop('checked', false);
    }
});

/* Start: Check All */
function checkAll() {
    if ($("#selectAll").prop('checked') == true) {
        $("input.action-checkbox[name='id[]']").each(function () {
            $(this).prop("checked", true);
            $("#normal_btns").addClass("hide");
            $("#action_btns").removeClass("hide");
        });
    } else {
        $("input.action-checkbox[name='id[]']").each(function () {
            $(this).prop("checked", false);
            $("#action_btns").addClass("hide");
            $("#normal_btns").removeClass("hide");
        });
    }
}

/* Tinymce */
tinymce.init({
    selector: '.tinymce',
    "branding": false,
    'language': 'en',
    'plugins': [
        ' autolink lists link image    hr anchor pagebreak',
        '  code textcolor insertdatetime  fullscreen',
        '  nonbreaking save table searchreplace preview ',
        ' print advlist contextmenu visualblocks charmap '
    ],
    'toolbar': 'code newdocument undo redo | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link unlink anchor image | preview fullscreen table | hr removeformat subscript superscript charmap | forecolor backcolor spellchecker blockquote  | styleselect fontselect fontsizeselect',
    'menubar': true,
    'toolbar_items_size': 'small',
    'image_advtab': true,
    'relative_urls': false,
    'browser_spellcheck': true,
    'forced_root_block': 'p',
    setup: function (editor) {
        editor.on('init change', function () {
            editor.save();
        });
    },
    images_upload_handler: function (blobInfo, success, failure) {
        var xhr, formData;
        xhr = new XMLHttpRequest();
        xhr.withCredentials = false;
        xhr.open('POST', upload_url);
        xhr.setRequestHeader("X-CSRF-Token", csrf_token);
        xhr.onload = function () {
            var json;
            if (xhr.status != 200) {
                failure('HTTP Error: ' + xhr.status);
                return;
            }
            json = JSON.parse(xhr.responseText);

            if (!json || typeof json.location != 'string') {
                failure('Invalid JSON: ' + xhr.responseText);
                return;
            }
            success(json.location);
        };
        formData = new FormData();
        formData.append('file', blobInfo.blob(), blobInfo.filename());
        formData.append('folder', 'tinymce');
        formData.append('size', '20');
        xhr.send(formData);
    }
});


function submitactionform(action) {
    $("input[name='bulk-action']").val(action);
    var html = '';
    if (action == 'delete') {
        var check_dependancy = $("#frmlist").attr('data-dependent');
        if (check_dependancy !== null && check_dependancy != undefined) {
            // checkDependantData
            var formData = $("#frmlist").serialize();
            $.ajax({
                type: 'POST',
                url: 'category/checkdependantdata',
                data: formData,
                success: function (data) {
                    if (data.success == false) {
                        html = '<h6>Selected category(s) has reference data. If you delete category(s) then its associated data also deleted. </h6><br />';
                        $('#dependent_msg').html(html);
                    }

                }
            });
        }
    } else {
        $('#dependent_msg').html(html);
    }
    var actionText = action;
    if (action == 'active') {
        actionText = 'activate';
    } else if (action == 'inactive') {
        actionText = 'inactivate';
    }
    $(".modal-action-name").html(actionText.toUpperCase());
    $("#confirm-action-modal").modal("show");
}

$('#confirm-action-submit').click(function () {
    $("#frmlist").submit();
});

function sort() {
    var sortable_element_length = $('*[id^="display_order_"]').length;
    if (sortable_element_length >= 2) {
        $("#sortable").sortable({
            stop: function () {
                var sort_url = $(this).data('sort-url');
                var display_order = $(this).find('.ui-sortable-handle').map(function () {
                    return ($(this).attr('id')).replace('display_order_', '');
                }).get();
                $.ajax({
                    url: sort_url,
                    type: "POST",
                    data: {
                        'display_order': display_order
                    },
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', csrf_token);
                    },
                    success: function (response) {
                        $(".ajax_message").removeClass("d-none");
                        $(".ajax_message").find('span').addClass('successmessage').addClass('text-success').html(response.success);
                        setTimeout(function () {
                            $(".ajax_message").find('span').html('');
                        }, message_display_time);
                    },
                    error: function (response) {

                    }
                });
            }
        });
        $("#sortable").disableSelection();
    }
}
$(function () {
    sort();
});
/* Dropify Image Upload */
dropify = $('.dropify').dropify();

$('.image-upload').change(function () {
    $(this).attr("disabled", "dsiabled");
    $(this).parent(".dropify-wrapper").addClass("disabled-input");

    var folder = $(this).data('folder');
    var size = $(this).data('size');
    var file_input_name = $(this).attr('name');
    var text_input_name = file_input_name.replace("temp_", "");

    dropify = $("input[name='" + file_input_name + "']").dropify();

    formData = new FormData();
    formData.append('file', $(this)[0].files[0]);
    formData.append('folder', folder);
    formData.append('size', size);

    $.ajax({
        url: upload_url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-Token', csrf_token);
            $("input[name='" + file_input_name + "']").addClass("inprocess");
        },
        success: function (response) {
            $("input[name='" + file_input_name + "']").parent(".dropify-wrapper").addClass("disabled-input");
            $("input[name='" + file_input_name + "']").removeAttr("disabled");
            $('input[name="' + file_input_name + '"]').removeAttr('data-validator');
            $('input[name="' + text_input_name + '"]').parent('.form-group').removeClass('has-error');
            $("#error_" + text_input_name).addClass("d-none");
            $("#error_" + text_input_name).html("");

            $("input[name='" + text_input_name + "']").val(response.file);
            $("input[name='" + file_input_name + "']").removeClass("inprocess");

            dropify = dropify.data('dropify');
            dropify.resetPreview();
            dropify.clearElement();
            dropify.settings['defaultFile'] = response.location;
            dropify.destroy();
            dropify = dropify.init();

        },
        error: function (response) {
            $("input[name='" + file_input_name + "']").parent(".dropify-wrapper").addClass("disabled-input");
            $("input[name='" + file_input_name + "']").removeAttr("disabled");

            $("#error_" + text_input_name).removeClass("d-none");
            if (typeof response.responseJSON.errors.file != 'undefined') {
                var error_message = '';
                $.each(response.responseJSON.errors.file, function (index, value) {
                    error_message += value + "<br>";
                });
                $("#error_" + text_input_name).html(error_message);
            } else {
                $("#error_" + text_input_name).html(response.responseJSON.message);
            }

            $("input[name='" + text_input_name + "']").val("");
            var lbl_id = file_input_name;
            if ($('label[for="' + lbl_id + '"]').find('span.text-danger').length > 0) {
                if (file_input_name == 'temp_banner') {
                    $('input[name="' + file_input_name + '"]').attr('data-validator', 'required_if:has_banner');
                } else {
                    $('input[name="' + file_input_name + '"]').attr('data-validator', 'required');
                }
            }

            $("input[name='" + file_input_name + "']").removeClass("inprocess");

            dropify = dropify.data('dropify');
            dropify.resetPreview();
            dropify.clearElement();
            dropify.settings['defaultFile'] = '';
            dropify.destroy();
            dropify = dropify.init();
        }
    });
});

$(document).on("click", ".dropify-clear", function () {
    var text_input_name = ($(this).parent(".dropify-wrapper").find("input").attr("name")).replace("temp_", "");
    $("input[name='" + text_input_name + "']").val("");
    var lbl_id = 'temp_' + text_input_name;
    if ($('label[for="' + lbl_id + '"]').find('span.text-danger').length > 0) {
        if (text_input_name == 'banner') {
            $('input[name="temp_' + text_input_name + '"]').attr('data-validator', 'required_if:has_banner');
        } else {
            $('input[name="temp_' + text_input_name + '"]').attr('data-validator', 'required');
        }
    }

});

$(".switch input[type='checkbox']").change(function () {
    if ($(this).attr("data-show") != '') {
        if ($(this).prop('checked') == true) {
            $($(this).attr("data-show")).show();
        } else {
            $($(this).attr("data-show")).hide();
        }
    }
});

$(".switch input[type='checkbox']").trigger("change");

$("form").on('submit', function () {

    $(this).find('[data-validator]').each(function () {
        new Validator($(this));
    });
    if ($('.has-error').length == 0) {
        // submit more than once return false
        $(this).submit(function () {
            return false;
        });
        $(".loader").show();
    } else {
        return false;
    }
});
$("form").find(':input:enabled:visible:first').focus();

$(document).ajaxStart(function () {
    $(".loader").fadeIn("slow");
});

$(document).ajaxStop(function () {
    $(".loader").fadeOut("slow");
});


function displayDetail(id) {
    $(".detail-section").addClass('d-none');
    $("#detail_" + id).removeClass('d-none');
}

$(document).ready(function () {
    if ($('.admintable tr.noreocrd').length == 0) {
        var dir = $('.admintable').attr('defaultdir');
        var order = $('.admintable').attr('data-orders');
        var target = $('.admintable').attr('data-target');
        /* Set data-title attribute this attribute is set export file title */
        var reportTitle = $('.admintable').attr('data-title');
        /* Set data-export attribute which columns you needed in the exportt option like 1,2,3,4 */
        var exportColums = $('.admintable').attr('data-export');
        /* Set data-isexport attribute in blade file if you need enable export option in datatable */
        var isExport = $('.admintable').attr('data-isexport');
        if (dir !== null && dir != undefined) {
            dir = dir;
        } else {
            dir = 'asc';
        }
        if (order == null && order == undefined) {
            order = 0;
        }
        if (target == null && target == undefined) {
            target = 3;
        }
        if (reportTitle == null && reportTitle == undefined) {
            reportTitle = 'Contact Us Report';
        }
        if (exportColums == null && exportColums == undefined) {
            exportColums = '3,4,5';
        }
        if (isExport == null && isExport == undefined) {
            isExport = 0;
        }
        if (isExport) {
            var buttonCommon = {
                exportOptions: {
                    format: {
                        body: function (data, column, row) {
                            data = data.replace(/<br\s*\/?>/ig, "\r\n");
                            data = data.replace(/<.*?>/g, "");
                            data = data.replace("&amp;", "&");
                            data = data.replace("&nbsp;", "");
                            data = data.replace("&nbsp;", "");
                            return data;
                        }
                    }
                }
            };
        }
        admintable = $('.admintable').DataTable({
            "order": [
                [parseInt(order), dir]
            ],
            "lengthMenu": pagingLenthOptions,
            "pageLength": parseInt(defaultPagingLenth),
            "searching": false,
            language: {
                paginate: {
                    next: '&#187;',
                    previous: '&#171;'
                }
            },
            responsive: {
                details: {
                    target: parseInt(target),
                    type: 'column'
                }
            },
            "emptyTable": 'No data available',
            "columnDefs": [{
                    "targets": 'nosort',
                    "orderable": false
                },
                {
                    "visible": false,
                    "targets": 0
                },
                {
                    className: 'control',
                    targets: parseInt(target)
                }
            ],
            // "dom": '<"pull-left"f>Br<"pull-right"l>tip',
            "dom": '<"row hide js-buttons"<"col-sm-5 rsp_tac"B><"col-sm-3"><"col-sm-4"f>>rt<"row justify-content-between"<"col-xl-auto py-1 d-flex justify-content-center justify-content-xl-start"p><"col-xl-auto text-center"i><"col-xl-auto py-1 d-flex justify-content-center justify-content-xl-end align-items-center"l>>',
            "buttons": [
                $.extend(true, {}, buttonCommon, {
                    extend: 'excel',
                    title: reportTitle,
                    className: 'btn btn-info excel',
                    text: 'Export To Excel',
                    exportOptions: {
                        columns: [exportColums]
                    },
                    footer: false
                })
            ],
            drawCallback: function () {
                if (isExport) {
                    $(".js-buttons").show();
                }
            },

        });
        $(window).resize(function () {
            setTimeout(function () {
                admintable.responsive.recalc();
            }, 500);
        });
    } else {
        $('.admintable thead').remove();
    }


    if ($('.sort_table tr.noreocrd').length == 0) {
        var dir = $('.sort_table').attr('defaultdir');
        var order = $('.sort_table').attr('data-orders');
        var target = $('.sort_table').attr('data-target');
        if (dir !== null && dir != undefined) {
            dir = dir;
        } else {
            dir = 'asc';
        }
        if (order == null && order == undefined) {
            order = 0;
        }
        if (target == null && target == undefined) {
            target = 3;
        }
        oTable = $('.sort_table').DataTable({
            order: [parseInt(order), 'asc'],
            language: {
                paginate: {
                    next: '&#187;',
                    previous: '&#171;'
                }
            },
            columnDefs: [{
                    orderable: true,
                    className: 're-order',
                    targets: parseInt(order)
                },
                {
                    "targets": 'nosort',
                    "orderable": false
                },
                {
                    "visible": false,
                    "targets": 0
                },
                {
                    className: 'control',
                    targets: parseInt(target)
                }
            ],
            rowReorder: {
                //Changed selecotr to the re-order class anme
                selector: '.re-order',
                //Set dataSrc to the column containing the re-order display order number
                dataSrc: parseInt(order),
                update: true
            },
            "paging": true,
            "lengthMenu": pagingLenthOptions,
            "pageLength": parseInt(defaultPagingLenth),
            "bFilter": false,
            "searching": false,
            responsive: {
                details: {
                    target: parseInt(target),
                    type: 'column'
                }
            },
            "emptyTable": 'No data available',
            "dom": '<"row hide js-buttons"<"col-sm-5 rsp_tac"><"col-sm-3"><"col-sm-4"f>>rt<"row justify-content-between"<"col-xl-auto d-flex justify-content-center justify-content-xl-start"p><"col-xl-auto text-center"i><"col-xl-auto py-1 d-flex justify-content-center justify-content-xl-end align-items-center"l>>'
        });
        // oTable.rowReordering();
        oTable.on('row-reorder', function (e, diff, edit) {
            var sort_array = [];
            for (var i = 0, ien = diff.length; i < ien; i++) {
                var newVal = diff[i].newData;
                var oldVal = diff[i].oldData;
                var rowData = oTable.row(diff[i].node).data();
                var currentRowID = rowData[0];
                sort_array[i] = [];
                sort_array[i].push(currentRowID);
                sort_array[i].push(newVal);
                sort_array[i].push(oldVal);
            }
            if (sort_array.length > 0) {
                $.ajax({
                    url: $('.sort_table').attr('data-sort-url'),
                    type: "POST",
                    data: {
                        'display_order': sort_array
                    },
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', csrf_token);
                    },
                    success: function (response) {
                        updateModifiedDateData(response);
                        $(".ajax_message").removeClass("d-none");
                        $(".ajax_message").find('span').addClass('successmessage').addClass('text-success').html(response.success);
                        setTimeout(function () {
                            $(".ajax_message").find('span').html('');
                        }, message_display_time);
                    },
                    error: function (response) {

                    }
                });
            }
        });

        $(window).resize(function () {
            setTimeout(function () {
                oTable.responsive.recalc();
            }, 500);
        });

    } else {
        $('.sort_table thead').remove();
    }

});

//slider page uploder call
$(document).ready(function () {
    var cfr_data = $('meta[name="csrf-token"]').attr('content');
    if ($('#basic-uploader').length) {
        // ================================
        $('#basic-uploader').fileupload({
                // Uncomment the following to send cross-domain cookies:
                xhrFields: {
                    withCredentials: true
                },
                url: uploadsliderimages,
                formData: {
                    '_token': cfr_data,
                    'folder': 'slider'
                },
                //dataType: 'json',
                autoUpload: false,
                acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
                maxFileSize: parseInt(max_image_size_inMB) * 1000000, //100000000 = 10 MB
                dropZone: $('.dropzone'),
                disableImageResize: true,
                imageMaxWidth: parseInt(max_image_width),
                imageMaxHeight: parseInt(max_image_height),
                previewMaxWidth: 50,
                previewMaxHeight: 50,
                previewCrop: true,
                messages: {
                    maxFileSize: 'File is too large. Maximum allowed upload file size is ' + max_image_size_inMB + ' MB. Hence file is not uploaded',
                    acceptFileTypes: 'File type not allowed. Hence file is not uploaded'
                }
            })
            .on('fileuploadstart', function () {
                $('[role="progressbar"]').removeClass('hide');
                $('#addfiles').hide();
                // $('#startall').show();
            })
            .on('fileuploadfinished', function (e, data) {
                var uploadtpl = $('.template-upload').length;
                if (uploadtpl == 0) {
                    $('#addfiles').show();
                    //  $('.upload-lists').html('');
                    reloadSliderData();
                }
            })
            .on('fileuploadprocessalways', function (e, data) {
                var error = data.files[data.index].error;
                if (typeof error !== "undefined" && error != '') {
                    setTimeout(function () {
                        data.context.addClass('d-none').removeClass('template-upload');
                    }, 1000);
                }
            });
    }

    var cfr_data1 = $('meta[name="csrf-token"]').attr('content');
    if ($('#gallery-basic-uploader').length) {
        // ================================
        $('#gallery-basic-uploader').fileupload({
                // Uncomment the following to send cross-domain cookies:
                xhrFields: {
                    withCredentials: true
                },
                url: uploadgalleryimages,
                formData: {
                    '_token': cfr_data1,
                    'folder': 'galleryimage'
                },
                //dataType: 'json',
                autoUpload: false,
                acceptFileTypes: /(\.|\/)(gif|jpeg|png|mp4|3gp|ogg|wmv|webm|avi|flv)$/i,
                maxFileSize: parseInt(max_gallery_image_size_inMB) * 1000000, //100000000 = 10 MB
                dropZone: $('.dropzone'),
                disableImageResize: true,
                imageMaxWidth: parseInt(max_image_width_gallery),
                imageMaxHeight: parseInt(max_image_height_gallery),
                previewMaxHeight: 50,
                previewCrop: true,
                messages: {
                    maxFileSize: 'File is too large. Maximum allowed upload file size is ' + max_gallery_image_size_inMB + ' MB. Hence file is not uploaded',
                    acceptFileTypes: 'File type not allowed. Hence file is not uploaded'
                }
            })
            .on('fileuploadstart', function () {
                $('[role="progressbar"]').removeClass('hide');
                $(this).find('#addfiles').hide();
                // $('#startall').show();
            })
            .on('fileuploadfinished', function (e, data) {
                var uploadtpl = $(this).find('.template-upload').length;
                if (uploadtpl == 0) {
                    $(this).find('#addfiles').show();
                }
            })
            .on('fileuploadprocessalways', function (e, data) {
                var error = data.files[data.index].error;
                if (typeof error !== "undefined" && error != '') {
                    setTimeout(function () {
                        data.context.addClass('d-none').removeClass('template-upload');
                    }, 1000);
                }
            });
    }

});

//reload slider list
function reloadSliderData(id) {
    var formData;
    $('.successmessage').html('');
    formData = {
        '_token': $('meta[name="csrf-token"]').attr('content'),
        'reload': 'yes'
    };

    if (id !== null && id != undefined) {
        formData.id = id;
    }

    var search = getUrlParameter('search');
    var status = getUrlParameter('status');
    if (search !== null && search != undefined) {
        formData.search = search;
    }
    if (status !== null && status != undefined) {
        formData.status = status;
    }
    $.ajax({
        type: 'POST',
        url: 'slider/list',
        data: formData,
        success: function (data) {
            $('#sliderlist').html(data.html);
            $('.successmessage').html('');
            $(".ajax_message").removeClass("d-none");
            if ($('.upload-lists tbody tr.template-download[pk_id]').length > 0 || id > 0) {
                $(".ajax_message").find('span').addClass('successmessage').addClass('text-success').html(data.success);
                setTimeout(function () {
                    $(".ajax_message").find('span').html('');
                }, message_display_time);
            }
            if ($('.upload-lists tbody tr').length) {
                $('.upload-lists tbody').html('');
            }
            var slideritem = $('#sliderlist').find('#sortable').find('[id^="display_order"]').length;
            if (slideritem > 0) {
                $('#addimagebtn').removeClass('hide');
            }
            if (slideritem == 0) {
                $('#addimagebtn').addClass('hide');
            }
            sort();
            $('[role="progressbar"]').addClass('hide');
            $("#slider-confirm-action").modal("hide");
            $("#selectAll").prop('checked', false);
            $("#action_btns").addClass("hide");
            $("#normal_btns").removeClass("hide");
            init_magnificPopup();
            slidertitleeditable();
        }
    });
}
//reload slider list

//reload gallery list
function reloadGalleryData(id) {
    var formData;
    $('.successmessage').html('');
    formData = {
        '_token': $('meta[name="csrf-token"]').attr('content'),
        'reload': 'yes'
    };

    if (id !== null && id != undefined) {
        formData.id = id;
    }

    var search = getUrlParameter('search');
    var status = getUrlParameter('status');
    if (search !== null && search != undefined) {
        formData.search = search;
    }
    if (status !== null && status != undefined) {
        formData.status = status;
    }
    $.ajax({
        type: 'POST',
        url: 'gallery/list',
        data: formData,
        success: function (data) {
            $('#gallerylist').html(data.html);
            $('.successmessage').html('');
            $(".ajax_message").removeClass("d-none");
            $(".ajax_message").find('span').addClass('successmessage').addClass('text-success').html(data.success);
            setTimeout(function () {
                $(".ajax_message").find('span').html('');
            }, message_display_time);

            var galleryitem = $('#gallerylist').find('#sortable').find('[id^="display_order"]').length;
            if (galleryitem > 0) {
                $('#addimagebtn').removeClass('hide');
            }
            if (galleryitem == 0) {
                $('#addimagebtn').addClass('hide');
            }
            sort();
            $('[role="progressbar"]').addClass('hide');
            $("#gallery-confirm-action").modal("hide");
            $("#selectAll").prop('checked', false);
            $("#action_btns").addClass("hide");
            $("#normal_btns").removeClass("hide");
            init_magnificPopup();
        }
    });
}
//reload gallery list

$(document).on("click", ".js-view-contact", function () {
    var id = $(this).attr('id');
    id = id.replace("link_msg_", "");
    $("#js-message-body").html($('#message_' + id).html());
    $("#contact-message").modal("show");

});

function removeslider(id) {
    $(".modal-action-name").html('DELETE');
    $("#slider-confirm-action").modal("show");
    $('#confirm-slider-delete').click(function () {
        reloadSliderData(id);
    });
}

function removegallery(id) {
    $(".modal-action-name").html('DELETE');
    $("#gallery-confirm-action").modal("show");
    $("#gallery-confirm-action #dependent_msg").html("Are you sure you want to <b>DELETE</b> this gallery?");
    $('#confirm-gallery-delete').click(function () {
        reloadGalleryData(id);
    });
}

function addExtraData() {
    $('#youtube_video_url_form').find('[data-validator]').each(function () {
        new Validator($(this));
    });
    if ($('#youtube_video_url_form .has-error').length == 0) {
        if ($('#youtube_video_link').val() != "") {
            if ($('.link_list_area').length == 0) {
                $('.external_file_link_list').parent().before('<h5 class="text-center link_title">Youtube video Links</h5>');
            }
            var str_html = '';
            str_html += '<tr class="link_list_area">';
            str_html += '<td width="6%">' + ($('.link_list_area').length + 1) + '</td>';
            str_html += '<td><a href="' + $('#youtube_video_link').val() + '" target="_blank">' + $('#youtube_video_link').val() + '</a><input type="hidden" id="efl_'+($('.link_list_area').length + 1) +'" name="external_file_links[]" value="' + $('#youtube_video_link').val() + '" /></td>';
            str_html += '<td width="6%" class="text-center bg-dark"><a href="javascript:void(0)" class="remove_link" title="Remove Link"><i class="icon-close-icon top-icon" title="Remove Link"></i></a></td>';
            str_html += '</tr>';
            $('.external_file_link_list').append(str_html);
            $('#externallink-modal').modal('hide');
            $('#youtube_video_link').val('');
            $('#youtube_video_link').removeClass('is-invalid is-valid');
            $('.form-group').removeClass('has-error');
            $(".youtube-help-text").removeClass("text-danger").addClass("text-muted");
            $('.youtube_video_url_form .errormessage').html('');
        }

    } else {
        return false;
    }
}

$('.youtube_video_url_popup').on('shown.bs.modal', function (e) {
    $('#youtube_video_link').val('');
    $('#youtube_video_link').removeClass('is-invalid is-valid');
    $('.form-group').removeClass('has-error');
    $(".youtube-help-text").removeClass("text-danger").addClass("text-muted");
    $('.youtube_video_url_form .errormessage').html('');
});

$(document).on('click', '.remove_link', function (e) {
    $(this).parent().parent().remove();
    if ($('.link_list_area').length == 0) {
        $('.link_title').remove();
    }
});

$(document).ready(function() {
    $('.popup-youtube').magnificPopup({
        disableOn: 700,
        type: 'iframe',
        mainClass: 'mfp-fade',
        removalDelay: 160,
        preloader: false,

        fixedContentPos: false
    });
});

function removegalleryimage(id) {
    $(".modal-action-name").html('DELETE');
    $("#gallery-confirm-action").modal("show");
    $('#confirm-gallery-delete').click(function () {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: removegalleryimages,
            data: {
                id: id
            },
            success: function (response) {
                $("#gallery-confirm-action").modal("hide");
                $('div#galleryimage' + id).remove();
                $(".ajax_message").removeClass("d-none");
                $(".ajax_message").find('span').addClass('successmessage').addClass('text-success').html(response.success);
                $('#main').animate({
                    scrollTop: $(".ajax_message").offset().top - $("header").outerHeight()
                }, 1000);
                setTimeout(function () {
                    $(".ajax_message").find('span').html('');
                }, message_display_time);
            }
        });
    });
}
//URL parameter
var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
};
//URL parameter

// Start: Tooltip
$(function () {

    $('body').on('focus', ".datepicker", function () {
        var container = $(this).parent();
        $(this).datetimepicker({
            format: String(date_standard),
            container: container,
            todayHighlight: true,
            autoclose: true,
            minView: 2,
            maxView: 4
        }).on('hide', function (e) {
            $(this).removeClass("disabled-input");
            var text_input_name = $(this).attr('id');
            if ($('label[for=' + text_input_name + ']').find('span.text-danger').length > 0) {
                if ($(this).val() == "") {
                    $(this).attr('data-validator', 'required');
                } else {
                    new Validator($(this));
                }
            }
        });
    });

    $('body').on('focus', ".datetimepicker", function () {
        var container = $(this).parent();
        $(this).datetimepicker({
            format: String(datetime_standard),
            container: container,
            todayHighlight: true,
            autoclose: true,
            startView: 2,
            minView: 0,
            maxView: 4,
            showMeridian: true,
            sideBySide: true
        }).on('hide', function (e) {
            $(this).removeClass("disabled-input");
            var text_input_name = $(this).attr('id');
            if ($('label[for=' + text_input_name + ']').find('span.text-danger').length > 0) {
                if ($(this).val() == "") {
                    $(this).attr('data-validator', 'required');
                } else {
                    new Validator($(this));
                }
            }
        });
    });

    $('body').on('focus', ".timepicker", function () {
        var container = $(this).parent();
        var date_value = new Date();
        if ($("#start_date").length && $('#start_date').val() != '') {
            var date_value = $('#start_date').val();
            date_value = new Date(date_value);
        }
        var time_value = '';
        if ($("#event_time_hidden").length) {
            time_value = $("#event_time_hidden").val();
        }
        var set_date = date_value.getFullYear() + '-' + (date_value.getMonth() + 1) + '-' + date_value.getDate() + ' ' + time_value;
        var current_time = $(this).val();
        $(this).val('');
        //var set_date = '';
        $(this).datetimepicker({
            format: String(time_standard),
            container: container,
            autoclose: true,
            //todayBtn:'linked',
            initialDate: set_date,
            startView: 1,
            minView: 0,
            maxView: 1,
            showMeridian: true
        }).on('hide', function (e) {
            $(this).removeClass("disabled-input");
            var text_input_name = $(this).attr('id');
            if ($('label[for=' + text_input_name + ']').find('span.text-danger').length > 0) {
                if ($(this).val() == "") {
                    $(this).attr('data-validator', 'required');
                } else {
                    new Validator($(this));
                }
            }
        });
        $(this).val(current_time);
    });
});

function updateModifiedDateData(response) {
    if ($('.update-data').length) {
        $.each(response.data, function (index, value) {
            if ($("#update_id_" + index).length && value['text_data'] != '' && value['sort_data'] != '') {
                $("#update_id_" + index).text(value['text_data']);
                $("#update_id_" + index).attr('data-sort', value['sort_data']);
            }
        });
    }
}
// End: Tooltip

// start magnific jquery
function init_magnificPopup() {
    $('.image-link').magnificPopup({
        type: 'image'
    });
}
$(document).ready(function () {
    init_magnificPopup();
});

// end maggnific jquery
