<ul>
<li><a href="http://localhost:9898/frontend/login">login</a></li>
<li><a href="http://localhost:9898/frontend/register">register</a></li>
<li><a href="http://localhost:9898/frontend/trust">trust</a></li>
<li><a href="http://localhost:9898/frontend/vote">vote</a></li>
</ul>
<script>
    const token = localStorage.getItem('token')
    if(token='') window.location.href='http://localhost:9898/frontend/login'
    
</script>