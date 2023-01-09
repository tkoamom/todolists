@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <h5 class="card-header">
                    <a href="{{ route('catalog.create') }}" class="btn btn-sm btn-outline-primary">Add list</a>
                </h5>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session()->has('success'))
                        <div class="alert alert-success">
                            <button type="button" class="close" data-bs-dismiss="alert">×</button>
                            {{ session()->get('success') }}
                        </div>
                    @endif

                    <table class="table table-hover table-borderless">
                        <thead>
                            <th scope="col">To do lists</th>
                            <th scope="col"></th>
                        </thead>
                        <tbody>
                        @forelse ($catalogs as $catalog)
                            <tr>
                                <td scope="row"><a href="{{ route('catalog.show', $catalog->id) }}" style="color: black">{{ $catalog->title }}</a></td>
                                <td>
                                    <a href="{{ route('catalog.edit', $catalog->id) }}" class="btn btn-sm btn-outline-success"><i class="fa-solid fa-pen-to-square"></i></a>
                                    <a href="" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-id="{{$catalog->id}}"><i class="fa-solid fa-xmark"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                No To do lists!
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="deleteModal" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Are you sure you want to delete this To do list?</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-footer">
                <form method="POST" id="delete_list" action="{{route('catalog.destroy', 1)}}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger pull-right" data-bs-dismiss="modal">Delete</button>
                </form>
                <button type="button" class="btn btn-default pull-right" data-bs-dismiss="modal">Cancel</button>
            </div>

        </div>
    </div>
</div>
@endsection
