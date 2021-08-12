@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('message'))
    <div class="alert alert-danger col-md-6 mx-auto">
        {{session('message')}}
    </div>
    @endif
    <form action="{{url('formato/generar-formato')}}" method="post">
        @csrf

        <div class="py-2">
            <label class="form-label" for="format_number">Número: </label> <span data-bs-toggle="tooltip" data-bs-placement="top" title="Es el número del fromato, este va anexado al nombre del documento PDF para ordenar.">*</span>
            <input class="form-control col-md-2" type="number" name="format_number" id="" min="1" step="1" value="{{old('format_number')}}" required>
        </div>
        <div class="py-2">
            <label class="form-label" for="title">Título del artículo: </label> <span>*</span>
            <input class="form-control" type="text" name="title" id="" value="{{old('title')}}" required>
        </div>
        <div class="py-2">
            <label class="form-label" for="paper_event">Revista y/o evento: </label> <span data-bs-toggle="tooltip" data-bs-placement="top" title="Nombre de la revista, evento o artículo.">*</span>
            <input class="form-control " type="text" name="paper_event" id="" value="{{old('paper_event')}}" required>
        </div>
        <div class="py-2"><label class="form-label" for="year">Año: </label> <span>*</span>
            <input class="form-control col-md-2" type="number" name="year" id="" min="1950" value="{{old('year')}}" required>
        </div>
        <div class="py-2">
            <label class="form-label" for="authors">Autor(es): </label> </label> <span data-bs-toggle="tooltip" data-bs-placement="top" title="Autores del texto, separados por coma.">*</span>
            <input class="form-control " type="text" name="authors" id="" value="{{old('authors')}}" required>
        </div>
        <div class="py-2">
            <label class="form-label" for="univ_inst">Universidad o institución: </label> <span>*</span>
            <input class="form-control" type="text" name="univ_inst" id="" value="{{old('univ_inst')}}" required>
        </div>
        <div class="py-2">

        </div>
        <div class="py-2">
            <label class="form-label" for="reference">Referencia APA: </label> <span>*</span>
            <input class="form-control " type="text" name="reference" id="" value="{{old('reference')}}" required>
        </div>
        <div class="py-2">
        </div>
        <div class="py-2">
            <label class="form-label" for="keyword">Palabras clave: </label> </label> <span data-bs-toggle="tooltip" data-bs-placement="top" title="Palabras clave del texto, separadas por coma.">*</span>
            <input class="form-control" type="text" name="keyword" id="" value="{{old('keyword')}}" required>
        </div>
        <div class="py-2">
            <label class="form-label" for="resume">Resumen: </label> </label> <span data-bs-toggle="tooltip" data-bs-placement="top" title="Resumen del texto leído, hecho por usted mismo.">*</span>
            <textarea class="form-control " name="resume" id="" cols="30" rows="10" required>{{old('resume')}}</textarea>
        </div>

        <div class="py-2">
            <label class="form-label" for="tools">Herramientas utilizadas: </label> </label> <span data-bs-toggle="tooltip" data-bs-placement="top" title="Elementos que utilizaron para elaborar el documento, tecnologias, conceptos, etc.">*</span>
            <input class="form-control " type="text" name="tools" id="" value="{{old('tools')}}" required>
        </div>
        <div class="py-2">
            <label class="form-label" for="comments">Comentarios</label>
            <textarea class="form-control " name="comments" id="" cols="30" rows="10">{{old('comments')}}</textarea>
        </div>
        <div class="py-2">
            <label class="form-label" for="rate">Puntuación: </label> </label> <span data-bs-toggle="tooltip" data-bs-placement="top" title="Del 1 al 5.">*</span>
            <input class="form-control col-md-2" type="number" name="rate" id="" min="1" max="5" step="1" value="{{old('rate')}}" required>
        </div>
        <div class="boton d-flex">
            <button class="btn btn-success mx-auto my-3" type="submit">Generar formato <br><i class="far fa-file-pdf"></i></input></button>
        </div>
    </form>
</div>
@endsection