@extends('site.layouts.dashboard-layout')

@section('navbar')
    <li class="active"><a href="{{URL::to('researcher/dashboard')}}"><i class="fa fa-dashboard fa-lg fa-fw"></i> Home</a></li>
    <li>
        <a href="{{$sessionUrl}}"><i class="fa fa-plus"></i> <i class="fa fa-users fa-lg fa-fw"></i> Add Session</a>
    </li>
@stop

@section('error')
    @if ( $error != null )
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <span>{{(isset($error))? $error : ''}}</span>
        </div>
    @endif
@stop

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h2>Sessions</h2>
        <div class="table-responsive">
            <table id = "sessionTable" class="table table-bordered table-hover tablesorter">
                <thead>
                <tr>
                    <th>Session ID </th>
                    <th>Number of Subjects </th>
                    <th>Session State </th>
                    <th>Manage Session </th>
                </tr>
                </thead>
                <tbody>
                @foreach($sessions as $session)
                <tr>
                    <td>{{ $session->id }}</td>
                    <td>{{ $session->num_subjects }}</td>
                    <td>
                        @if ($session->getState() == $sessionStartState)
                        <span>Started</span>
                        @else
                        <span>Stopped</span>
                        @endif
                    </td>
                    <td>
                        {{ Form::open(array('url'=>$postUrl, 'method'=>'post')) }}
                        {{ Form::hidden($sessionIdKey, $session->getId()) }}
                        @if ($session->getState() == $sessionStartState)
                        {{ Form::hidden($sessionStateKey, $sessionStopState) }}
                        {{ Form::button('Stop', ['type'=>'submit', 'class'=>'btn btn-danger']) }}
                        @else
                        {{ Form::hidden($sessionStateKey, $sessionStartState) }}
                        {{ Form::button('Start', ['type'=>'submit', 'class'=>'btn btn-success']) }}
                        @endif
                        {{ Form::close() }}
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div><!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <h2>Subjects</h2>
        <div class="table-responsive">
            <table id="subjectTable" class="table table-bordered table-hover tablesorter">
                <thead>
                <tr>
                    <th>Session ID </th>
                    <th>User Name </th>
                    <th>Payoff Task ID</th>
                    <th>Payoff</th>
                    <th>Item Purchased</th>
                    <th>Game State</th>
                    <th>Commercial Entries</th>
                </tr>
                </thead>
                <tbody>
                @foreach($subjects as $subject)
                <tr>
                    <td>{{ $subject->session_id }}</td>
                    <td>{{ $subject->user->user_name }}</td>
                    <td>{{ $subject->getPayoffTaskId() }}</td>
                    <td>{{ $subject->getPayoff() }}</td>
                    <td>{{ ($subject->getItemPurchased()) ? "Item Purchased" : "" }}</td>
                    @if ($subject->getState() == $registrationState)
                    <td>Registration</td>
                    @elseif ($subject->getState() == $holdState)
                    <td>Hold</td>
                    @elseif ($subject->getState() == $gameState)
                    <td>Game</td>
                    @elseif ($subject->getState() == $payoffState)
                    <td>Payoff</td>
                    @elseif ($subject->getState() == $questionnaireState)
                    <td>Questionnaire</td>
                    @elseif ($subject->getState() == $completedState)
                    <td>Completed</td>
                    @elseif ($subject->getState() == $preGameQuestionnaireState)
                    <td>Pre-Game Questionnaire</td>
                    @else
                    <td>Undeclared</td>
                    @endif
                    <td>{{ $subject->getSubjectEntryState()->getCommercialBreakEntry() }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div><!-- /.row -->

@stop