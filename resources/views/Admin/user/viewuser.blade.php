@include('Admin.Layouts.head')
@include('Admin.Layouts.header')
@include('Admin.Layouts.sidenav')
            <div id="layoutSidenav_content">
                <main>
                <div class="container-fluid">
                        <h1 class="mt-4">View User</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Dashboard/View User</li>
                        </ol>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table mr-1"></i>
                                DataTable Example
                        </div>
                        
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Name</th>
                                            <th>UserName</th>
                                            <th>Email</th>
                                            <th>Address</th>
                                            <th>Mobile</th>
                                            <th>MailBox</th>
                                            <th>Block/Unblock</th>								
                                        </tr>
                                    </thead>

                                    <tfoot>
                                        <tr class="text-center">
                                            <th>Name</th>
                                            <th>UserName</th>
                                            <th>Email</th>
                                            <th>Address</th>
                                            <th>Mobile</th>
                                            <th>MailBox</th>
                                            <th>Block/Unblock</th>
                                        </tr>
                                    </tfoot>
                                
                                    <tbody> 
                                        @foreach($user as $u)
                                        <tr class="text-bottom" >
                                            <td>{{ $u['Firstname'] }} {{ $u['Lastname']}}</td>
                                            <td>{{ $u['username'] }}</td>
                                            <td>{{ $u['email'] }}</td>
                                            <td>{{ $u['Address'] }}</td>
                                            <td>{{ $u['mobile'] }}</td>
                                            <td><a href="/user_mail/ {{ $u['id'] }}" class="btn btn-lg btn-success">MailBox</a></td>
                                            <td><a href="/user_block/ {{ $u['id'] }}" class="btn btn-lg btn-danger">Block/Unblock</a></td>
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
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/datatables-demo.js"></script>
    </body>
</html>