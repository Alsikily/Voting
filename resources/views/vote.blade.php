@extends('layouts.app')

@push('js')
    @vite(['resources/js/vote.js'])
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 my-votes">
            <div class="card" style="margin-bottom: 25px">
                <div class="card-header">
                    <span>{{ $vote -> title }}</span>
                    <span class="vote-polls">Polls: <span class="polls">{{ $vote -> polls_count }}</span></span>
                </div>
                <div class="card-body vote-chooses-container" data-vote-polls='{{ $vote -> polls_count }}'>
                    @foreach ($vote -> chooses as $choose)
                        <div class="choose" data-id='{{ $choose -> id }}' id='choose_{{ $choose -> id }}' data-choose-id='{{ $choose -> id }}' data-choose-polls='{{ $choose -> polls_count }}'>
                            <input type="radio" name="choose_id" value="{{ $choose -> id }}" @checked($choose -> poll != null)>
                            <div class="content">
                                <div class="info">
                                    <div class="item">
                                        <span class="select"></span>
                                        <span class="title">
                                            {{ $choose -> choose }}
                                        </span>
                                    </div>
                                    <div class="prog">
                                        <span class="prog-value">{{ floor($vote -> polls_count == 0 ? 0 : $choose -> polls_count / $vote -> polls_count * 1000) / 10 }}</span>%
                                    </div>
                                </div>
                                <div class="progress-bar">
                                    <span class="progress" style="width: {{ floor($vote -> polls_count == 0 ? 0 : $choose -> polls_count / $vote -> polls_count * 1000) / 10 }}%"></span>
                                </div>
                            </div>
                            <span class="choose-polls-count">
                                polls:
                                <span class="polls">{{ $choose -> polls_count }}</span>
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    @vite(['resources/js/vote.js'])

    <script>

        document.addEventListener('DOMContentLoaded', function() {

            let votes = document.querySelectorAll('.vote-chooses-container .choose > input');
            let chooses = document.querySelectorAll('.vote-chooses-container .choose');
            let votePollsSpan = document.querySelector('.vote-polls .polls');
            let userID = '{{ auth() -> user() -> id }}';
            let voteID = '{{ $vote -> id }}';
            let voteChooses = document.querySelectorAll('.vote-chooses-container .choose > input');
            let UserHasChoosen = document.querySelectorAll('.vote-chooses-container .choose > input[checked]');

            function increaseVotePolls() {
                let votePolls = parseInt(votePollsSpan.textContent);
                let newVotePolls = votePolls + 1;
                votePollsSpan.textContent = newVotePolls;
            }

            function increaseChoosePolls(chooseID) {
                let chooseMain = document.querySelector(`.vote-chooses-container #choose_${chooseID}`);
                let choosePolls = document.querySelector(`.vote-chooses-container #choose_${chooseID} .choose-polls-count .polls`);
                let newPolls = parseInt(choosePolls.textContent) + 1;

                chooseMain.setAttribute('data-choose-polls', newPolls);
                choosePolls.textContent = newPolls;

            }

            function increaseChooseProg(chooseID) {

                let chooseProgValue = document.querySelector(`.vote-chooses-container #choose_${chooseID} .prog .prog-value`);
                let chooseDiv = document.querySelector(`.vote-chooses-container #choose_${chooseID}`);
                let choosePolls = chooseDiv.getAttribute('data-choose-polls');                
                let newVotePolls = parseInt(votePollsSpan.textContent);
                let newProg = newVotePolls != 0 ? (choosePolls / newVotePolls) * 100 : 0;
                let newProgressNumber = Math.floor(newProg * 10) / 10;

                chooseDiv.setAttribute('data-choose-polls', choosePolls);
                chooseProgValue.textContent = newProgressNumber;

            }

            function chooseProgressBar(chooseID, chooseItem, newChoosePolls) {

                let newVotePolls = parseInt(votePollsSpan.textContent);
                let newProgress = newVotePolls != 0 ? (newChoosePolls / newVotePolls) * 100 : 0;
                let progressBar = document.querySelector(`.vote-chooses-container #choose_${chooseID} .progress-bar .progress`);

                progressBar.style.width = `${newProgress}%`;

            }

            function increaseVariables(chooseID) {
                increaseVotePolls();
                increaseChoosePolls(chooseID);
                chooses.forEach(chooseItem => {
                    let currentChooseID = parseInt(chooseItem.getAttribute('data-id'));
                    increaseChooseProg(currentChooseID);
                    chooseProgressBar(currentChooseID, parseInt(chooseItem.getAttribute('data-choose-id')), parseInt(chooseItem.getAttribute('data-choose-polls')));
                });
            }

            function executeFunction(voteID, chooseID) {

                stopChoosing();
                let xhr = new XMLHttpRequest();
                let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                xhr.open('POST', '{{ route("votes.store") }}', true);
                xhr.setRequestHeader('Content-Type', 'application/json');
                xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            let response = JSON.parse(xhr.responseText);
                            socket.emit('pollVoteServer', chooseID);
                        } else {
                            console.error('Error:', xhr.status);
                        }
                    }
                };
                let data = JSON.stringify({ voteID: voteID, chooseID: chooseID });
                xhr.send(data);

            }

            function stopChoosing() {
                voteChooses.forEach(choose => {
                    choose.style.pointerEvents = 'none';
                });
            }

            if (UserHasChoosen.length == 0) {
                voteChooses.forEach(choose => {
                    choose.addEventListener('change', function(event) {
                        if (event.target.checked) {
                            let chooseID = event.target.value;
                            // increaseVariables(chooseID);
                            executeFunction(voteID, chooseID);
                        }
                    });
                });
            } else {
                stopChoosing();
            }

            let socket = io('127.0.0.1:3000', {
                query: {
                    voteID: voteID,
                    userID: userID
                }
            });
            // socket.on('connection');

            socket.on('pollVoteClient', (voteChooseID) => {
                increaseVariables(voteChooseID);
                console.log('Choose ID: ', voteChooseID);
            });

        });

    </script>

@endpush