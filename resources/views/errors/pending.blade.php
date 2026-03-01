<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Pending Approval</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #101820;
            color: #fff;
            font-family: 'Segoe UI', sans-serif;
        }

        .pending-wrapper {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .pending-card {
            background: linear-gradient(180deg, #1F2E3A, #17232D);
            border-radius: 25px;
            padding: 40px 30px;
            margin: 0px 20px;
            text-align: center;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 0 20px rgba(200, 182, 138, 0.5);
        }

        .pending-card h2 {
            color: #D8C79A;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .pending-card p {
            color: #ccc;
            margin-bottom: 25px;
        }

        .timer-box {
            background: #101820;
            padding: 15px 25px;
            border-radius: 15px;
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 2px;
            display: inline-block;
            margin-bottom: 20px;
            color: #D8C79A;
        }

        .btn-gold {
            background: linear-gradient(180deg, #D8C79A, #B8A06F);
            color: #17232D;
            font-weight: bold;
            border-radius: 15px;
            padding: 10px 20px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-gold:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>

<div class="pending-wrapper">
    <div class="pending-card">
        <h2>Account Pending Approval</h2>
        <p>Your account is currently under review. Please wait for approval.</p>

        <div class="timer-box" id="timer">05:00</div>

        <p>You will be automatically redirected once the approval is complete.</p>

        <a href="{{ route('frontend.home') }}" class="btn-gold">Check Status</a>
    </div>
</div>

<script>
    // Timer: 5 minutes = 300 seconds
    let timeLeft = 300;
    const timerEl = document.getElementById('timer');

    const countdown = setInterval(() => {
        let minutes = Math.floor(timeLeft / 60);
        let seconds = timeLeft % 60;

        // Add leading zeros
        minutes = minutes < 10 ? '0' + minutes : minutes;
        seconds = seconds < 10 ? '0' + seconds : seconds;

        timerEl.textContent = `${minutes}:${seconds}`;

        if (timeLeft <= 0) {
            clearInterval(countdown);
            timerEl.textContent = "00:00";

            // Redirect to frontend.home after timer ends
            window.location.href = "{{ route('frontend.home') }}";
        }

        timeLeft--;
    }, 1000);
</script>

</body>
</html>
