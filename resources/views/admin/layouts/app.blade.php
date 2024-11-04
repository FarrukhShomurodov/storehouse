<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed " dir="ltr" data-theme="theme-default"
      data-assets-path="/" data-template="horizontal-menu-template">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>

    @yield('title')

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('vendor/fonts/boxicons.css') }}"/>
    <link rel="stylesheet" href="{{ asset('vendor/fonts/fontawesome.css') }}"/>
    <link rel="stylesheet" href="{{ asset('vendor/fonts/flag-icons.css') }}"/>

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/css/rtl/core.css') }}" class="template-customizer-core-css"/>
    <link rel="stylesheet" href="{{ asset('vendor/css/rtl/theme-default.css') }}"
          class="template-customizer-theme-css"/>
    <link rel="stylesheet" href="{{ asset('css/demo.css') }}"/>

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}"/>
    <link rel="stylesheet" href="{{ asset('vendor/libs/typeahead-js/typeahead.css') }}"/>
    <link rel="stylesheet" href="{{ asset('vendor/libs/bootstrap-select/bootstrap-select.css') }}"/>
    <link rel="stylesheet" href="{{asset('vendor/libs/select2/select2.css')}}"/>

    <!-- Helpers -->
    <script src="{{ asset('vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('js/config.js') }}"></script>
    <style>
        body {
            overflow-x: hidden;
        }
    </style>
</head>

<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar  ">
    <div class="layout-container">

        @include('admin.layouts.menu')

        <div class="layout-page">
            @include('admin.layouts.header')
            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Core JS -->
<script src="{{ asset('vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
<script src="{{ asset('vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('vendor/libs/hammer/hammer.js') }}"></script>
<script src="{{ asset('vendor/js/menu.js') }}"></script>

<!-- Vendors JS -->
<script src="{{asset('vendor/libs/select2/select2.js')}}"></script>
<script src="{{asset('vendor/libs/formvalidation/dist/js/FormValidation.min.js')}}"></script>
<script src="{{asset('vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js')}}"></script>
<script src="{{asset('vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js')}}"></script>
<script src="{{asset('vendor/libs/moment/moment.js')}}"></script>
<script src="{{asset('js/forms-selects.js')}}"></script>

<!-- Main JS -->
<script src="{{ asset('js/main.js') }}"></script>

@yield('scripts')

<script>
    $(document).ready(function () {
        // alert
        setTimeout(function () {
            $('.alert').fadeOut('slow', function () {
                $(this).remove();
            });
        }, 1500);

        // Image
        $('#imageInput').on('change', function () {
            const files = Array.from($(this)[0].files);
            const imagePreview = $('#imagePreview');

            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const imgElement = $('<img>', {
                        src: e.target.result,
                        alt: file.name,
                        class: 'uploaded-image'
                    });

                    const imgContainer = $('<div>', {class: 'image-container td__img'});
                    imgContainer.append(imgElement);

                    const deleteBtn = $('<button>', {
                        class: 'btn btn-danger btn-sm delete-image',
                        text: 'Удалить',
                        click: function () {
                            imgContainer.remove();
                            const index = files.indexOf(file);
                            if (index !== -1) {
                                files.splice(index, 1);
                                updateFileInput(files);
                            }
                        }
                    });
                    imgContainer.append(deleteBtn);

                    imagePreview.append(imgContainer);
                };
                reader.readAsDataURL(file);
            });
        });

        function updateFileInput(files) {
            const input = $('#imageInput')[0];
            const fileList = new DataTransfer();
            files.forEach(file => {
                fileList.items.add(file);
            });
            input.files = fileList.files;
        }

        $(document).on('click', '.delete-image', function () {
            const path = $(this).data('photo-path');
            if (path) {
                $.ajax({
                    url: `/api/delete/image/${path}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (res) {
                        console.log(res);
                        $(this).closest('.image-container').remove();
                    }.bind(this),
                    error: function (error) {
                        console.error('Error deleting photo:', error);
                    }
                });
            }
        });

        // select2
        $('.select2').select2();
    })
</script>
</body>
</html>
