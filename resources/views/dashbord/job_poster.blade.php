@extends('layouts.default')

@section('title', 'JOB POSTER Dashboard')
@section('site_description', '')
@section('site_keywords', '')
@section('site_image', '/images/bg.jpg')
@section('page_name', 'dashboard')
@section('content')


<section class="page__wrapper-content ">
    <div class="container dashboard">
        <div class=" col-md-8 g-no-padding">
            <div class="readytohire">
                <div class="readytohire__header ">
                    <span><i class="glyphicon glyphicon-list"></i>Candidates ready for Hiring<span>
                </div>
              <div class="readytohire__content">
                @if(count($screened_candidates) ==0 )
                  <div class="readytohire__item">
                      <div class="readytohire__item-job">
                      Waiting for the awesome!
                      <i class="glyphicon glyphicon-bullhorn"></i>
                      </div>
                  </div>
                @endif
                @foreach($screened_candidates as $candidate)
                  <div class="readytohire__item">
                      <div class="readytohire__item-job">{{$candidate['title']}}</div>
                      <a href="{{route('candidates') . '?title=' .  $candidate['cat_id'] . '&status=' . App\Enums\ApplicationStatus::SCREENED}}" class="readytohire__item-job-candidates">{{$candidate['applicant_count']}} candidates screened</a>
                  </div>
                @endforeach
              </div>
            </div>
            <div class="top-candidates">
                <div class="top-candidates__header ">
                    <span><i class="glyphicon glyphicon-thumbs-up"></i>Top Candidates<span>
                </div>
              <div class="top-candidates__content">
                  @if(count($top_candidates) ==0 )
                    <div  class="top-candidates__item g-cleared">
                      <div class="top-candidates__item-job">It's not the end, wait for the awesome :)</div>
                    </div>
                  @endif
                  @foreach($top_candidates as $candidate)
                  <a href="{{route('candidates') . '?id=' .  $candidate['app_id']}}" class="top-candidates__item g-cleared">
                      <div class="top-candidates__item-user col-md-6 text-left g-no-padding">{{$candidate['from_email']}}
                      <div class="top-candidates__item-job">for {{$candidate['title']}}</div>
                      </div>
                      
                      <div class="top-candidates__stars col-md-6 text-right g-no-padding">
                        @for($i=1 ; $i <= $candidate['rating'] ; $i++)
                          <i class="glyphicon glyphicon-star"></i>
                        @endfor
                      </div>
                  </a>
                  @endforeach
              </div>
            </div>
        </div>
        <div class="col-md-4">
          <div class="jobs">
            <div class="jobs__header">
              <span><i class="glyphicon glyphicon-tags"></i>Jobs<span>
            </div>
            <div class="jobs__content">
              <div class="jobs__item g-cleared">
                <div class="jobs__item-name col-md-10 g-no-padding">Open</div>
                <div class="jobs__item-count col-md-2 g-no-padding">{{$jobs['open'] == 0 ? "--" : $jobs['open']}}</div>
              </div>
              <div class="jobs__item g-cleared">
                <div class="jobs__item-name col-md-10 g-no-padding">Closed</div>
                <div class="jobs__item-count col-md-2 g-no-padding">{{$jobs['closed'] == 0 ? "--" : $jobs['closed']}}</div>
              </div>
            </div>
          </div>
          <div class="candidates">
            <div class="candidates__header">
              <span><i class="glyphicon glyphicon-list-alt"></i>Candidates<span>
            </div>
            <div class="candidates__content">
              @if(count($candidates) ==0)
                <div href="" class="candidates__item g-cleared">
                  <div class="candidates__item-name col-md-12 g-no-padding">No pending candidates</div>
                </div>
              @endif
              @foreach($candidates as $candidate)
              <a href="{{$candidate['route']}}" class="candidates__item g-cleared">
                <div class="candidates__item-name col-md-10 g-no-padding">{{$candidate['title']}}</div>
                <div class="candidates__item-count col-md-2 g-no-padding">{{$candidate['applicant_count']}}</div>
              </a>
              @endforeach
            </div>
          </div>
        </div>
    </div>
</section>

  
@endsection