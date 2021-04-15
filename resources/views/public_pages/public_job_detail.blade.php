<html>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

<title>ITS Job Detail</title>

<style>
    html,
    body {
        overflow-x: hidden;
    }
</style>

<body>
    <nav class="navbar navbar-dark bg-primary">
        <a class="navbar-brand" href="/">
            <h3>YouRHired</h3>
            <!-- <img src="/docs/4.1/assets/brand/bootstrap-solid.svg" width="30" height="30" alt=""> -->
        </a>
    </nav>
    <div class="container">

        <dl class="row " style="margin: 15px 0px;">
            <dt class="col-sm-2">
                <p>Job Name:</p>
            </dt>
            <dd class="col-sm-4">
                <p>{{$job['title']}}</p>
            </dd>

            <dt class="col-sm-2">
                <p>Location:</p>
            </dt>
            <dd class="col-sm-4">
                <p>{{$job['job_locations'][0]['country']}}</p>
            </dd>

            <dt class="col-sm-2">
                <p>Job Category:</p>
            </dt>
            <dd class="col-sm-4">
                <p>{{$job['job_category']['name']}}</p>
            </dd>

            <!-- <dt class="col-sm-2">Job Description</dt>
            <dd class="col-sm-10">
                <p>-</p>
            </dd> -->

            <dt class="col-sm-2">
                <p>Job Tags:</p>
            </dt>
            <dd class="col-sm-4">@if(count($job['job_tags']) > 0)@foreach($job['job_tags'] as $tag) <span class="badge badge-primary">{{$tag['tag']['name']}} </span>@endforeach @endif</dd>


            <!-- 
            <dt class="col-sm-3">Nesting</dt>
            <dd class="col-sm-9">
                <dl class="row">
                    <dt class="col-sm-4">Nested definition list</dt>
                    <dd class="col-sm-8">Aenean posuere, tortor sed cursus feugiat, nunc augue blandit nunc.</dd>
                </dl>
            </dd> -->
        </dl>
        <div class="row table-responsive">
            <h3>Applicants</h3>
            <table class="table table-hover ">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <!-- <th scope="col">ITS ID</th> -->
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($job['applications'] as $key => $application)
                    <tr>
                        <th scope="row">{{$key + 1}}</th>
                        <td>{{$application['name']}} ({{$application['extra_field']}})</td>
                        <td>{{$application['from_email']}}</td>
                        <td><button type="button" data-answers="{{json_encode($application)}}" class="btn btn-primary btn-popup" data-toggle="modal" data-target="#exampleModalCenter">View Answer</button></td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Applicant Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('.btn-popup').click(function(e) {
                var answers = [];
                answers = $(this).data('answers');
                var html = '<div>'; 
                html += '<p>Name : '+answers.name+'</p>';
                html += '<p>Subject : '+answers.subject+'</p>';
                html += '<p>From Email : '+answers.from_email+'</p>';
                html += '<p>To Email : '+answers.to_email+'</p>';
                html += '<p>Type : '+answers.type+'</p>';
                html += '<p>Cat Id : '+answers.cat_id+'</p>';
                html += '<p>Org Id : '+answers.org_id+'</p>';
                html += '<p>Text : '+answers.text+'</p>';
                html += '<p>Rating : '+answers.rating+'</p>';
                html += '<p>References : '+answers.references+'</p>';
                html += '</div>';
                // for(var i=0;i<answers.length;i++) {
                //     html += '<div>';
                //     html += '<p>Q1. '+answers[i].question.question + '</p>';
                //     html += '<p>Answer: '+answers[i].answer + '</p>';
                //     html += '</div>';
                // }
                $('.modal-body').html(html);
            });
        })
    </script>
</body>

</html>