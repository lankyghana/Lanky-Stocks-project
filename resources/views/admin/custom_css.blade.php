@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Custom CSS Settings</h2>
    <form method="POST" action="{{ route('setting.custom.css') }}">
        @csrf
        <div class="form-group">
            <label for="custom_css">Custom CSS</label>
            <textarea name="custom_css" id="custom_css" class="form-control" rows="10">{{ old('custom_css', $generalSetting->custom_css ?? '') }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Save</button>
    </form>
</div>
@endsection
