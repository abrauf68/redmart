@extends('frontend.layouts.master')

@section('title', 'Support')

@section('css')

@endsection

@section('content')
    <div class="row mb-4">
        <div class="col-12 px-3">
            <div class="card border-0 rounded-20 p-4 text-center"
                style="background: linear-gradient(180deg,#1F2E3A,#17232D); color:#fff;">

                <div class="mb-3">
                    <i class="bi bi-headset" style="font-size:48px; color:#D8C79A;"></i>
                </div>

                <h5 class="fw-bold">Support</h5>
                <p class="text-muted small mb-3">
                    If your recharge is not successful or facing any issue,
                    please contact our support team.
                </p>

                <div class="d-grid gap-3">

                    <!-- WhatsApp Support -->
                    <a href="https://wa.me/923000000000" target="_blank" class="btn rounded-15"
                        style="background: linear-gradient(180deg,#D8C79A,#B8A06F); color:#17232D;">
                        <i class="bi bi-whatsapp me-2"></i> Contact on WhatsApp
                    </a>

                    <!-- Telegram Support -->
                    <a href="#" class="btn btn-outline-light rounded-15">
                        <i class="bi bi-telegram me-2"></i> Contact on Telegram
                    </a>

                    <!-- Email Support -->
                    <a href="mailto:support@example.com" class="btn btn-outline-light rounded-15">
                        <i class="bi bi-envelope-fill me-2"></i> Email Support
                    </a>

                </div>

            </div>
        </div>
    </div>


    <!-- Info Section -->
    <div class="row">
        <div class="col-12 px-3">
            <div class="card border-0 rounded-20 p-3" style="background:#101820; color:#C8B68A;">

                <h6 class="mb-3 text-white">
                    <i class="bi bi-info-circle me-2"></i> Important Notice
                </h6>

                <ul class="small mb-0">
                    <li>Please share your registered phone number.</li>
                    <li>Attach payment screenshot for quick response.</li>
                    <li>Support response time: 5 - 15 minutes.</li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('script')
@endsection
