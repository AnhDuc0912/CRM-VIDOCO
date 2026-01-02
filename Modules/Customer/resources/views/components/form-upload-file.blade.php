@use('App\Helpers\FileHelper')

<div class="card shadow-none border mb-0 radius-15">
    <div class="card-body">
        <h5 class="mb-3">Tài liệu khách hàng</h5>
        <div class="row g-3 mb-4">
            @if (!request()->routeIs('customers.show'))
                <div class="col-12">
                    <input id="fancy-file-upload4" type="file" name="files[]" accept=".jpg, .png, image/jpeg, image/png"
                        multiple>
                </div>
            @endif
            @if (request()->routeIs('customers.edit') || request()->routeIs('customers.show'))
                <div class="file-preview" id="filePreview">
                    @foreach ($customer->files as $file)
                        @if ($file->extension == 'jpeg' || $file->extension == 'png' || $file->extension == 'jpg')
                            <div class="file-item">
                                <div class="file-image">
                                    <img src="{{ FileHelper::getFileUrl($file->path) }}" alt="Preview">
                                </div>
                                @if (request()->routeIs('customers.edit'))
                                    <a class="remove-btn" href="javascript:void(0)"
                                        onclick="confirmDelete('{{ route('customers.remove-file', ['id' => $customer->id, 'fileId' => $file->id]) }}', 'Bạn có chắc chắn muốn xóa file này không?')">&times;</a>
                                @endif
                            </div>
                        @else
                            <div class="file-item">
                                @if (request()->routeIs('customers.edit'))
                                    <div class="file-image d-flex align-items-center justify-content-center">
                                        <div class="file-icon text-primary">
                                            {{ $file->extension ?? '' }}
                                        </div>
                                    </div>
                                    <a class="remove-btn" href="javascript:void(0)"
                                        onclick="confirmDelete('{{ route('customers.remove-file', ['id' => $customer->id, 'fileId' => $file->id]) }}', 'Bạn có chắc chắn muốn xóa file này không?')">&times;</a>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>
                @if (request()->routeIs('customers.show'))
                    <div class="d-flex align-items-center mt-2">
                        <a href="{{ route('customers.download-files', $customer->id) }}" class="btn btn-info"
                            download>
                            <i class="bx bx-download"></i>
                            <span>Tải xuống</span>
                        </a>
                    </div>
                @endif
            @endif
        </div>
        <hr>
        <div class="row g-3 mb-4 text-center">
            <div class="col-12">
                <button class="btn btn-primary" type="submit">Lưu Dữ
                    Liệu</button>
            </div>
        </div>
    </div>
</div>
