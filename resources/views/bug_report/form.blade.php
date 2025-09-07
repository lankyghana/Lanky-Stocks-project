@extends('templates.trendy.layouts.master')

@section('content')
<div class="container mt-4">
    <h2>Report a Bug</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('bug.report.submit') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>
        <div class="form-group mt-2">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control" rows="5" required></textarea>
        </div>
        <div class="form-group mt-2">
            <label for="screenshot">Screenshot (optional)</label>
            <input type="file" name="screenshot" id="screenshot" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary mt-3">Submit Bug Report</button>
    </form>
</div>
@endsection
