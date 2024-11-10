<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
      data-assets-path="/" data-template="horizontal-menu-template">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
    <title>Pc maker | Продажа подтверждена</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&family=Rubik:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('vendor/fonts/boxicons.css') }}"/>
    <link rel="stylesheet" href="{{ asset('vendor/fonts/fontawesome.css') }}"/>
    <link rel="stylesheet" href="{{ asset('vendor/fonts/flag-icons.css') }}"/>

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/css/rtl/core.css') }}" class="template-customizer-core-css"/>

    <style>
        .card-body {
            padding: 2rem;
        }

        .icon {
            font-size: 5rem;
            color: #007bff;
        }

        h1 {
            font-family: 'Rubik', sans-serif;
            font-weight: 600;
            color: #007bff;
        }

        .text-muted {
            font-size: 0.95rem;
        }
    </style>

    <script>
        function closeTab() {
            window.location.href = '/'
        }

        document.addEventListener('DOMContentLoaded', () => {
            let countdown = 5;
            const timerDisplay = document.getElementById('timer');
            const timerInterval = setInterval(() => {
                if (countdown <= 0) {
                    clearInterval(timerInterval);
                    closeTab();
                } else {
                    timerDisplay.textContent = countdown;
                    countdown--;
                }
            }, 1000);
        });
    </script>
</head>

<body>
<div class="layout-wrapper layout-content-navbar d-flex justify-content-center align-items-center vh-100">
    <div class="container">
        @if($success)
            <div class="card shadow-lg border-0 mx-auto" style="max-width: 500px;">
                <div class="card-body text-center">
                    <i class="icon bx bx-check-circle mb-3"></i>
                    <h1 class="mb-4">{{ $success }}</h1>
                    <p class="mb-3">
                        Товар: <strong>{{ $product->name }}</strong>
                    </p>
                    <p class="mb-3">
                        Оставшееся количество: <strong>{{ $product->quantity }}</strong>
                    </p>
                    <p class="text-muted mb-4">
                        Вкладка закроется через <span id="timer">5</span> секунд.
                    </p>
                    <button onclick="closeTab()" class="btn btn-danger">Закрыть вкладку сейчас</button>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="d-flex flex-column align-items-center justify-content-center">
                <div class="alert alert-solid-danger alert-dismissible d-flex align-items-center" role="alert">
                    <i class="bx bx-error-circle fs-4 me-2"></i>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                    </ul>
                </div>
                <button onclick="closeTab()" class="btn btn-danger">Закрыть вкладку сейчас</button>
            </div>
        @endif
    </div>
</div>
</body>
</html>
