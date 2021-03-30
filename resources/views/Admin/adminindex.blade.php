<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Dashboard - Admin</title>
        
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js">
        <link href="css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand" href="admin_index">Admin Panel</a>
            <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
                <div class="input-group">
                    <input class="form-control" type="text" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2" />
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
            <!-- Navbar-->
            <ul class="navbar-nav ml-auto ml-md-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="logout">Logout</a>
                    </div>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Core</div>
                            <a class="nav-link" href="/AdminPanel">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            <div class="sb-sidenav-menu-heading">Interface</div>
                            <a class="nav-link" href="/Add-Category">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Manage Category
                            </a>
                            <a class="nav-link" href="/addsubcategory">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Manage Sub-Category
                            </a>
                            <a class="nav-link" href="/addproduct">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Manage Product
                            </a>     
                            <a class="nav-link" href="/addbrand">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Manage Brand
                            </a>      
                            <a class="nav-link" href="/addcolor">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Manage Color
                            </a>                 
                            <a class="nav-link" href="/addcolor">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Manage User
                            </a> 
                            <a class="nav-link" href="/addcolor">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Contact Us Details
                            </a>            
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        Admin
                    </div>
                </nav>
            </div>
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
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="{{ asset('js/js/scripts.js')}}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="{{ asset('js/chart-area-demo.js')}}"></script>
        <script src="{{ asset('js/chart-bar-demo.js')}}"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="{{ asset('js/datatables-demo.js')}}"></script>
        <script src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.flash.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.print.min.js"></script>
       <script> $(document).ready( function () {
            $('#demo').DataTable({
                dom: 'Bfrtip',
                buttons: [
        'copy', 'excel', 'pdf'
    ]});
        } );
      </script>
    </body>
</html>