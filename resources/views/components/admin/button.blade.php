<div class="d-flex">
    <a class="btn btn-info" href="{{ route('kegiatan-show', $model->id) }}" data-bs-toggle="tooltip" data-bs-placement="top"
        data-bs-custom-class="custom-tooltip" data-bs-title="Lihat Detail">
        <i class="bi bi-eye"></i>
    </a>
    <a class="btn btn-warning mx-1" href="{{ route('kegiatan-edit', $model->id) }}" data-bs-toggle="tooltip"
        data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="Edit Pengaduan">
        <i class="bi bi-pencil"></i>
    </a>
    <form action="{{ route('kegiatan-delete', $model->id) }}" method="POST">
        @method('delete')
        @csrf
        <button class="btn btn-danger"
            onclick="return confirm('Apakah anda ingin menghapus pengaduan ' + {{ $model->id }} + '?')"
            data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip"
            data-bs-title="Hapus Pengaduan" >
            <i class="bi bi-trash text-body-secondary"></i>
        </button>
    </form>
</div>
