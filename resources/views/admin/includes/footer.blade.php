@guest
@else
<footer class="text-center footer-box bg-dark fixed-bottom py-2">
    Demo 2020
</footer>
@endguest
<script type="text/javascript">
    var csrf_token = "{{ csrf_token() }}";
    var upload_url = "{{ route('upload') }}";
    var date_standard = "{{ config('app.date_format_js') }}";
    var datetime_standard = "{{ config('app.datetime_format_js') }}";
    var time_standard = "{{ config('app.time_format_js') }}";
    var pagingLenthOptions = [[100,150,200,250,300,350,400,450,500, -1], [100,150,200,250,300,350,400,450,500, "All"]];
    var defaultPagingLenth = "100";
    var regx_minimum_password_length = /^(.{8,})/;
</script>

<!-- fileupload js -->
<link rel="stylesheet" href="{{ asset('assets/admin/css/jquery.fileupload.css') }}">
<!-- The template to display files available for upload -->
<script src="{{ asset('assets/admin/js/fileupload/vendor/jquery.ui.widget.js') }}"></script>
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="{{ asset('assets/admin/js/fileupload/tmpl.min.js') }}"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="{{ asset('assets/admin/js/fileupload/load-image.all.min.js') }}"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="{{ asset('assets/admin/js/fileupload/canvas-to-blob.min.js') }}"></script>
<!-- blueimp Gallery script -->
<script src="{{ asset('assets/admin/js/fileupload/jquery.blueimp-gallery.min.js') }}"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="{{ asset('assets/admin/js/fileupload/jquery.iframe-transport.js') }}"></script>
<script src="{{ asset('assets/admin/js/fileupload/jquery.fileupload.js') }}"></script>
<!-- The File Upload processing plugin -->
<script src="{{ asset('assets/admin/js/fileupload/jquery.fileupload-process.js') }}"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="{{ asset('assets/admin/js/fileupload/jquery.fileupload-image.js') }}"></script>
<!-- The File Upload audio preview plugin -->
<script src="{{ asset('assets/admin/js/fileupload/jquery.fileupload-audio.js') }}"></script>
<!-- The File Upload video preview plugin -->
<script src="{{ asset('assets/admin/js/fileupload/jquery.fileupload-video.js') }}"></script>
<!-- The File Upload validation plugin -->
<script src="{{ asset('assets/admin/js/fileupload/jquery.fileupload-validate.js') }}"></script>
<!-- The File Upload user interface plugin -->
<script src="{{ asset('assets/admin/js/fileupload/jquery.fileupload-ui.js') }}"></script>

<!-- Dropify CSS -->
<link rel="stylesheet" href="{{ asset('assets/admin/libraries/dropify/dist/css/dropify.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/common/libraries/jquery-ui/jquery-ui.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/common/libraries/datatables/datatables.min.css') }}" />

<!-- Footer Scripts -->
<script src="{{ asset('assets/common/libraries/jquery-ui/jquery-ui.js') }}"></script>
<script src="{{ asset('assets/admin/libraries/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('assets/admin/libraries/dropify/dist/js/dropify.min.js') }}"></script>
<script src="{{ asset('assets/common/js/jquery.mask.min.js') }}"></script>
<script src="{{ asset('assets/common/libraries/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/common/libraries/datatables/datatables-bs3.js') }}"></script>
<script src="{{ asset('assets/common/libraries/datatables/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/common/libraries/datatables/jszip.min.js') }}"></script>
<script src="{{ asset('assets/common/libraries/datatables/buttons.html5.min.js') }}"></script>

<!-- Include Magnific Popup -->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/common/libraries/magnific/magnific-popup.css') }}" />
<script src="{{ asset('assets/common/libraries/magnific/jquery.magnific-popup.min.js') }}"></script>

<!-- Include Date Range Picker -->
<script src="{{ asset('assets/admin/js/moment.js') }}"></script>
<script src="{{ asset('assets/admin/js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/bootstrap-editable.js') }}"></script>
<script src="{{ asset('assets/admin/js/jquery.form-validation.js') }}"></script>
<script src="{{ asset('assets/admin/js/footer.js') }}"></script>

<!-- SVG -->
<script type="text/javascript">
    $(function(){
    jQuery('img.svg').each(function(){
        var $img = jQuery(this);
        var imgID = $img.attr('id');
        var imgClass = $img.attr('class');
        var imgURL = $img.attr('src');

        jQuery.get(imgURL, function(data) {
            // Get the SVG tag, ignore the rest
            var $svg = jQuery(data).find('svg');

            // Add replaced image's ID to the new SVG
            if(typeof imgID !== 'undefined') {
                $svg = $svg.attr('id', imgID);
            }
            // Add replaced image's classes to the new SVG
            if(typeof imgClass !== 'undefined') {
                $svg = $svg.attr('class', imgClass+' replaced-svg');
            }

            // Remove any invalid XML tags as per http://validator.w3.org
            $svg = $svg.removeAttr('xmlns:a');

            // Check if the viewport is set, else we gonna set it if we can.
            if(!$svg.attr('viewBox') && $svg.attr('height') && $svg.attr('width')) {
                $svg.attr('viewBox', '0 0 ' + $svg.attr('height') + ' ' + $svg.attr('width'))
            }

            // Replace image with new SVG
            $img.replaceWith($svg);

        }, 'xml');

    });
});
</script>
