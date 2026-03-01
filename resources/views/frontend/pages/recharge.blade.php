@extends('frontend.layouts.master')

@section('title', 'Recharge')

@section('css')
<style>
    .recharge-wrapper {
        margin-top: 60px;
        margin-bottom: 40px;
        color: #fff;
    }

    .recharge-card {
        background: #1F2E3A;
        border-radius: 25px;
        padding: 20px;
    }

    .recharge-label {
        font-size: 13px;
        color: #ccc;
        margin-bottom: 8px;
    }

    .recharge-input {
        width: 100%;
        background: #101820;
        border: 1px solid #2c3e50;
        border-radius: 18px;
        padding: 14px;
        color: #fff;
        font-size: 18px;
        font-weight: 600;
        text-align: center;
    }

    .recharge-input:focus {
        outline: none;
        border-color: #D8C79A;
    }

    .quick-amounts {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 15px;
    }

    .amount-btn {
        flex: 1 1 30%;
        background: #101820;
        border-radius: 16px;
        padding: 10px;
        text-align: center;
        font-size: 13px;
        font-weight: 600;
        color: #D8C79A;
        border: 1px solid #2c3e50;
        cursor: pointer;
        transition: 0.3s ease;
    }

    .amount-btn.active,
    .amount-btn:hover {
        background: linear-gradient(180deg, #D8C79A, #B8A06F);
        color: #17232D;
        border-color: transparent;
    }

    .recharge-btn {
        width: 100%;
        margin-top: 20px;
        padding: 14px;
        border-radius: 20px;
        font-weight: bold;
        border: none;
        background: linear-gradient(180deg, #D8C79A, #B8A06F);
        color: #17232D;
        font-size: 15px;
    }
</style>
@endsection

@section('content')

<div class="container recharge-wrapper">
    <div class="row">
        <div class="col-12 px-3">

            <div class="recharge-card">
                <form action="{{ route('frontend.recharge.submit') }}" method="POST">
                    @csrf
                    <div class="recharge-label">
                        Enter Recharge Amount
                    </div>

                    <input type="number" name="amount"
                           id="rechargeAmount"
                           class="recharge-input"
                           placeholder="Enter amount">

                    <!-- Quick Select Amounts -->
                    <div class="quick-amounts">

                        <div class="amount-btn" data-amount="50">50</div>
                        <div class="amount-btn" data-amount="100">100</div>
                        <div class="amount-btn" data-amount="200">200</div>
                        <div class="amount-btn" data-amount="500">500</div>
                        <div class="amount-btn" data-amount="1000">1000</div>
                        <div class="amount-btn" data-amount="2000">2000</div>

                    </div>

                    <button type="submit" class="recharge-btn">
                        Recharge Now
                    </button>
                </form>

            </div>

        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    const amountButtons = document.querySelectorAll('.amount-btn');
    const input = document.getElementById('rechargeAmount');

    amountButtons.forEach(btn => {
        btn.addEventListener('click', function() {

            // Remove active from all
            amountButtons.forEach(b => b.classList.remove('active'));

            // Add active to clicked
            this.classList.add('active');

            // Set input value
            input.value = this.dataset.amount;
        });
    });

    // Remove active if user types manually
    input.addEventListener('input', function() {
        amountButtons.forEach(b => b.classList.remove('active'));
    });
</script>
@endsection
