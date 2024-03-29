@extends('layouts.app-v2')

@section('content')
    <main id="main" class="main">

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Card Body -->

                        @if (session()->has('success'))
                            <div class="alert alert-primary" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        <h4 class="card-title mr-4 pr-4">Data Subkelompok</h4>
                        <div class="d-flex justify-content-start mb-3">
                            <a href="{{route('subkelompok-create')}}" class="btn btn-primary py-2 px-4">Tambah
                                Subkelompok</a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th>Nama Ketua Tim</th>
                                        <th>Nama Kelompok</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataSubkelompoks as $subkelompok)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $subkelompok->nama }}</td>
                                            <td>{{ $subkelompok->nama_katim }}</td>
                                            <td>{{ $subkelompok->kelompok->nama }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    <a class="btn btn-warning mx-1"
                                                        href="{{ route('subkelompok-edit', $subkelompok->id) }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                                        data-bs-title="Edit {{ $subkelompok->nama }}">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>

                                                    <!-- Button trigger modal -->
                                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal" data-id="{{ $subkelompok->id }}"
                                                        data-name="{{ $subkelompok->nama }}">
                                                        <i class="bi bi-trash text-body-secondary"></i>
                                                    </button>

                                                    <!-- Modal -->
                                                    <div class="modal fade" id="deleteModal" tabindex="-1"
                                                        aria-labelledby="deleteModalLabel" data-bs-backdrop="static"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="deleteModalLabel">Delete
                                                                        Item</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Are you sure you want to delete subkelompok <span
                                                                        id="itemName"></span>?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                    <form id="deleteForm"
                                                                        action="{{ route('subkelompok-delete', $subkelompok->id) }}"
                                                                        method="POST">
                                                                        @method('DELETE')
                                                                        @csrf
                                                                        <button type="submit"
                                                                            class="btn btn-danger">Delete</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        // Update modal content when the button is clicked
        $('#deleteModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var itemId = button.data('id');
            var itemName = button.data('name');
            var modal = $(this);

            console.log(itemId);
            console.log(itemName);

            modal.find('#itemName').text(itemName);
            modal.find('#deleteForm').attr('action', '/v2/app/subkelompok/' + itemId);
        });
    </script>
@endpush
