@extends('frontend.layouts.master')

@section('title', 'Live Chat')

@section('css')
<style>
    .recharge-wrapper {
        padding: 0;
    }

    .chat-iframe-wrapper {
        position: fixed;
        top: 80px;
        bottom: 70px;
        left: 10px;
        right: 10px;
        z-index: 0;
    }

    .chat-iframe-wrapper iframe {
        width: 100%;
        height: 100%;
        border: none;
        display: block;
        border-radius: 20px
    }
</style>
@endsection

@section('content')
@endsection

@section('script')
<script>
    // Move iframe outside .main-container so it's not constrained
    document.addEventListener('DOMContentLoaded', function() {
        var wrapper = document.createElement('div');
        wrapper.className = 'chat-iframe-wrapper';
        var iframe = document.createElement('iframe');
        iframe.src = 'https://tawk.to/chat/68b3f346109d7be2aa211610/1j3vesj59';
        iframe.frameBorder = '0';
        wrapper.appendChild(iframe);
        document.body.appendChild(wrapper);
    });
</script>
@endsection

