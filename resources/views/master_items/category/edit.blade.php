@extends('layouts.app')

@section('content')

<div class="container">

    <h3>Edit Category</h3>

    @if ($errors->any())

        <div class="alert alert-danger">

            <ul>

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    <form action="{{ route('categories.update', $category->id) }}"
          method="POST">

        @csrf
        @method('PUT')

        <div class="mb-3">

            <label>Kode</label>

            <input type="text"
                   name="kode"
                   class="form-control"
                   value="{{ old('kode', $category->kode) }}">

        </div>

        <div class="mb-3">

            <label>Name</label>

            <input type="text"
                   name="name"
                   class="form-control"
                   value="{{ old('name', $category->name) }}">

        </div>

        <button type="submit"
                class="btn btn-primary">
            Update
        </button>

        <a href="{{ route('categories.index') }}"
           class="btn btn-secondary">
            Back
        </a>

    </form>

</div>

@endsection