@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Laboratory Dashboard Overview</h4>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <!-- Summary Stats -->
            <div class="row">
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box">
                        <div class="box-body">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary-light rounded-circle h-60 w-60 d-flex align-items-center justify-content-center">
                                    <i class="fa fa-users text-primary fs-24"></i>
                                </div>
                                <div class="ms-15">
                                    <h3 class="mb-0 fw-bold">{{ $totalPatients }}</h3>
                                    <p class="text-fade mb-0">Total Patients</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box">
                        <div class="box-body">
                            <div class="d-flex align-items-center">
                                <div class="bg-success-light rounded-circle h-60 w-60 d-flex align-items-center justify-content-center">
                                    <i class="fa fa-check-circle text-success fs-24"></i>
                                </div>
                                <div class="ms-15">
                                    <h3 class="mb-0 fw-bold">{{ $totalCompleted }}</h3>
                                    <p class="text-fade mb-0">Completed Reports</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box">
                        <div class="box-body">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning-light rounded-circle h-60 w-60 d-flex align-items-center justify-content-center">
                                    <i class="fa fa-calendar-check-o text-warning fs-24"></i>
                                </div>
                                <div class="ms-15">
                                    <h3 class="mb-0 fw-bold">{{ $totalAppointments }}</h3>
                                    <p class="text-fade mb-0">Total Bookings</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box">
                        <div class="box-body">
                            <div class="d-flex align-items-center">
                                <div class="bg-danger-light rounded-circle h-60 w-60 d-flex align-items-center justify-content-center">
                                    <i class="fa fa-clock-o text-danger fs-24"></i>
                                </div>
                                <div class="ms-15">
                                    <h3 class="mb-0 fw-bold">{{ $pendingReports }}</h3>
                                    <p class="text-fade mb-0">Pending Reports</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12">
                    <div class="box">
                        <div class="box-body text-center py-50">
                            <h2 class="text-primary mb-10">Welcome to Laboratory Management System</h2>
                            <p class="text-fade fs-16">Use the sidebar to manage patients, appointments, and generate reports.</p>
                            <div class="mt-20">
                                <a href="{{ route('patients') }}" class="btn btn-primary px-30">Manage Patients</a>
                                <a href="{{ route('reports') }}" class="btn btn-info px-30 ms-10">View Reports</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
