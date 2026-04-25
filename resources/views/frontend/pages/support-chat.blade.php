<!DOCTYPE html>
<html>

<head>
    <title>Online Chat Support</title>
    <style>
        html,
        body {
            margin: 0;
            height: 100%;
        }

        #crisp-container {
            width: 100%;
            height: 100%;
        }

        .crisp-client .cc-1brb6 .cc-1xry0 {
            width: 100% !important;
            height: 100% !important;
            max-height: 100% !important;
            bottom: 0 !important;
            right: 0 !important;
            border-radius: 0 !important;
        }

        .crisp-client {
            z-index: 9999 !important;
        }

        @media (max-width: 768px) {
            .crisp-client .cc-1brb6 .cc-1xry0 {
                width: 100% !important;
                height: 100% !important;
                bottom: 0 !important;
                right: 0 !important;
                border-radius: 0 !important;
            }
        }
    </style>
</head>

<body>
    <div id="crisp-container"></div>
    <!-- Crisp Chat Embedded -->
    <script type="text/javascript">
        window.$crisp = [];
        window.CRISP_WEBSITE_ID = "2b6de554-9720-419a-8d7c-669c0a40917d";

        (function() {
            var d = document;
            var s = d.createElement("script");
            s.src = "https://client.crisp.chat/l.js";
            s.async = 1;
            s.onload = function() {
                // 👇 THIS is the correct command
                $crisp.push(["do", "chat:open"]);
            };
            d.getElementsByTagName("head")[0].appendChild(s);
        })();
    </script>
</body>

</html>
