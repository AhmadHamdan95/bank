<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title')</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{asset('admin/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Theme style -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
{{-- 
  <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet"> --}}

  <link rel="stylesheet" href="{{asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">


  <link rel="stylesheet" href="{{asset('admin/dist/css/adminlte.min.css')}}">
  <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">

  @yield('styles')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  @include('admin.layout.navbar')
  
  @include('admin.layout.sidebar')

  @yield('content')

  @include('admin.layout.footer')
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="{{asset('admin/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

<script src="{{asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
{{-- {{asset('admin/plugins/datatbles-bs4/css/dataTables.bootstrap4.css')}} --}}
<script src="{{asset('admin/dist/js/adminlte.min.js')}}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="{{asset('admin/dataTableAjax.js')}}"></script>
<script>
  $(document).on('submit', '#ajax_form', function (e) {
    e.preventDefault();
    let form = jQuery(this),
        url = form.attr('action'),
        method = form.attr('method'),
        data = new FormData(form[0]);
    $.ajax({
        url: url,
        type: method,
        data: data,
        contentType: false,
        processData: false,
    }).done(function (data) {
        if (data.status = 200) {
            if (data != null) {
                toastr.success(data.message)
                if (data.url != null) {
                    jQuery('.submit_btn').attr('disabled', true);
                    setTimeout(function () {
                        window.location = data.url;
                    }, 1500);
                }
            }
        } else {
            toastr.error(data.message)
        }
    }).fail(function (data) {
        if (data.status === 422) {
            jQuery('.is-invalid').removeClass('is-invalid');
            jQuery('.invalid-feedback').remove();
            var object = data.responseJSON.errors;
            for (const key in object) {
                if (object.hasOwnProperty(key)) {
                    const element = object[key][0];
                    let input = '';
                    let selector = '';
                    if (key.indexOf('.') > -1) {
                        let keys = key.split('.');
                        let newKeysList = [];
                        for (let index in keys) {
                            if (index == 0) {
                                newKeysList[index] = keys[index];
                                continue;
                            }
                            newKeysList[index] = '[' + keys[index] + ']';
                        }
                        let newName = newKeysList.join('');
                        selector = '[name="' + newName + '"]';
                    } else selector = '[name="' + key + '"]';
                    input = jQuery(selector);
                    input.addClass('is-invalid');
                    let nextSiblings = input.nextAll();
                    if (nextSiblings.length > 0) {
                        jQuery(`<span class="invalid-feedback">${element}</span>`).insertAfter(nextSiblings.last());
                    } else {
                        jQuery(`<span class="invalid-feedback">${element}</span>`).insertAfter(selector);
                    }
                }
            }
            for (let index in data.responseJSON.errors) {
                description = data.responseJSON.errors[index][0];
                break;
            }
            toastr.error(description)
        } else if (data.status === 400) {
            var object = data.responseJSON
            toastr.error(object.message)
        } else if (data.status === 401) {
            toastr.error('الرجاء تسجيل الدخول", "خطأ')
        } else if (data.status === 500) {
            Swal.fire("Server Error", data.responseJSON.message);
        } else {
            Swal.fire("Error", data.responseJSON.message);
        }
    });
});
</script>



@yield('scripts')
</body>
</html>
