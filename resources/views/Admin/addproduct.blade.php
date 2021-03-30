<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Add Product</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand" href="/AdminPanel">Admin Panel</a>
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
                        <a class="dropdown-item" href="#">Settings</a>
                        <a class="dropdown-item" href="#">Activity Log</a>
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
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h1 class="mt-4">Add Product</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Dashboard/Add Product</li>
                        </ol>
                        <form role="form" action="/addproduct" enctype="multipart/form-data" method="post">
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
                                foreach($Subcategory as $s)
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

                            <div class="form-group row">
                                <label class="col-xl-3">Product Name<i class="text-danger">*</i></label>
                                <div class="form-group">
                                    <div class="col-xs-7">
                                        <input name="product_name" type="text" class="form-control" id="product_name" placeholder="" value="" required>
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
                                     <input name="price" type="number" class="form-control" id="product_price" placeholder="₹" min='1' value="">
                                </div>
                            </div>
                            </div>

                            
                            <div class="form-group row">
                                <label class="col-xl-3">Product Description<i class="text-danger">*</i></label>
                            <div class="form-group">
                                <div class="col-xs-7">
                                     <textarea class="textarea" placeholder="Place some text here" name= "description" style="width: 300%; height: 150px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                                </div>
                            </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-xl-3">Color<i class="text-danger">*</i></label>
                            <div class="form-group">
                                <div class="col-xs-7">
                                    <select class="form-control" id='color' name="color">
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
                                     <input name="stock" min="1" type="number" class="form-control" id="product_stock" placeholder="" value="">
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
 
                            <div class="text-center">
                                <div class="col-xl-8">
                                    <input type="submit" id="add" class="btn btn-lg btn-success" value="Save">
                                    <input type="reset" class="btn btn-lg btn-danger" name="Reset"/>                                
                                </div>   
                            </div>
                        </form>
                        <div class="text-right"> 
                            <a href="/view-product"><input type="button" style="background-color:purple" class="btn btn-lg btn-success" name="View" value="View Categories"/></a>
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
        
        <script
            src="https://code.jquery.com/jquery-3.5.1.min.js"
            integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
            crossorigin="anonymous">
        </script>
        <script type="text/javacript">
        $("#file").fileinput(
            {
                theme:'fa',
                uploadUrl="",
                allowedFileExtentions:['jpg','png','gif'],
                overWriteIntial:'false',
                maxFileSize:4000,
                maxFileNum:1,
                slugCallback:function(inm){
                    return inm.replace('(','_').replace(')','_');
                }
            }
        );
        </script> 
        <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.js"></script>
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
