@extends('layouts.app')

@push('js')
    @vite(['resources/js/dashboard.js'])
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card" style="margin-bottom: 25px">
                    <div class="card-header">
                        Create vote
                    </div>
                    <div class="card-body">
                        <form action="{{ route('dashboard.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" id="title" name='title'>
                            </div>
                            <div class="form-group">
                                <div class="add-choose">
                                    <h6>Add choose</h6>
                                    <input type="text" class="form-control choose" name='choose'>
                                    <button type="button" class="btn btn-success" >+ Add</button>
                                </div>
                                <div class="vote-chooses"></div>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
                <div class="my-votes">
                    <h5>My Votes</h5>
                    @foreach($votes as $vote)
                        <div class="card">
                            <div class="card-header">
                                <span>{{ $vote -> title }}</span>
                                <span>Polls: {{ $vote -> polls_count }}</span>
                            </div>
                            <div class="card-body chooses">
                                <h6 class="vote-link">Vote Link: <a target="_blank" href='{{ url('/votes/' . $vote -> id) }}'>{{ url('/votes/' . $vote -> id) }}</a></h6>
                                @foreach ($vote -> chooses as $choose)
                                <div class="choose">
                                    <div class="title">
                                        <span class="content">{{ $choose -> choose }}</span>
                                        <span class="prog">{{ $vote -> polls_count == 0 ? 0 : $choose -> polls_count / $vote -> polls_count * 100 }}%</span>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped bg-info" role="progressbar" style="width: {{ $vote -> polls_count == 0 ? 0 : $choose -> polls_count / $vote -> polls_count * 100 }}%" aria-valuenow="{{ $vote -> polls_count == 0 ? 0 : $choose -> polls_count / $vote -> polls_count * 100 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
