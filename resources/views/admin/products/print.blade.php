<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Печать QR-кодов</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
        .qr-container {
            page-break-inside: avoid;
            display: flex;
            align-items: center;
            justify-content: start;
            margin-bottom: 20px;
        }
        .qr-container img {
            width: 150px;
            height: 150px;
            margin-right: 20px;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="no-print mb-4">
        <button onclick="window.print()" class="btn btn-success">
            <i class="bx bx-printer me-1"></i> Распечатать
        </button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Назад</a>
    </div>
    <h4 class="mb-4">QR-коды для продукта: {{ $product->name }}</h4>
    <div class="row">
        @foreach($product->units as $unit)
            <div class="col-md-4 qr-container">
                <img src="{{ Storage::url($unit->qr_code) }}" alt="QR Code">
                <div>
                    <strong>Серийный номер:</strong> {{ $unit->serial_number }} <br>
                    @if($unit->sale)
                        <span class="badge bg-danger">Продано</span>
                    @else
                        <span class="badge bg-success">В наличии</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
    window.onload = () => {
        // Автоматическая печать при загрузке страницы
        window.print();
    };
</script>
</body>
</html>
