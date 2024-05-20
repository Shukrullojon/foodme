@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-2">
                                <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1">
                                    <i class="fas fa-language"></i>
                                </span>
                                    <div class="info-box-content">
                                    <span class="info-box-text">
                                        <a href="">Langs</a>
                                    </span>
                                        <span class="info-box-number">1</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-2">
                                <div class="info-box">
                                <span class="info-box-icon bg-gradient-gray-dark elevation-1">
                                    <i class="fas fa-circle"></i>
                                </span>
                                    <div class="info-box-content">
                                    <span class="info-box-text">
                                        <a href="">Circle</a>
                                    </span>
                                        <span class="info-box-number">1</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-2">
                                <div class="info-box">
                                <span class="info-box-icon bg-gradient-blue elevation-1">
                                    <i class="fas fa-sim-card"></i>
                                </span>
                                    <div class="info-box-content">
                                    <span class="info-box-text">
                                        <a href="">Card</a>
                                    </span>
                                        <span class="info-box-number">1</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-2">
                                <div class="info-box">
                                <span class="info-box-icon bg-black elevation-1">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                                    <div class="info-box-content">
                                    <span class="info-box-text">
                                        <a href="">Circle Card</a>
                                    </span>
                                        <span class="info-box-number">1</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-2">
                                <div class="info-box">
                                <span class="info-box-icon bg-gradient-light elevation-1">
                                    <i class="fas fa-ticket-alt"></i>
                                </span>
                                    <div class="info-box-content">
                                    <span class="info-box-text">
                                        <a href="">Bilet</a>
                                    </span>
                                        <span class="info-box-number">1</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-2">
                                <div class="info-box">
                                <span class="info-box-icon bg-gradient-light elevation-1">

                                </span>

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <h3>15</h3>
                                        <p>Today Games</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-gamepad"></i>
                                    </div>
                                    <a href="" class="small-box-footer">
                                        More info <i class="fas fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-black">
                                    <div class="inner">
                                        <h3>15</h3>
                                        <p>Active Game</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-users"></i>
                                    </div>
                                    <a href="" class="small-box-footer">
                                        More info <i class="fas fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-primary">
                                    <div class="inner">
                                        <h3>15</h3>
                                        <p>Bot Users</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-users"></i>
                                    </div>
                                    <a href="" class="small-box-footer">
                                        More info <i class="fas fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-yellow">
                                    <div class="inner">
                                        <h3>15</h3>
                                        <p>Bot Users</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-users"></i>
                                    </div>
                                    <a href="" class="small-box-footer">
                                        More info <i class="fas fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
