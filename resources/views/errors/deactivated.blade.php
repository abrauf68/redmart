<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Deactivated</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #101820;
            color: #fff;
            font-family: 'Segoe UI', sans-serif;
        }

        .deactivated-wrapper {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .deactivated-card {
            background: linear-gradient(180deg, #1F2E3A, #17232D);
            border-radius: 25px;
            padding: 45px 35px;
            margin: 0 20px;
            text-align: center;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 0 25px rgba(220, 53, 69, 0.35);
        }

        .icon-box {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: rgba(220, 53, 69, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 35px;
            color: #dc3545;
        }

        .deactivated-card h2 {
            color: #dc3545;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .deactivated-card p {
            color: #ccc;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .btn-gold {
            background: linear-gradient(180deg, #D8C79A, #B8A06F);
            color: #17232D;
            font-weight: bold;
            border-radius: 15px;
            padding: 10px 22px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-outline-light-custom {
            border: 1px solid #D8C79A;
            color: #D8C79A;
            border-radius: 15px;
            padding: 10px 22px;
            text-decoration: none;
            margin-left: 10px;
        }

        .btn-outline-light-custom:hover {
            background: #D8C79A;
            color: #17232D;
        }
    </style>
</head>

<body>

    <div class="deactivated-wrapper">
        <div class="deactivated-card">

            <div class="icon-box">
                ⚠
            </div>

            <h2>Your Account Has Been Deactivated</h2>

            <p>
                It appears that your account is currently inactive.
                If you believe this action was taken in error,
                please contact our support team for further assistance.
            </p>

            <div>
                <a href="javascript:void();" class="btn-gold"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Log Out
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>

                <a href="#" class="btn-outline-light-custom">
                    Contact Support
                </a>
            </div>

        </div>
    </div>

</body>

</html>
