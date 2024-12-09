<script type="text/javascript" src="/js/mdb.umd.min.js"></script>
<script>
    var gridInstance;
    var subgridInstance;
    const headers = new Headers();
    headers.append("Content-Encoding", "br");
    headers.append("Accept-Encoding", "gzip, compress, br");
    headers.append("Authorization", 'Bearer {{session('apiToken')}}');
    headers.append("Accept", "application/json, text/plain, */*");
    headers.append("Content-Type", 'application/json, text/plain, */*');
    const headersMultipart = new Headers();
    headersMultipart.append("Content-Encoding", "br");
    headersMultipart.append("Accept-Encoding", "gzip, compress, br");
    headersMultipart.append("Authorization", 'Bearer {{session('apiToken')}}');
    headersMultipart.append("Accept", "application/json, text/plain, */*");
</script>
@if(session('username') != '')
<script type="text/javascript" src="/js/app.js"></script>
<script type="text/javascript" src="/js/dragAndDrop.js"></script>
@else
    <script type="text/javascript" src="/js/login.js"></script>
@endif