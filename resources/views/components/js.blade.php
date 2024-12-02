<script type="text/javascript" src="/js/mdb.umd.min.js"></script>
<script>
    var gridInstance;
    const headers = new Headers();
    headers.append("Content-Encoding", "br");
    headers.append("Accept-Encoding", "gzip, compress, br");
    headers.append("Authorization", 'Bearer {{session('apiToken')}}');
    headers.append("Accept", "application/json, text/plain, */*");
    headers.append("Content-Type", "application/json");
</script>
@if(session('username') != '')
    <script type="text/javascript" src="/js/app.js"></script>
@else
    <script type="text/javascript" src="/js/login.js"></script>
@endif