@include('Admin.Layouts.head')
@include('Admin.Layouts.header')
@include('Admin.Layouts.sidenav')
    <?php
    foreach($subcategory as $c)
    {
        $id = $c->id;
        $cnm = $c->SubCategory_name;
        $d = $c->Discription;
        $ctype = $c->Category_id;
    }
    ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                        <h1 class="mt-4">Update Sub-Category</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Dashboard/Update Sub-Category</li>
                        </ol>
                
                    <form role="form" action="/updatesub/{{$id}}" method="post">
                    @csrf
                        <div class="form-group row">
                            <label class="col-xl-3">Sub-Category Name<i class="text-danger">*</i></label>
                            <div class="form-group">
                                <div class="col-xs-7">
                                    <input name="SubCategory_name" value="{{ $cnm }}" type="text" class="form-control" id="subcategory_name" placeholder="Male/Female" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-xl-3">Sub-Category Description<i class="text-danger">*</i></label>
                            <div class="form-group">
                                <div class="col-xs-7">
                                    <textarea class="textarea" placeholder="Place some text here" name="Discription" style="width: 300%; height: 150px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" required>{{ $d }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-xl-3">Category Name<i class="text-danger">*</i></label>
                            <div class="form-group">
                                <div class="col-xs-7">
                                <select class="form-control" name="Category_id">
                                <?php
                                    foreach($category as $ci)
                                    {
                                ?>   
                                        <option value="{{ $ci->id }}">{{ $ci->Cname }}</option>
                                <?php
                                }
                                ?>
                                     </select>   
                                </div>
                            </div>
                        </div>
                            
                        <div class="text-center">
                            <div class="col-xl-8">
                                <input type="submit" class="btn btn-lg btn-success" name="Save" value="Update"/>
                                <input type="reset" class="btn btn-lg btn-danger" name="Reset"/>                                
                            </div>   
                        </div>
                    </form>
                </div>
            </main>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="{{asset('js/scripts.js')}}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="{{ asset('js/datatables-demo.js') }}"></script>        
    </body>
</html>