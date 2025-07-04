@extends('back/layouts/template')
@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.bootstrap5.css">
@endpush
{{-- Artikel di ganti dengan postingan --}}
@section('title', 'List Postingan - Admin')

@section('content')


    {{-- konten --}}
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Postingan</h1>
        </div>

        <div class="mt-3">
            <a href="{{ url('postingan/create') }}" class="btn btn-secondary mb-2">Create</a>

            @if ($errors->any())
                <div class="my-3">
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
            {{-- success alert --}}
            <div class="swal" data-swal="{{ session('success') }}"></div>

            <table class="table table-striped table-bordered" id="dataTable">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>Nama</th>
                        <th>Mata Pelajaran</th>
                        <th>Kategori</th>
                        <th>Views</th>
                        <th>Maks. Anggota</th>
                        <th>Status</th>
                        <th>Tanggal Post</th>
                        <th>Function</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>


    @endsection

    @push('js')
        <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
        <script src="https://cdn.datatables.net/2.3.1/js/dataTables.js"></script>
        <script src="https://cdn.datatables.net/2.3.1/js/dataTables.bootstrap5.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        {{-- alert success --}}
        <script>
            const swal = $('.swal').data('swal');
            if (swal) {
                Swal.fire({
                    'title': 'Success',
                    'text': swal,
                    'icon': 'success',
                    'showConfirmButton': false,
                    'timer': 2000
                })
            }

            function deletePostingan(e) {
                let id = e.getAttribute('data-id');

                Swal.fire({
                    title: 'Delete',
                    text: "Apakah Anda Yakin Untuk Menghapus Artikel?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Delete!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'DELETE',
                            url: '/postingan/' + id,
                            dataType: 'json',
                            success: function(response) {
                                Swal.fire({
                                    title: 'Success',
                                    text: response.message,
                                    icon: 'success'
                                }).then(() => {
                                    window.location.href = '/postingan';
                                });
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                            }
                        });
                    }
                });
            }
        </script>

        <script>
            $(document).ready(function() {
                $('#dataTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ url()->current() }}',
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'title',
                            name: 'title'
                        },
                        {
                            data: 'category_id',
                            name: 'category_id'
                        },
                        {
                            data: 'views',
                            name: 'views'
                        },
                        {
                            data: 'max_participants',
                            name: 'max_participants'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'created_at',
                            name: 'publish_date'
                        },
                        {
                            data: 'button',
                            name: 'button',
                            orderable: false,
                            searchable: false
                        },
                    ],
                    // Tambahkan drawCallback ini!
                    "drawCallback": function(settings) {
                        feather.replace(); // Panggil feather.replace() setelah tabel digambar ulang
                    }
                });
            });
        </script>
    @endpush
