<!doctype html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
    <title>@yield('title') - {{ \App\Helpers\Helper::getCompanyName() }}</title>

    <!-- manifest meta -->
    <meta name="mobile-web-app-capable" content="yes">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="{{ asset(\App\Helpers\Helper::getFavicon()) }}" sizes="180x180">
    <link rel="icon" href="{{ asset(\App\Helpers\Helper::getFavicon()) }}" sizes="32x32" type="image/png">
    <link rel="icon" href="{{ asset(\App\Helpers\Helper::getFavicon()) }}" sizes="16x16" type="image/png">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body.auth-page {
            background: #17232D;
            color: #ffffff;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
        }

        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-card {
            width: 100%;
            max-width: 450px;
            background: #1F2E3A;
            border-radius: 22px;
            padding: 35px 28px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
            transition: 0.3s ease;
        }

        .auth-card:hover {
            transform: translateY(-4px);
        }

        .auth-logo {
            text-align: center;
            margin-bottom: 25px;
            font-size: 22px;
            font-weight: 700;
        }

        .auth-logo span {
            background: linear-gradient(180deg, #D8C79A, #B8A06F);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .auth-title {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .auth-subtitle {
            font-size: 13px;
            color: #aaa;
            margin-bottom: 25px;
        }

        .auth-input-group {
            margin-bottom: 16px;
        }

        .auth-input-group input {
            width: 100%;
            height: 50px;
            border-radius: 16px;
            border: none;
            padding: 0 15px;
            font-size: 14px;
            background: #101820;
            color: #fff;
            transition: 0.3s ease;
        }

        .auth-input-group input::placeholder {
            color: #777;
        }

        .auth-input-group input:focus {
            outline: none;
            box-shadow: 0 0 0 2px #2a3c4d;
        }

        .auth-link {
            font-size: 12px;
            color: #D8C79A;
            text-decoration: none;
        }

        .auth-link:hover {
            opacity: 0.8;
        }

        .auth-btn {
            width: 100%;
            height: 50px;
            border-radius: 18px;
            border: none;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            background: linear-gradient(180deg, #D8C79A, #B8A06F);
            color: #17232D;
            transition: 0.3s ease;
        }

        .auth-btn:hover {
            transform: translateY(-3px);
        }

        .auth-footer-text {
            margin-top: 22px;
            text-align: center;
            font-size: 13px;
            color: #aaa;
        }

        .auth-checkbox {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            font-size: 12px;
            color: #aaa;
            margin-bottom: 18px;
        }

        .auth-checkbox input {
            margin-top: 3px;
        }

        .auth-alert {
            background: #2a1c1f;
            border-radius: 14px;
            padding: 12px 15px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #ffb3b3;
        }

        /* Responsive adjustments */
        @media (max-width: 576px) {
            .auth-card {
                padding: 25px 20px;
                border-radius: 16px;
            }

            .auth-title {
                font-size: 20px;
            }

            .auth-subtitle {
                font-size: 12px;
            }

            .auth-input-group input {
                height: 45px;
                font-size: 13px;
            }

            .auth-btn {
                height: 45px;
                font-size: 13px;
            }

            .auth-footer-text {
                font-size: 12px;
            }
        }

        @media (max-width: 400px) {
            .auth-card {
                padding: 20px 15px;
                border-radius: 14px;
            }

            .auth-title {
                font-size: 18px;
            }

            .auth-input-group input {
                height: 40px;
                font-size: 12px;
            }

            .auth-btn {
                height: 40px;
                font-size: 12px;
            }
        }
    </style>
    <style>
        /* Modal wrapper hidden by default */
        .custom-modal {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 9999;
            font-family: 'Poppins', sans-serif;
        }

        /* Backdrop */
        .custom-modal-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        /* Modal content */
        .custom-modal-content {
            position: relative;
            max-width: 400px;
            width: 90%;
            margin: 100px auto;
            background: #1F2E3A;
            color: #fff;
            border-radius: 20px;
            padding: 25px 20px;
            z-index: 10;
            transform: translateY(-50px);
            opacity: 0;
            transition: all 0.3s ease;
        }

        /* Show modal */
        .custom-modal.show .custom-modal-backdrop {
            opacity: 1;
        }

        .custom-modal.show .custom-modal-content {
            transform: translateY(0);
            opacity: 1;
        }

        /* Header */
        .custom-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .custom-modal-header h5 {
            font-size: 18px;
            margin: 0;
        }

        .custom-modal-close {
            background: none;
            border: none;
            font-size: 22px;
            color: #fff;
            cursor: pointer;
        }

        /* Body */
        .custom-modal-body {
            font-size: 14px;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Footer button */
        .custom-modal-footer {
            text-align: center;
        }

        .custom-modal-btn {
            padding: 10px 25px;
            background: linear-gradient(180deg, #D8C79A, #B8A06F);
            border: none;
            border-radius: 15px;
            color: #17232D;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .custom-modal-btn:hover {
            transform: translateY(-2px);
        }

        @media (max-width: 400px) {
            .custom-modal-content {
                padding: 20px 15px;
                margin: 50px auto;
            }

            .custom-modal-header h5 {
                font-size: 16px;
            }

            .custom-modal-body {
                font-size: 13px;
            }

            .custom-modal-btn {
                padding: 8px 20px;
                font-size: 13px;
            }
        }
    </style>
</head>

<body class="auth-page">

    @yield('content')

    <!-- Dynamic Alert Modal -->
    <div id="alertModal" class="custom-modal">
        <div class="custom-modal-backdrop" id="modalBackdrop"></div>
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                <h5 id="alertModalTitle"></h5>
                <button type="button" class="custom-modal-close" id="modalCloseBtn">&times;</button>
            </div>
            <div class="custom-modal-body" id="alertModalBody">
                <!-- Dynamic message -->
            </div>
            <div class="custom-modal-footer">
                <button type="button" class="custom-modal-btn" id="modalOkBtn">OK</button>
            </div>
        </div>
    </div>

    <script>
        function showModal(title, message) {
            const modal = document.getElementById('alertModal');
            const titleEl = document.getElementById('alertModalTitle');
            const bodyEl = document.getElementById('alertModalBody');

            titleEl.innerText = title;
            bodyEl.innerText = message;

            modal.classList.add('show');
            modal.style.display = 'block';

            // Close handlers
            document.getElementById('modalOkBtn').onclick = closeModal;
            document.getElementById('modalCloseBtn').onclick = closeModal;
            document.getElementById('modalBackdrop').onclick = closeModal;
        }

        function closeModal() {
            const modal = document.getElementById('alertModal');
            modal.classList.remove('show');
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300); // match CSS transition
        }

        // Auto show modal if session has success or error
        window.addEventListener('load', function() {
            @if (session('success'))
                showModal('Success', "{{ session('success') }}");
            @endif

            @if (session('error'))
                showModal('Error', "{{ session('error') }}");
            @endif
        });
    </script>
</body>

</html>
