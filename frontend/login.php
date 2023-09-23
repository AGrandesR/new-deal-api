<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New deal</title>
</head>
<body>
    <h1>Register</h1>
    <p><label>Mail</label><input type="text" id="mail"/></p>
    <p><label>Password</label><input type="text" id="password"/></p>
    <input type="button" value="Registrarse" onclick="register(event)">
    <p id="result" style="background: black; color: white;"></p>
    <script>
        function register(event) {
            const formData  = new FormData()
            formData.append('mail',document.getElementById('mail').value)
            formData.append('pass',document.getElementById('password').value)

            fetch('http://localhost:9898/public/login.php',{
                method:'POST',
                body: formData
            })
            .then(raw=>raw.json())
            .then((json)=>{
                localStorage.setItem('token',json.token)
                window.location.href="http://localhost:9898/frontend/index.php"
            }).catch((e)=>document.getElementById('result').innerHTML='Something fail in the POST')
        }
    </script>
</body>
</html>