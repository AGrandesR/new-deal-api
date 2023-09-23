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
    <p id="result" style="background-color: black; color: white;"></p>
    <script>
        function register(event) {
            const result = document.getElementById('result')
            const formdata  = new FormData()
            formdata.append('mail',document.getElementById('mail').value)
            formdata.append('pass',document.getElementById('password').value)

            fetch('http://localhost:9898/public/register.php',{
                method: 'POST',
                body: formdata,
                redirect: 'follow'
            })
            .then(raw=>raw.json())
            .then((json)=>{
                if(json.status=='ok')
                    document.getElementById('result').innerHTML='OK - Check your mail'
                else 
                    document.getElementById('result').innerHTML=json.message
            }).catch((e)=>document.getElementById('result').innerHTML='Something fail in the POST')
        }
    </script>
</body>
</html>