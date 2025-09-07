@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $pageTitle }}</h1>
        <p>Use this form to request a bug report or submit an issue to the admin team.</p>
        <!-- Add your bug report form here -->
        <form method="POST" action="#">
            @csrf
            <div class="mb-3">
                <label for="description" class="form-label">Describe the issue</label>
                <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit Report</button>
        </form>
    </div>
@endsection
