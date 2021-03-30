@include('Admin.Layouts.head')
@include('Admin.Layouts.header')
@include('Admin.Layouts.sidenav')
            <div id="layoutSidenav_content">
                <main>
                <?php
                foreach($product as $c)
                {
                    $id = $c->id;
                }
                ?>
                    <div class="container-fluid">
                        <h1 class="mt-4">Update Product</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Dashboard/Update Product</li>
                        </ol>
                        <form role="form" action="/updateproduct/{{$id}}" enctype="multipart/form-data" method="post">
                        @csrf
                        <div class="form-group row">
                                <label class="col-xl-3">Category<i class="text-danger">*</i></label>
                            <div class="form-group">
                                <div class="col-xs-7">
                                    <select class="form-control" name="category_id">
                                    <?php
                                    foreach($category as $c)
                                    {
                                    ?>
                                        <option value="{{ $c->id }}">{{ $c->Cname }}</option>
                                    <?php
                                    }
                                    ?> 
                                     </select>
                                </div>
                            </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-xl-3">Sub category<i class="text-danger">*</i></label>
                            <div class="form-group">
                                <div class="col-xs-7">
                                <select class="form-control" name="subcategory_id">
                                <?php
                                foreach($subcategory as $s)
                                {
                                ?>
                                    <option value="{{ $s->id }}">{{ $s->SubCategory_name }}</option>
                                <?php
                                }
                                ?>
                                </select>
                                </div>
                            </div>
                            </div>
                            <?php
                            foreach($product as $p)
                            {
                            ?>
                            <div class="form-group row">
                                <label class="col-xl-3">Product Name<i class="text-danger">*</i></label>
                                <div class="form-group">
                                    <div class="col-xs-7">
                                        <input name="product_name" type="text" class="form-control" id="product_name" placeholder="" value="{{ $p->Product_name }}" required>
                                    </div>
                                 </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-xl-3">Product Brand<i class="text-danger">*</i></label>
                            <div class="form-group">
                                <div class="col-xs-7">
                                <select class="form-control" name="product_brand">
                                    <?php
                                    foreach($Brand as $b)
                                    {
                                    ?>
                                        <option value="{{ $b->Brand_name }}">{{ $b->Brand_name }}</option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                </div>
                            </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-xl-3">Product Price<i class="text-danger">*</i></label>
                            <div class="form-group">
                                <div class="col-xs-7">
                                     <input name="price" type="number" class="form-control" id="product_price" placeholder="₹" value="{{ $p->Price }}" required>
                                </div>
                            </div>
                            </div>

                            
                            <div class="form-group row">
                                <label class="col-xl-3">Product Description<i class="text-danger">*</i></label>
                            <div class="form-group">
                                <div class="col-xs-7">
                                     <textarea class="textarea" placeholder="Place some text here" name= "description" style="width: 300%; height: 150px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{ $p->Description }}</textarea>
                                </div>
                            </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-xl-3">Color<i class="text-danger">*</i></label>
                            <div class="form-group">
                                <div class="col-xs-7">
                                    <select class="form-control" name="color">
                                    <?php
                                    foreach($Color as $c)
                                    {
                                    ?>
                                        <option value="{{ $c->color }}">{{ $c->color }}</option>
                                    <?php
                                    }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-xl-3">Size<i class="text-danger">*</i></label>
                            <div class="form-group">
                                <div class="col-xs-7">
                                     <select class="form-control" name="size">
                                        <option value="S">S</option>
                                        <option value="M">M</option>
                                        <option value="L">L</option>
                                        <option value="XL">XL</option>
                                        <option value="XXL">XXL</option>
                                        <option value="XXXL">XXXL</option>
                                     </select>
                                </div>
                            </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-xl-3">Product Stock<i class="text-danger">*</i></label>
                            <div class="form-group">
                                <div class="col-xs-7">
                                     <input name="stock" type="number" class="form-control" id="product_stock" placeholder="" value="{{ $p->Stock }}" required>
                                </div>
                            </div>
                            </div>
                     

                            <div class="form-group row">
                                <label class="col-xl-3">Product Image<i class="text-danger">*</i></label>
                            <div class="form-group">
                                <div class="col-xs-7">
                                     <input name="photo" type="file" >
                                </div>
                            </div>
                            </div>
                            <?php
                            }
                            ?>
                            <div class="text-center">
                                <div class="col-xl-8">
                                    <input type="submit" class="btn btn-lg btn-success" value="Update">
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
        <script src="{{asset('js/scripts.js')}}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="{{ asset('js/datatables-demo.js') }}"></script>
    </body>
</html>
