@include('Admin.Layouts.head')
@include('Admin.Layouts.header')
@include('Admin.Layouts.sidenav')
            <div id="layoutSidenav_content">
            <?php
            foreach($udata as $c)
            {
                $id = $c->id;
                $fname = $c->Firstname;
                $lname = $c->Lastname;
                $email = $c->email;
            }
            ?>
                <main>
                <div class="container-fluid">
                        <h1 class="mt-4">Reply Contact Person</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Dashboard/Reply Contact Person</li>
                        </ol>
                    <form role="form" action="/userreplymail/{{ $id }}" method="post">
                    @csrf
                        <div class="form-group row">
                            <label class="col-xl-3">User Name<i class="text-danger">*</i></label>
                            <div class="form-group">
                                <div class="col-xs-7">
                                    <input name="Uname" type="text" class="form-control" value="{{ $fname }} {{$lname}}" readonly>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-xl-3">User E-Mail<i class="text-danger">*</i></label>
                            <div class="form-group">
                                <div class="col-xs-7">
                                    <input name="Umail" type="text" class="form-control" value="{{ $email }}" readonly>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-xl-3">User Reply Message<i class="text-danger">*</i></label>
                            <div class="form-group">
                                <div class="col-xs-7">
                                    <textarea class="textarea" placeholder="Place some text here" name="Umessage" style="width: 300%; height: 150px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" required></textarea>
                                </div>
                            </div>
                        </div>
                            
                        <div class="text-center">
                            <div class="col-xl-8">
                                <input type="submit" class="btn btn-lg btn-success" name="Save" value="Send"/>
                                <input type="reset" class="btn btn-lg btn-danger" name="Reset"/>                                
                            </div>   
                        </div>
                    </form>
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
