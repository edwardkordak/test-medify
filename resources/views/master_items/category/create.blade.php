@extends('layouts.app')

@section('content')

<div class="container">

    <h3>Create Category</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>
        </div>
    @endif

    <form action="{{ route('categories.store') }}" method="POST">

        @csrf

        <div class="mb-3">
            <label>Kode</label>

            <input
                type="text"
                name="kode"
                class="form-control"
                value="{{ old('kode') }}"
            >
        </div>

        <div class="mb-3">
            <label>Name</label>

            <input
                type="text"
                name="name"
                class="form-control"
                value="{{ old('name') }}"
            >
        </div>

        <button type="submit" class="btn btn-primary">
            Save
        </button>

    </form>

</div>

@endsection