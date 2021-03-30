@include('Admin.Layouts.head')
@include('Admin.Layouts.header')
@include('Admin.Layouts.sidenav')
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h1 class="mt-4">Dashboard</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body">Total Customers</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                    <b>{{ $usercount }}</b>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-warning text-white mb-4">
                                    <div class="card-body">Total Products</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                    <b>{{ $productcount }}</b>   
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-body">Total Sale</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                    <b>₹{{ $totsale }}</b>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-danger text-white mb-4">
                                    <div class="card-body">Total Orders</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                    <b>{{ $ordercount }}</b>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table mr-1"></i>
                                Orders
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="demo" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Invoice No</th>
                                                <th>Customer Id</th>
                                                <th>Total Amount Of Orders</th>
                                                <th>View Order</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Invoice No</th>
                                                <th>Customer Id</th>
                                                <th>Total Amount Of Orders</th>
                                                <th>View Order</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                        @foreach($orderdata as $o)
                                            <tr>
                                                <td>{{ $o->orderid }}</td>
                                                <td>{{ $o->user_id }}</td>
                                                <td>₹{{ $o->total }}</td>
                                                <td><a href="/orderdetails/{{ $o->orderid }}"><b>View Order Details</b></a></td>
                                            </tr>
                                        @endforeach                                               
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2020</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
 @include('Admin.Layouts.script')
</html>