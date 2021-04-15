<a class="dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
       Hi, {{auth()->user()->fullname}} 
      <span class="caret"></span>
</a>



<ul class="dropdown-menu">
    <li>
        <a href="/profile" class="dropmenu-item">
          <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
          <span class="dropmenu-item-label">Profile</span>
          {{-- <span class="dropmenu-item-content">Ctrl+N</span> --}}
          </a>
    </li>
    <div class="dropdown-divider divider"></div>
    <li><a href="/logout" class="dropmenu-item">
      <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>
                  <span class="dropmenu-item-label">Logout</span>
                  {{-- <span class="dropmenu-item-content">Ctrl+E</span> --}}
                  </a> {{-- <i class="glyphicon glyphicon-cog"></i> --}}
    </li>
</ul>