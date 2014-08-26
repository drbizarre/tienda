@extends ('layout')

@section ('header')

  <h1>soy header {{ $name }}</h1>

@stop

@section ('content')

  <h1>soy content {{ $name }}</h1>

@stop

@section ('footer')

  <h1>soy footer {{ $name }}</h1>

@stop