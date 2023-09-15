@extends('admin.layouts.app')
@section('section')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>Rewards Sent</h3>
                            <a href="#" type="button" id="btn_rewards_this_month" style="color: inherit;">
                                <p>
                                    This Month: ${{$total_reward_amount_this_month}}
                                </p>
                            </a>
                            <br/>
                            <a href="#" type="button" id="btn_rewards_this_year" style="color: inherit;">
                                <p>
                                    This Year: ${{$total_reward_amount_this_year}}
                                </p>
                            </a>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>Payments Received</h3>
                            <a href="#" type="button" id="btn_payments_this_month" style="color: inherit;">
                                <p>
    {{--                                This Month: ${{$total_payments_this_month}}--}}
                                    This Month: $100
                                </p>
                            </a>
                            <br/>
                            <a href="#" type="button" id="btn_payments_this_year" style="color: inherit;">
                                <p>
    {{--                                This Year: ${{$total_payments_this_year}}--}}
                                    This Year: $100
                                </p>
                            </a>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>Gross Income</h3>
                            <p>
{{--                                This Month: ${{$total_payments_this_month}}--}}
                                This Month: $100
                            </p>
                            <p>
{{--                                This Year: ${{$total_payments_this_year}}--}}
                                This Year: $100
                            </p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>Company Profit</h3>
                            <p>
{{--                                This Month: ${{$total_payments_this_month - $total_reward_amount_this_month}}--}}
                                This Month: $100
                            </p>
                            <p>
{{--                                This Year: ${{$total_payments_this_year - $total_reward_amount_this_year}}--}}
                                This Year: $100
                            </p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>

                <!-- ./col -->
            </div>
            {{-- latest orders --}}
            <div class="row mt-4 mb-3">


            {{-- latest orders end --}}

            {{-- latest Reviews --}}


            </div>
            {{-- latest orders end --}}
            <!-- /.row -->
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

{{--monthly rewards modal--}}
<div class="modal fade" id="modal_rewards_this_month" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Rewards This Month</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Amount</th>
                            <th>On Inviting</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rewards_this_month as $reward_log)
                            <tr>
                                <td>{{($reward_log->reward->user->profile->first_name ?? '') . ' ' . ($reward_log->reward->user->profile->last_name ?? '')}}</td>
                                <td>${{$reward_log->reward->amount}}</td>
                                <td>{{($reward_log->reward->invited_user->profile->first_name ?? '') . ' ' . ($reward_log->reward->invited_user->profile->last_name ?? '')}}</td>
                                <td>{{$reward_log->created_at}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{--yearly rewards modal--}}
<div class="modal fade" id="modal_rewards_this_year" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Rewards This Year</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Amount</th>
                            <th>On Inviting</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rewards_this_year as $reward_log)
                            <tr>
                                <td>{{($reward_log->reward->user->profile->first_name ?? '') . ' ' . ($reward_log->reward->user->profile->last_name ?? '')}}</td>
                                <td>${{$reward_log->reward->amount}}</td>
                                <td>{{($reward_log->reward->invited_user->profile->first_name ?? '') . ' ' . ($reward_log->reward->invited_user->profile->last_name ?? '')}}</td>
                                <td>{{$reward_log->created_at}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{--monthly payments modal--}}
<div class="modal fade" id="modal_payments_this_year" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Payments This Month</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($incoming_payments_this_month as $payment)
                            @if (!is_null($payment->user))
                                <tr>
                                    <td>{{($payment->user->profile->first_name ?? '') . ' ' . ($payment->user->profile->last_name ?? '')}}</td>
                                    <td>${{($payment->total / 100) - 12}}</td>
                                    <td>{{\Carbon\Carbon::createFromTimestamp($payment->date)}}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{--yearly payments modal--}}
<div class="modal fade" id="modal_payments_this_year" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Payments This Year</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($incoming_payments_this_year as $payment)
                            @if (!is_null($payment->user))
                                <tr>
                                    <td>{{($payment->user->profile->first_name ?? '') . ' ' . ($payment->user->profile->last_name ?? '')}}</td>
                                    <td>${{($payment->total / 100) - 12}}</td>
                                    <td>{{\Carbon\Carbon::createFromTimestamp($payment->date)}}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $('#btn_rewards_this_month').on('click', function () {
                $('#modal_rewards_this_month').modal('show');
            });
            $('#btn_rewards_this_year').on('click', function () {
                $('#modal_rewards_this_year').modal('show');
            });
            $('#btn_payments_this_month').on('click', function () {
                $('#modal_payments_this_year').modal('show');
            });
            $('#btn_payments_this_year').on('click', function () {
                $('#modal_payments_this_year').modal('show');
            });
        });
    </script>
@endsection
