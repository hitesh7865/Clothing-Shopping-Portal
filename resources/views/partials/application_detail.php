<script id="application-detail-template" type="text/x-handlebars-template">
    <div class="app-detail row">
        <div class="app-detail__content col-md-3">
            <!-- <div class="app-detail__dt">Date : 12/12/2019</div> -->
            <div class="app-detail__header">Questionaires : </div>
            <div class="app-detail__questions">
                {{#each questions}}
                <div class="app-detail__question-wrapper">
                    <div class="app-detail__question"> {{question}} </div>
                    {{#each answer}}
                     <label class="status status_options">{{this}}</label>
                    {{/each}}
                </div>
                {{/each}}
            </div>
        </div>
        <div class="app-detail__content col-md-9">
            <!-- <div class="app-detail__dt">Date : 12/12/2019</div> -->
            <div class="app-detail__header">Email : </div>
            <div class="app-detail__questions">
              {{{text}}}
            </div>
        </div>
    </div>
</script>

<script id="application-content-template" type="text/x-handlebars-template">
    <div class="applications-listing">
        {{subject}}
        {{#if attachments.length}}
        <div class="applications-listing__attachments-head">
          Attachments
        </div>
        <div>
            {{#each attachments}}
            <div class="applications-listing__attachments-item">
                 <i class="glyphicon glyphicon-paperclip"></i>&nbsp;<a href="/api/applications/{{../id}}/{{file_unique_name}}" target="_blank" >{{file_name}}</a>
            </div>
            {{/each}}
        </div>
        {{/if}}
    </div>
</script>

<script id="application-user-template" type="text/x-handlebars-template">
  <div class="app-detail">
      <div class="app-detail__content">
          <div class="app-detail__email small">{{from_email}}</div>
          <div class="app-detail__name small">{{name}}</div>
      </div>
  </div>
</script>