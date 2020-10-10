<div class="app-sidebar sidebar-shadow">
    <div class="app-header__logo">
        <div class="logo-src"></div>
        <div class="header__pane ml-auto">
            <div>
                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
    <div class="app-header__mobile-menu">
        <div>
            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                </span>
            </button>
        </div>
    </div>
    <div class="app-header__menu">
        <span>
            <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                <span class="btn-icon-wrapper">
                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                </span>
            </button>
        </span>
    </div>    <div class="scrollbar-sidebar">
        <div class="app-sidebar__inner">
            <ul class="vertical-nav-menu">
                <li class="app-sidebar__heading"><br></li>
                <li class="app-sidebar__heading"></li>
                <li class="app-sidebar__heading">My Account</li>
                <!-- <li>
                    <a href="#"><i class="metismenu-icon pe-7s-home"></i>Home</a>
                </li> -->
                <li>
                    <a href="{{route('u_home')}}"><i class="metismenu-icon pe-7s-rocket"></i>Pay Bills</a>
                </li>
                <li>
                    <a href="{{route('trxns')}}"><i class="metismenu-icon pe-7s-graph3"></i>Transactions</a>
                </li>
                <li>
                    <a href="{{route('pans')}}"><i class="metismenu-icon pe-7s-wallet"></i>Payment options</a>
                </li>
                <li>
                    <a href="{{route('u_profile')}}"><i class="metismenu-icon pe-7s-user-female"></i>Profile</a>
                </li>
            </ul>
        </div>
    </div>
</div>