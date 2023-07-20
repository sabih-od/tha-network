@extends('admin.layouts.app')
@section('title', 'User Detail')
@section('page_css')
    <style>
        .addBtn{
            float: right;
            /*margin-top: 10px;*/
        }
        td{
            text-align: center;
        }
    </style>

@endsection
@section('section')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Users</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">User</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-4">

                        <!-- /.card -->

                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <img class="mr-2" width="40" src="{{$user->get_profile_picture()}}" alt="">
                                    <h2>{{($user->profile->first_name . ' ' . $user->profile->last_name) ?? ''}}</h2>
                                </div>
                            </div>
                        <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered">
                                    <thead>
                                        <tr style="text-align: center">
                                            <th>Joining Date</th>
                                            <td>{{\Carbon\Carbon::parse($user->created_at)->format('M d, Y.')}}</td>
                                        </tr>
                                        <tr style="text-align: center">
                                            <th>Payment Date</th>
                                            <td>{{\Carbon\Carbon::parse($user->created_at)->format('M d, Y.')}}</td>
                                        </tr>
                                        <tr style="text-align: center">
                                            <th>Subscription Status</th>
                                            <td>{{$subscription->status ?? ''}}</td>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>

                    <div class="col-8"></div>

                    {{--payments--}}
                    <div class="col-4">
                        <div class="card">
                            <div class="card-header">
                                <h3>
                                    Payments
                                    <strong><h6>(Total ${{$total_payment}})</h6></strong>
                                </h3>
                            </div>

                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                    <tr style="text-align: center">
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($payments as $payment)
                                        <tr>
                                            <td>{{$payment['amount'] ?? ''}}</td>
                                            <td>{{$payment['date'] ?? ''}}</td>
                                            <td>{!! $payment['status'] ?? '' !!}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{--refferrals--}}
                    <div class="col-4">
                        <div class="card">
                            <div class="card-header">
                                <h3>Referrals</h3>
                            </div>

                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr style="text-align: center">
                                            <th>User</th>
                                            <th>Joining Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($referrals as $referral)
                                            <tr>
                                                <td>{{($referral->invited_user->profile->first_name) ?? '' . ' ' . ($referral->invited_user->profile->last_name ?? '')}}</td>
                                                <td>{{$referral->invited_user && $referral->invited_user->created_at ? \Carbon\Carbon::parse($referral->invited_user->created_at)->format('M d, Y.') : ''}}</td>
                                                <td>{!! is_null($referral->invited_user->closed_on) ? '<span class="badge badge-pill badge-success">Active</span>' : '<span class="badge badge-pill badge-danger">Closed</span>' !!}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{--rewards--}}
                    <div class="col-4">
                        <div class="card">
                            <div class="card-header">
                                <h3>
                                    Rewards
                                    <strong><h6>(Total ${{$total_referral_payment}})</h6></strong>
                                </h3>
                            </div>

                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr style="text-align: center">
                                            <th>Amount</th>
                                            <th>On Inviting</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reward_logs as $reward_log)
                                            <tr>
                                                <td>{{('$' . $reward_log->reward->amount) ?? ''}}</td>
                                                <td>{{($reward_log->reward->invited_user->profile->first_name) ?? '' . ' ' . ($reward_log->reward->invited_user->profile->last_name ?? '')}}</td>
                                                <td>{{\Carbon\Carbon::parse($reward_log->created_at)->format('M d, Y.')}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div id="confirmModal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header"  style="background-color: #343a40;
            color: #fff;">
                        <h2 class="modal-title">Confirmation</h2>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <h4 align="center" style="margin: 0;">Are you sure you want to delete this ?</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="ok_delete" name="ok_delete" class="btn btn-danger">Delete</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="activateModal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header"  style="background-color: #343a40;
            color: #fff;">
                        <h2 class="modal-title">Confirmation</h2>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <h4 align="center" style="margin: 0;">Are you sure?</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="ok_activate" name="ok_activate" class="btn btn-danger">Activate</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function () {

        })
    </script>


    @endsection
