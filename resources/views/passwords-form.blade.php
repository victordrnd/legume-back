<head>
    <title>Réinitialisation de mot de passe</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
</head>
    <!------ Include the above in your HEAD tag ---------->

 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
 <div class="form-gap"></div>
<div class="container">
	<div class="row">
		<div class="col-md-4 col-md-offset-4 d-block mx-auto" style="margin-top:15vh">
            <div class="panel panel-default">
              <div class="panel-body">
                <div class="text-center">
                  <img src="{{asset('images/1587998011.jpg')}}" class="w-75 d-block mx-auto"/>
                  <h2 class="text-center">Nouveau mot de passe</h2>
                  <p>Réinitialisation de votre mot de passe</p>
                  <div class="panel-body">
    
                    <form id="register-form" role="form" autocomplete="off" class="form" method="post">
    
                      <div class="form-group">
                        <div class="input-group">
                          <input name="password" placeholder="Nouveau mot de passe" class="form-control"  type="password">
                        </div>
                        <div class="input-group mt-4">
                          <input name="password" placeholder="Répétez votre mot de passe" class="form-control"  type="password">
                        </div>
                      </div>
                      <div class="form-group">
                        <input name="recover-submit" class="btn btn-primary btn-block" value="Réinitialiser" type="submit">
                      </div>
                      
                      <input type="hidden" class="hide" name="token" id="token" value="{{$token}}"> 
                    </form>
    
                  </div>
                </div>
              </div>
            </div>
          </div>
	</div>
</div>