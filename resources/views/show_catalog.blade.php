@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>{{$catalog->title}}</h5>
                    </div>
                    <h5 class="card-header">
                        <a href="{{ route('catalog.index') }}" class="btn btn-sm btn-outline-primary"></i> Go Back</a>
                    </h5>

                    <div class="card-body">

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

                        <div class="col-md-12">
                            <h4>Description</h4>
                        </div>

                        <div class="col-md-12">
                            {{$catalog->description}}
                        </div>

                        <table class="table">
                            <thead>
                            <th scope="col">Items</th>
                            </thead>
                            <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td scope="row">{{ $item->title }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
