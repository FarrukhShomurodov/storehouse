@extends('admin.layouts.app')

@section('title')
    <title>Pc maker | Просмотр продукта</title>
@endsection

@section('content')
    <h6 class="py-3 breadcrumb-wrapper mb-4">
        <span class="text-muted fw-light"><a class="text-muted"
                                             href="{{ route('products.index') }}">Продукты</a> /</span>Просмотр продукта
    </h6>

    <div class="card shadow-sm">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-header">Информация о продукте</h5>
            <div class="d-inline-block text-nowrap" style="margin-right: 22px;">
                <button class="btn btn-primary btn-sm" onclick="location.href='{{ route('products.edit', $product->id) }}'">
                    <i class="bx bx-edit me-1"></i> Редактировать
                </button>
                <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="bx bx-trash me-1"></i> Удалить
                    </button>
                </form>
                <button class="btn btn-secondary btn-sm" onclick="location.href='{{ route('products.index') }}'">
                    <i class="bx bx-arrow-back me-1"></i> Назад к списку
                </button>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-borderless">
                <tr>
                    <th>ID:</th>
                    <td>{{ $product->id }}</td>
                </tr>
                <tr>
                    <th>Название:</th>
                    <td>{{ $product->name }}</td>
                </tr>
                <tr>
                    <th>Артикул:</th>
                    <td>{{ $product->sku }}</td>
                </tr>
                <tr>
                    <th>Количество:</th>
                    <td>{{ $product->quantity }}</td>
                </tr>
                <tr>
                    <th>Цена:</th>
                    <td>{{ $product->price }} руб.</td>
                </tr>
                <tr>
                    <th>QR-код:</th>
                    <td>
                        @if($product->qr_code)
                            <div class="text-center mt-4" style="width:150px">
                                <img src="{{ asset($product->qr_code) }}" alt="QR Code" width="150" class="rounded shadow-sm mb-3">
                                <a href="{{ route('products.downloadQrCode', $product->id) }}"
                                   class="btn btn-info text-white d-flex align-items-center justify-content-center"
                                   style="width: 140px; font-size: 0.9rem;">
                                    <i class="bx bx-download me-1"></i> Скачать
                                </a>
                            </div>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>
@endsection
