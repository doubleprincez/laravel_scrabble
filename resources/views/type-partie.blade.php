@extends('layout')
@section('title','Type de Partie')
@section('type-partie')
<div class="inscription ">
    <h2 id="type">Veuillez choisir le type de partie que vous voulez jouer</h2><br><br>

   
<div class="container" >
	<div class="row">
		<form class="form-horizontal" action="/type-partie" method="POST"id="nbj">
        @csrf 
            <fieldset>
                <legend>Nombre minimum des joueurs</legend>
                <div class="form-group"id="nbjbox" >
                    <div class="col-lg-9">
                        <select name="typePartie" class="form-control" id="typePartie" required>
                          <option value=2>2</option>
                          <option value=3>3</option>
                          <option value=4>4</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-8 col-lg-offset-2">
                        <a href="/" class="btn btn-danger btn-raised">Quitter</a>
                        <button type="submit" class="btn btn-default" name="insert">Jouer</button>
                    </div>
                </div>
            </fieldset>
        </form>
	</div>
</div>

<script>

    </script>



</div>
<?php
$connection = mysqli_connect("127.0.0.1","root","");
$db = mysqli_select_db($connection,"laravel");
if (isset($_POST['insert']))
{
    $typePartie = $_POST['typePartie'];
}
?>
@endsection