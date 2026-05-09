@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row justify-content-center">

            <div class="col-md-8">
                <div class="form-group mb-2">
                    <a href="{{ route('categories.create') }}" class="btn btn-primary">
                        Tambah Category
                    </a>
                </div>

                {{-- FILTER --}}
                <div class="card mb-3">

                    <div class="card-body">

                        <form method="GET" action="{{ route('categories.index') }}">

                            <div class="row">

                                {{-- SEARCH --}}
                                <div class="col-md-4">

                                    <label>Search Name</label>

                                    <input type="text" name="search" class="form-control"
                                        placeholder="Search category..." value="{{ request('search') }}">

                                </div>

                                {{-- FILTER KODE --}}
                                <div class="col-md-3">

                                    <label>Filter Kode</label>

                                    <input type="text" name="kode" class="form-control" placeholder="Input kode..."
                                        value="{{ request('kode') }}">

                                </div>

                                {{-- BUTTON --}}
                                <div class="col-md-3 d-flex align-items-end">

                                    <button type="submit" class="btn btn-primary me-2">
                                        Filter
                                    </button>

                                    <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                                        Reset
                                    </a>

                                    <a href="{{ route('category.pdf') }}?search={{ request('search') }}&kode={{ request('kode') }}"
                                        class="btn btn-danger" style="margin-left: 0.5rem" >
                                        Print
                                    </a>

                                </div>

                            </div>

                        </form>

                    </div>

                </div>

                {{-- SUCCESS --}}
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- TABLE --}}
                <table class="table table-bordered">

                    <thead>

                        <tr>

                            <th width="70">No</th>
                            <th>Kode</th>
                            <th>Name</th>
                            <th width="200">Action</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($categories as $key => $category)
                            <tr>

                                <td>
                                    {{ $categories->firstItem() + $key }}
                                </td>

                                <td>{{ $category->kode }}</td>

                                <td>{{ $category->name }}</td>

                                <td>

                                    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning btn-sm">
                                        Edit
                                    </a>

                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                        style="display:inline-block">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Delete this data?')">
                                            Delete
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4" class="text-center">
                                    No Data
                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

                <table class="table table-bordered">

                    <thead>

                        <tr>

                            <th>Kode</th>
                            <th>Category</th>
                            <th>Items</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($categories as $category)
                            <tr>

                                <td>{{ $category->kode }}</td>

                                <td>{{ $category->name }}</td>

                                <td>

                                    @forelse($category->masterItems as $item)
                                        <span class="badge bg-primary">
                                            {{ $item->nama }}
                                        </span>

                                    @empty

                                        <span class="text-muted">
                                            No Item
                                        </span>
                                    @endforelse

                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

                {{-- PAGINATION --}}
                {{ $categories->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
