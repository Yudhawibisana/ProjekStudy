@extends('back/layouts/template')
@section('title', 'Dashboard - Admin')
@section('content')
    <style>
        .card:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            transition: 0.3s ease-in-out;
        }

        .card-header {
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .dashboard-icon {
            font-size: 2rem;
            margin-right: 10px;
        }

        .progress {
            height: 8px;
            background-color: rgba(255, 255, 255, 0.2);
        }

        .progress-bar {
            background-color: #ffc107;
        }

        table th {
            background-color: #f8f9fa;
        }
    </style>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2"><span data-feather="home"
                    style="width: 32px; height: 32px; margin-right: 10px;"></span>Dashboard</h1>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card text-white" style="background-color: #4CAF50;">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Total Postingan</span>
                        <span data-feather="file-text"></span>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $total_postingans }} POSTINGAN</h5>
                        <div class="progress mb-2">
                            <div class="progress-bar" style="width: 70%"></div>
                        </div>
                        <a href="{{ url('postingan') }}" class="btn btn-light btn-sm">Lihat Detail</a>
                    </div>
                </div>
            </div>


            <div class="col-md-6 mb-4">
                <div class="card text-white" style="background-color: #FFA726;">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Total Kategori</span>
                        <span data-feather="layers"></span>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $total_categories }} KATEGORI</h5>
                        <div class="progress mb-2">
                            <div class="progress-bar" style="width: 50%"></div>
                        </div>
                        <a href="{{ url('categories') }}" class="btn btn-light btn-sm">Lihat Detail</a>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-12">
                <h4 class="mb-3">📌 Postingan Terbaru</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th>NO</th>
                                <th>MATA PELAJARAN</th>
                                <th>KATEGORI</th>
                                <th>CREATED AT</th>
                                <th>FUNCTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($latest_postingan as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ $item->Category->name }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td class="text-center">
                                        <a href="{{ url('postingan/' . $item->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <span data-feather="eye"></span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
@endsection
