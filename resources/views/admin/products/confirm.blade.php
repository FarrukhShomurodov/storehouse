<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
      data-assets-path="/" data-template="horizontal-menu-template">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>

    <title>Pc maker | Подтверждение продажи</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('vendor/fonts/boxicons.css') }}"/>
    <link rel="stylesheet" href="{{ asset('vendor/fonts/fontawesome.css') }}"/>
    <link rel="stylesheet" href="{{ asset('vendor/fonts/flag-icons.css') }}"/>

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/css/rtl/core.css') }}" class="template-customizer-core-css"/>
</head>

<body>
<div class="layout-wrapper layout-content-navbar d-flex justify-content-center align-items-center vh-100">
    <div class="container">
        <div class="card shadow-lg border-0 mx-auto" style="max-width: 600px;">
            <div class="card-header bg-primary text-white text-center">
                <h4 class="mb-0 text-white">Подтверждение продажи</h4>
            </div>
            <div class="card-body p-4">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="table-responsive">
                            <table class="table table-borderless table-hover">
                                <tbody>
                                <tr>
                                    <th class="text-muted">Товар:</th>
                                    <td class="text-dark">{{ $product->name }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Оставшееся количество:</th>
                                    <td class="text-dark">{{ $product->quantity }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Цена:</th>
                                    <td class="text-dark">{{ $product->price }} сум</td>
                                </tr>
                                <tr>
                                    @if($unit->sold)
                                        <th class="text-muted">Дата продажи:</th>
                                        <td class="text-dark">{{ $unit->sold_at }} сум</td>
                                    @endif
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        @if($unit->qr_code)
                            <div class="text-center mt-4">
                                <img src="{{ Storage::url($unit->qr_code) }}" alt="QR Code"
                                     class="img-fluid rounded" style="width: 150px;">
                            </div>
                        @endif
                    </div>
                </div>


                @if($unit->sold)
                    <form action="{{ route('cancel', $unit->serial_number) }}" method="get" class="mt-4">
                        <div class="text-center">
                            <button type="submit" class="btn btn-danger btn-lg px-5">
                                <i class="bx bx-x-circle me-1"></i> Отменить продажу
                            </button>
                        </div>
                    </form>
                @else
                    @if($product->quantity > 0)
                        <form action="{{ route('sell', $unit->serial_number) }}" method="get">
                            <div class="text-center mt-5">
                                <button type="submit" class="btn btn-success btn-lg px-5">
                                    <i class="bx bx-check-circle me-1"></i> Подтвердить продажу
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="text-center mt-5">
                            <p class="text-danger fw-bold">Этот товар уже был продан или не существует.</p>
                        </div>
                    @endif
                @endif
            </div>
            <div class="card-footer bg-light text-end">
                <button class="btn btn-outline-secondary"
                        onclick="location.href='{{ route('products.index') }}'">
                    <i class="bx bx-arrow-back me-1"></i> Назад к списку
                </button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
