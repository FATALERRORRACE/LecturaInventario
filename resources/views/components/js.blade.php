<script>
    const headers = new Headers();
    headers.append("Content-Encoding", "br");
    headers.append("Accept-Encoding", "gzip, compress, br");
    headers.append("Accept", "application/json, text/plain, */*");
    headers.append("Content-Type", "application/json");
</script>
@vite([
    'resources/js/app.js',
    'resources/js/login.js'
])