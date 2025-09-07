@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Server Information</h2>
    <table class="table table-bordered mt-3">
        <tbody>
            @foreach($serverInfo as $key => $value)
            <tr>
                <th>{{ ucwords(str_replace('_', ' ', $key)) }}</th>
                <td>{{ $value }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
