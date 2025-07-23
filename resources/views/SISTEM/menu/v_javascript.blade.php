<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<script>
    function LoadData() {
        $.ajax({
            type: 'GET',
            url: "{{ route('menu.load') }}",
            data: {
                "_token": "{{ csrf_token() }}"
            },
            success: function (result) {
                $('#LoadData').html(result);
                alertify.set('notifier', 'position', 'bottom-right');
                alertify.success("Berhasil Memperbarui Data ");
            },
            error: function (xhr, status, error) {
                console.error('Terjadi kesalahan:', error);
            }
        });
    }
    function LoadDataSub() {
        $.ajax({
            type: 'GET',
            url: "{{ route('submenu.load') }}",
            data: {
                "_token": "{{ csrf_token() }}"
            },
            success: function (result) {
                $('#LoadDataSub').html(result);
                alertify.set('notifier', 'position', 'bottom-right');
                alertify.success("Berhasil Memperbarui Data ");
            },
            error: function (xhr, status, error) {
                console.error('Terjadi kesalahan:', error);
            }
        });
    }
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        LoadData();
        LoadDataSub();
    });
</script>
</body>
</html>