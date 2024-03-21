@extends('layouts.app')

@section('content')
    @foreach($data as $key => $values)
        <b>{{ $key }}</b>
        <ul>
            @foreach($values as $value)
                <li>
                    <a href={{ route('file.show', ['fileName' => $value['nameForHref']]) }}>{{ $value['nameForHref'] }}</a>
                </li>
            @endforeach
        </ul>
    @endforeach
@endsection
