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
                <div class="text-center mb-4">
                    <h5 class="fw-bold">{{ $message }}</h5>
                </div>
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
                                    <th class="text-muted">Артикул:</th>
                                    <td class="text-dark">{{ $product->sku }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Оставшееся количество:</th>
                                    <td class="text-dark">{{ $product->quantity }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Цена:</th>
                                    <td class="text-dark">{{ $product->price }} ₽</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        @if($product->qr_code)
                            <div class="text-center mt-4">
                                <img src="{{ asset($product->qr_code) }}" alt="QR Code"
                                     class="img-fluid rounded" style="width: 150px;">
                            </div>
                        @endif
                    </div>
                </div>

                @if($product->quantity > 0)
                    <div class="text-center mt-5">
                        <button class="btn btn-success btn-lg px-5"
                                onclick="location.href='{{ route('products.sale', $product->id) }}'">
                            <i class="bx bx-check-circle me-1"></i> Подтвердить продажу
                        </button>
                    </div>
                @else
                    <div class="text-center mt-5">
                        <p class="text-danger fw-bold">Товар больше не доступен.</p>
                    </div>
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
