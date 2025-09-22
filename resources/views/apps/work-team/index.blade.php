@extends('layouts.app-v2')

@section('title')
    Tim Kerja | E-Monev BBRM Mektan
@endsection

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

                        <h4 class="card-title mr-4 pr-4">Data Tim Kerja</h4>
                        <div class="d-flex justify-content-start mb-3">
                            <a href="{{route('work-team.create')}}" class="btn btn-primary py-2 px-4">Tambah
                                Tim Kerja</a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th>Nama Ketua Tim Kerja</th>
                                        <th>Nama Kelompok</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($workTeams as $work_team)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $work_team->name }}</td>
                                            <td>{{ $work_team->team_leader }}</td>
                                            <td>{{ $work_team->work_group->name }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    <a class="btn btn-warning mx-1"
                                                        href="{{ route('work-team.edit', $work_team->id) }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                                        data-bs-title="Edit {{ $work_team->nama }}">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>

                                                    <!-- Button trigger modal -->
                                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal" data-id="{{ $work_team->id }}"
                                                        data-name="{{ $work_team->name }}">
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
                                                                    Are you sure you want to delete Tim Kerja <span
                                                                        id="itemName"></span>?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                    <form id="deleteForm"
                                                                        action="{{ route('work-team.delete', $work_team->id) }}"
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
            modal.find('#deleteForm').attr('action', '/v2/app/work-team/' + itemId);
        });
    </script>
@endpush
