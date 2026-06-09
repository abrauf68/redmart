<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Live Chat Support</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            width: 100%;
            height: 100%;
            overflow: hidden;
            background-color: #ffffff;
        }

        #tawkto-container {
            width: 100%;
            height: 100%;
            position: relative;
        }

        iframe[src*="tawk.to"],
        .tawk-frame-wrapper,
        .tawk-frame {
            width: 100% !important;
            height: 100% !important;
            max-width: 100vw !important;
            max-height: 100vh !important;
            min-width: 100% !important;
            min-height: 100% !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            position: fixed !important;
            border: none !important;
            border-radius: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            z-index: 9999 !important;
        }

        .tawk-branding,
        .tawk-powered-by,
        .tawk-minimize-container,
        .tawk-button--minimize,
        .tawk-chat-header__minimize,
        [class*="tawk-minimize"],
        [aria-label*="Minimize"] {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            pointer-events: none !important;
        }

        @media (max-width: 768px) {
            iframe[src*="tawk.to"] {
                width: 100% !important;
                height: 100% !important;
                top: 0 !important;
                left: 0 !important;
                position: fixed !important;
            }
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            transition: opacity 0.3s ease;
        }
        .spinner {
            width: 48px;
            height: 48px;
            border: 4px solid #e2e8f0;
            border-top: 4px solid #3b82f6;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div id="tawkto-container"></div>
    <div id="loadingOverlay" class="loading-overlay">
        <div class="spinner"></div>
    </div>

    <script type="text/javascript">
        (function() {
            var Tawk_API = Tawk_API || {};
            Tawk_API.onLoad = function() {
                Tawk_API.maximize();
                Tawk_API.showWidget();
            };
            var Tawk_LoadStart = new Date();

            var s = document.createElement('script');
            s.async = true;
            s.src = 'https://embed.tawk.to/68b3f346109d7be2aa211610/1j3vesj59';
            s.charset = 'UTF-8';
            s.setAttribute('crossorigin', '*');

            s.onerror = function() {
                var loadingDiv = document.getElementById('loadingOverlay');
                if (loadingDiv) loadingDiv.style.display = 'none';
            };

            document.head.appendChild(s);
        })();

        window.addEventListener('load', function() {
            var style = document.createElement('style');
            style.textContent = `
                .tawk-branding, .tawk-powered-by, .tawk-minimize-container,
                .tawk-button--minimize, .tawk-chat-header__minimize,
                [class*="tawk-minimize"], [aria-label*="Minimize"] {
                    display: none !important;
                    visibility: hidden !important;
                    opacity: 0 !important;
                    pointer-events: none !important;
                }
                iframe[name^="tawk"], iframe[src*="tawk.to"] {
                    width: 100% !important;
                    height: 100% !important;
                    position: fixed !important;
                    top: 0 !important;
                    left: 0 !important;
                    right: 0 !important;
                    bottom: 0 !important;
                    border: none !important;
                    z-index: 2147483647 !important;
                }
            `;
            document.head.appendChild(style);

            var loadingOverlay = document.getElementById('loadingOverlay');
            if (loadingOverlay && loadingOverlay.style.display !== 'none') {
                loadingOverlay.style.display = 'none';
            }
        });

        window.onresize = function() {
            var tawkIframes = document.querySelectorAll('iframe[src*="tawk.to"]');
            tawkIframes.forEach(function(iframe) {
                iframe.style.width = '100%';
                iframe.style.height = '100%';
                iframe.style.position = 'fixed';
                iframe.style.top = '0';
                iframe.style.left = '0';
            });
        };
    </script>
</body>
</html>
