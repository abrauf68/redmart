<!DOCTYPE html>
<html>
<head>
    <title>Online Chat Support</title>
    <style>
        html, body {
            margin: 0;
            height: 100%;
        }
        #crisp-container {
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
    <div id="crisp-container"></div>

    <!-- Crisp Chat Embedded -->
    <script type="text/javascript">
        window.$crisp=[];
        window.CRISP_WEBSITE_ID="1f3b7ed9-31fa-4e93-8f8a-e1626b27f811";

        (function() {
            var d=document;
            var s=d.createElement("script");
            s.src="https://client.crisp.chat/l.js";
            s.async=1;
            s.onload = function() {
                // Open chat automatically
                $crisp.push(["do", "chat:show"]);
            };
            d.getElementsByTagName("head")[0].appendChild(s);
        })();
    </script>
</body>
</html>
