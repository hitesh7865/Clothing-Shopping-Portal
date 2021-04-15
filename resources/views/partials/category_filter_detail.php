<script id="category-filter-detail-template" type="text/x-handlebars-template">
    <div class="filter-detail">
        <div class="filter-detail__wrapper">
            <!-- <div class="app-detail__dt">Date : 12/12/2019</div> -->
            <div class="filter-detail__item-wrapper">
              <div class="filter-detail__header">Subject Filter : </div>
              <div class="filter-detail__content">{{subject_filter}}</div>
            </div>
            <div class="filter-detail__item-wrapper">
              <div class="filter-detail__header">Email Filter &nbsp;&nbsp;&nbsp;: </div>
              <div class="filter-detail__content"><a href= "mailto:{{email_filter}}">{{email_filter}}</a></div>
            </div>
        </div>
    </div>
</script>