<!doctype html>
<html lang="id">
  <head>
    <title>Ticketly API Reference & Documentation</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" type="image/png" href="<?= base_url('assets/ticketly-logo.png') ?>" />
    <style>
      body {
        margin: 0;
        padding: 0;
        background-color: #0f172a;
        font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
      }
    </style>
  </head>
  <body>
    <!-- Scalar API Reference Container -->
    <script
      id="api-reference"
      type="application/json"
      data-url="<?= base_url('openapi.json') ?>"
      data-configuration='{
        "theme": "purple",
        "layout": "modern",
        "showSidebar": true,
        "searchHotKey": "k",
        "hideDownloadButton": false,
        "darkMode": true,
        "defaultHttpClient": {
          "targetKey": "dart",
          "clientKey": "http"
        }
      }'></script>

    <!-- Scalar API Reference CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@scalar/api-reference"></script>
  </body>
</html>
