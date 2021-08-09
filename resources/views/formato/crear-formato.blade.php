@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('message'))
    <div class="alert alert-success col-md-6 mx-auto">
        {{session('message')}}
    </div>
    @endif



    <h1 class="text-center">Formato de control de lectura.</h1>
    <form action="{{url('api/formato/generar-formato')}}" method="post">
        @csrf

        <div class="py-2">
            <label class="form-label" for="format_number">Número:</label>
            <input class="form-control col-md-2" type="number" name="format_number" id="" min="1" step="1" value="{{old('format_number')}}" required>
        </div>
        <div class="py-2">
            <label class="form-label" for="title">Título del artículo</label>
            <input class="form-control" type="text" name="title" id="" value="{{old('title')}}" required>
        </div>
        <div class="py-2">
            <label class="form-label" for="paper_event">Revista y/o evento</label>
            <input class="form-control " type="text" name="paper_event" id="" value="{{old('paper_event')}}" required>
        </div>
        <div class="py-2"><label class="form-label" for="year">Año</label>
            <input class="form-control col-md-2" type="number" name="year" id="" min="1950" value="{{old('year')}}" required>
        </div>
        <div class="py-2">
            <label class="form-label" for="authors">Autor(es)</label>
            <input class="form-control " type="text" name="authors" id="" value="{{old('authors')}}" required>
        </div>
        <div class="py-2">
            <label class="form-label" for="univ_inst">Universidad o institución</label>
            <input class="form-control" type="text" name="univ_inst" id="" value="{{old('univ_inst')}}" required>
        </div>
        <div class="py-2">

        </div>
        <div class="py-2">
            <label class="form-label" for="reference">Referencia APA</label>
            <input class="form-control " type="text" name="reference" id="" value="{{old('reference')}}" required>
        </div>
        <div class="py-2">

        </div>
        <div class="py-2">
            <label class="form-label" for="keyword">Palabras clave</label>
            <input class="form-control" type="text" name="keyword" id="" value="{{old('keyword')}}" required>
        </div>
        <div class="py-2">
            <label class="form-label" for="resume">Resumen</label>
            <textarea class="form-control " name="resume" id="" cols="30" rows="10" required>{{old('resume')}}</textarea>
        </div>

        <div class="py-2">
            <label class="form-label" for="tools">Herramientas utilizadas</label>
            <input class="form-control " type="text" name="tools" id="" value="{{old('tools')}}" required>
        </div>
        <div class="py-2">
            <label class="form-label" for="comments">Comentarios</label>
            <textarea class="form-control " name="comments" id="" cols="30" rows="10">{{old('comments')}}</textarea>
        </div>
        <div class="py-2">
            <label class="form-label" for="rate">Puntuación:</label>
            <input class="form-control col-md-2" type="number" name="rate" id="" min="1" max="5" step="1" value="{{old('rate')}}" required>
        </div>
        <div class="boton d-flex">
            <input type="submit" value="Generar formato" class="btn btn-success mx-auto my-3">
        </div>
    </form>
</div>
@endsection