<div class="d-flex">
    <a class="btn btn-info" href="{{ route('activity.show', $model->id) }}" data-bs-toggle="tooltip" data-bs-placement="top"
        data-bs-custom-class="custom-tooltip" data-bs-title="Lihat Detail">
        <iconify-icon icon="bi:eye" width="16" height="22" style="display: flex;"></iconify-icon>
    </a>
    @can('update', $model)
    <a class="btn btn-warning mx-1" href="{{ route('activity.edit', $model->id) }}" data-bs-toggle="tooltip"
        data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="Edit Pengaduan">
        <iconify-icon icon="bi:pencil" width="16" height="22" style="display: flex;"></iconify-icon>
    </a>
    @endcan
    @can('delete', $model)
    <form action="{{ route('activity.delete', $model->id) }}" method="POST">
        @method('delete')
        @csrf
        <button class="btn btn-danger"
            onclick="return confirm('Apakah anda ingin menghapus pengaduan?')"
            data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip"
            data-bs-title="Hapus Pengaduan">
            <iconify-icon icon="bi:trash" width="16" height="22" style="display: flex; color: #212529;"></iconify-icon>
        </button>
    </form>
    @endcan
</div>