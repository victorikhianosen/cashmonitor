   <ul class="menu" data-fc-type="accordion">
       <li class="menu-title">Menu</li>

       <li class="menu-item">
           <a href="{{ route('admin.dashboard') }}" class="menu-link">
               <span class="menu-icon"><i class="mgc_home_3_line"></i></span>
               <span class="menu-text"> Dashboard </span>
           </a>
       </li>


       @if (Auth::user()->role === 'admin')
           <li class="menu-item">
               <a href="{{ route('admin.user') }}" data-fc-type="collapse" class="menu-link">
                   <span class="menu-icon"><i class="mgc_user_3_line"></i></span>
                   <span class="menu-text">All Users </span>
               </a>

           </li>


           <li class="menu-item">
               <a href="{{ route('admin.company') }}" data-fc-type="collapse" class="menu-link">
                   <span class="menu-icon"><i class="mgc_user_3_line"></i></span>
                   <span class="menu-text">Company </span>
               </a>

           </li>



           <li class="menu-item">
               <a href="{{ route('admin.branch.index') }}" data-fc-type="collapse" class="menu-link">
                   <span class="menu-icon"><i class="mgc_user_3_line"></i></span>
                   <span class="menu-text">Branch </span>
               </a>

           </li>
       @endif



   </ul>
