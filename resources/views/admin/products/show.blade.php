@extends('admin.layouts.app')

@section('title')
    <title>Pc maker | Просмотр продукта</title>
@endsection

@section('content')
    <h6 class="py-3 breadcrumb-wrapper mb-4">
        <span class="text-muted fw-light"><a class="text-muted"
                                             href="{{ route('products.index') }}">Продукты</a> /</span> Просмотр
        продукта
    </h6>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Информация о продукте</h5>
            <div class="d-flex">
                <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bx bx-arrow-back me-1"></i> Назад к списку
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <h6 class="fw-bold">Основная информация</h6>
                    <ul class="list-unstyled">
                        <li><strong>ID:</strong> {{ $product->id }}</li>
                        <li><strong>Название:</strong> {{ $product->name }}</li>
                        <li><strong>Количество:</strong> {{ $product->quantity }}</li>
                        <li><strong>Цена:</strong> {{ round($product->price) }} uzs</li>
                    </ul>
                </div>

                <div class="col-md-6">
                    @foreach($product->units as $unit)
                        <div class="d-flex flex-column justify-content-center align-items-center mb-3">
                            <img src="{{ Storage::url($unit->qr_code) }}" alt="QR Code"
                                 class="rounded shadow-sm mb-3" style="width: 200px">
                            <div class="d-flex column justify-content-between align-items-center">
                                <a class="btn btn-info text-white btn-sm d-flex align-items-center me-3"
                                   href="{{ Storage::url($unit->qr_code) }}"
                                   download="qr_code_{{ $unit->serial_number }}.svg"
                                   style="width: 140px; font-size: 0.9rem;">
                                    <i class="bx bx-download me-1"></i> Скачать
                                </a>
                                <form action="{{ route('unit.product.delete', $unit->id) }}" method="POST"
                                      style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger delete-record"><i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
