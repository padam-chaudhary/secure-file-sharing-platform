{{-- @extends('layouts.app')

@section('title', 'Files List')

@section('content')
<h2>All files</h2>
<ul>
  <li>file 1</li>
  <li>file 2</li>
</ul>
@endsection --}}

<h2>My Files</h2>

@foreach ($files as $file)
    <div style="margin-buttom: 10px; ">
      <span>{{$file->name }}</span>
@can('download', $file)
    <a href="{{route('files.downlaod', $file->id)}}">download</a>
@endcan
    </div>
@endforeach