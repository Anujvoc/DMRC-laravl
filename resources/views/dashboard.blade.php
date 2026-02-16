@extends('admin.partials.app')

@section('title', 'Dashboard')

@section('content')
<div class="app-wrapper">
    <div class="app-page-head d-flex flex-wrap gap-3 align-items-center justify-content-between">
        <div class="clearfix">
            <h1 class="app-page-title">Dashboard</h1>
            <span>Welcome to DMRC Learning Management System</span>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card border-primary">
                <div class="card-body">
                    <h4 class="card-title">Welcome to Your Dashboard</h4>
                    <p class="card-text">
                        You have successfully logged into the DMRC Learning Management System. 
                        Use the navigation menu to access different modules and features.
                    </p>
                    
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="fas fa-book fa-3x text-primary mb-3"></i>
                                    <h5>Modules</h5>
                                    <p class="card-text">Manage training modules</p>
                                    <a href="{{ route('admin.module.master') }}" class="btn btn-primary">Go to Modules</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="fas fa-users fa-3x text-success mb-3"></i>
                                    <h5>Batches</h5>
                                    <p class="card-text">Manage training batches</p>
                                    <a href="{{ route('admin.training_batch.index') }}" class="btn btn-success">Go to Batches</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="fas fa-chart-bar fa-3x text-warning mb-3"></i>
                                    <h5>Analytics</h5>
                                    <p class="card-text">View system analytics</p>
                                    <a href="#" class="btn btn-warning">View Analytics</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('logout') }}" class="btn btn-danger">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
