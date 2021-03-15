@extends('layout')
@section('title',"Salle d'attente")
@section('salle-d-attente')
<div class="media-body">
        <div class="row">
          <div class="col align-self-center text-center">
            <div class="spinner-border text-danger"></div>
            <br>
          <div class="form-group form-group-inline">
            <h3>Veuillez attendre vos adversaires..</h3>
            <p>0 Joueur(s) restant(s)</p><br>
          </div> 
          <div class="form-group">
          
          <div class="col-md-8 col-md-offset-2 bootstrap snippets bootdeys">
    <div class="widget-container scrollable list rollodex">
      <div class="heading">
        <i class="fa fa-comment"></i>PartieID<i class="fa fa-plus pull-right"></i><i class="fa fa-search pull-right"></i><i class="fa fa-refresh pull-right"></i>
      </div>
      <div class="widget-content">
        <div class="roll-title">  
        </div>
        <ul>
          <li>
            <img width="30" height="30" src="{{ asset('img/j1.jpg') }}"><a href="#"><br>Mohamed Aziz Touali</a>
          </li>
          <li>
            <img width="30" height="30" src="{{ asset('img/j2.jpg') }}"><a href="#"><br>Jelel Fliss</a>
          </li>
          <li>
            <img width="30" height="30" src="{{ asset('img/j3.jpg') }}"><a href="#"><br>Achref Mabrouk</a>
          </li>
          <li>
            <img width="30" height="30" src="{{ asset('img/j4.jpg') }}"><a href="#"><br>Nesrine Hadj Khelil</a>
          </li>
        </ul>
        <br>
      </div><br>
      <div class="form-group">
          <button class="btn btn-danger" type="button">Quitter</button>
      </div>
@endsection