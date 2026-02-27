 <!-- Footer -->
 <footer class="footer">
     <div class="container">
         <ul class="nav nav-pills nav-justified">
             <li class="nav-item">
                 <a class="nav-link {{ request()->routeIs('frontend.home') ? 'active' : '' }}" href="{{ route('frontend.home') }}">
                     <span>
                         <i class="nav-icon bi bi-house"></i>
                         <span class="nav-text">Home</span>
                     </span>
                 </a>
             </li>
             <li class="nav-item">
                 <a class="nav-link" href="">
                     <span>
                         <i class="nav-icon bi bi-wallet2"></i>
                         <span class="nav-text">Recharge</span>
                     </span>
                 </a>
             </li>
             <li class="nav-item centerbutton">
                 <a href="" class="nav-link" id="centermenubtn">
                     <span class="theme-linear-gradient">
                         <i class="bi bi-play-circle-fill size-22"></i>
                     </span>
                 </a>
             </li>
             <li class="nav-item">
                 <a class="nav-link" href="">
                     <span>
                         <i class="nav-icon bi bi-bag"></i>
                         <span class="nav-text">Orders</span>
                     </span>
                 </a>
             </li>
             <li class="nav-item">
                 <a class="nav-link" href="">
                     <span>
                         <i class="nav-icon bi bi-wallet2"></i>
                         <span class="nav-text">Wallet</span>
                     </span>
                 </a>
             </li>
         </ul>
     </div>
 </footer>
 <!-- Footer ends-->
