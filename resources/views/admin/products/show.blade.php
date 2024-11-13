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
                <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm me-2">
                    <i class="bx bx-arrow-back me-1"></i> Назад к списку
                </a>
                <a href="{{ route('products.printQRCodes', $product->id) }}" target="_blank" class="btn btn-primary btn-sm me-2">
                    <i class="bx bx-printer me-1"></i> Распечатать все QR-коды
                </a>
                <a href="{{ route('products.downloadQRCodes', $product->id) }}" class="btn btn-success btn-sm">
                    <i class="bx bx-download me-1"></i> Скачать все QR-коды
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2">
                            <strong>ID:</strong> <span>{{ $product->id }}</span>
                        </li>
                        <li class="mb-2">
                            <strong>Название:</strong> <span>{{ $product->name }}</span>
                        </li>
                        <li class="mb-2">
                            <strong>Кол-во на складе:</strong>
                            <span class="badge bg-secondary">{{ $product->quantity }}</span>
                        </li>
                        <li class="mb-2">
                            <strong>Цена:</strong>
                            <span>{{ round($product->price) }} UZS</span>
                        </li>
                    </ul>
                </div>

                <div class="col-md-6">
                    @foreach($product->units as $unit)
                        <div class="d-flex flex-column align-items-start mb-3 p-2 border-bottom">
                            <div class="d-flex align-items-center mb-2">
                                <img src="{{ Storage::url($unit->qr_code) }}" alt="QR Code"
                                     class="rounded" style="width: 80px; height: 80px; object-fit: cover; margin-right: 10px;">
                                <div>
                                    <a href="{{ Storage::url($unit->qr_code) }}" download="qr_code_{{ $unit->serial_number }}.svg"
                                       class="text-primary me-2">
                                        <i class="bx bx-download"></i> Скачать
                                    </a>

                                    @if($unit->sale)
                                        <span class="badge bg-danger">Продано</span>
                                    @else
                                        <span class="badge bg-success">В наличии</span>
                                    @endif
                                </div>
                            </div>

                            @if($unit->cancelLog)
                                <div class="mb-2 w-100">
                                    <h6 class="text-muted mb-1">История отмен:</h6>
                                    <div class="overflow-auto" style="max-height: 100px;">
                                        @foreach($unit->cancelLog as $log)
                                            <div class="d-flex justify-content-between mb-1 small text-muted">
                                                <span>{{ $log->reason }}</span>
                                                <span>{{ $log->created_at->format('d.m.Y H:i') }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <form action="{{ route('unit.product.delete', $unit->id) }}" method="POST" class="mt-1">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bx bx-trash"></i> Удалить
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
