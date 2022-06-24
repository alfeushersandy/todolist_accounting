@extends('layouts.master')

@section('title')
    DAFTAR TUGAS
@endsection

@section('breadcrumb')
    @parent
    <li class="active">DAFTAR TUGAS</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            @if(Auth()->user()->level == 1)
            <div class="box-header with-border">
                <button onclick="addForm('{{ route('todolist.store') }}')" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i> Tambah</button>
            </div>
            @endif
            <div class="box-body table-responsive">
                <form action="" method="post" class="form-member">
                    @csrf
                    <table class="table table-stiped table-bordered">
                        <thead>
                            <th width="5%">No</th>
                            <th>Todo</th>
                            <th>PIC</th>
                            <th>Uploaded</th>
                            <th>Finalize</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>

@includeIf('todolist.form')
@endsection

@push('scripts')
<script>
    let table;

    $(function () {
        table = $('.table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('todolist.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'nama_todolist'},
                {data: 'PIC'},
                {data: 'uploaded'},
                {data: 'finalize'},
                {data: 'aksi'},
            ]
        });


        $('#modal-form').validator().on('submit', function (e) {
            if (! e.preventDefault()) {
                $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
                    .done((response) => {
                        $('#modal-form').modal('hide');
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menyimpan data');
                        return;
                    });
            }
        });

        $('[name=select_all]').on('click', function () {
            $(':checkbox').prop('checked', this.checked);
        });

        $('#departemen').on('change', function(){
            let departemen = $(this).val();
            kategori(departemen);
            if(departemen == 1){
                $('.peralatan').show();
                $('.it').hide();
                $('.it input').removeAttr('required autofokus')
                $('.it select').removeAttr('required')
            }else if(departemen == 3){
                $('.peralatan').hide();
                $('.it').show();
                $('.peralatan input').removeAttr('required autofokus')
                $('.peralatan select').removeAttr('required')
            }
    
            });
    });

    function kategori(departemen){
        $.ajax({
                url: 'member/getcategory/'+departemen,
                type:'GET',
                data: {"_token":"{{csrf_token() }}"},
                dataType: "json",
                success: function(data)
                    {
                        $('select[name="id_kategori"]').empty();
                        $.each(data, function(key, kategori) {
                        $('select[name="id_kategori"]').append('<option value="'+ kategori.id_kategori +'">' + kategori.nama_kategori+ '</option>');
                        });
                    }
                        })
                }

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Member');
        
        $('.peralatan').hide();
        $('.it').hide();
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
    }

    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Member');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=nama]').focus();

        $.get(url)
            .done((response) => {
                $('#modal-form [name=nama]').val(response.nama);
                $('#modal-form [name=no_pol]').val(response.no_pol);
                $('#modal-form [name=user]').val(response.user);
                $('#modal-form [name=telepon]').val(response.telepon);
                $('#modal-form [name=alamat]').val(response.alamat);
            })
            .fail((errors) => {
                alert('Tidak dapat menampilkan data');
                return;
            });
    }

    function deleteData(url) {
        if (confirm('Yakin ingin menghapus data terpilih?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    table.ajax.reload();
                })
                .fail((errors) => {
                    alert('Tidak dapat menghapus data');
                    return;
                });
        }
    }

    function taskSelesai(url){
        $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'put'
                })
                .done((response) => {
                    table.ajax.reload();
                })
                .fail((errors) => {
                    return;
                });
    }

    function finalize(url){
        $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'put'
                })
                .done((response) => {
                    table.ajax.reload();
                })
                .fail((errors) => {
                    return;
                });
    }
    
</script>
@endpush