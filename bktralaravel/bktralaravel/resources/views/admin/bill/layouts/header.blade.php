<style>
    .bg-pink {
        background-color: #FFC0CB; /* Replace with your desired shade of pink */
    }
</style>

<nav class="navbar navbar-expand-lg navbar-light bg-pink">
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('admin.getCateList') }}"><h4>Quản lí sản phẩm |</h4></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('admin.getUserList') }}"><h4>Quản lí user |</h4></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('admin.listBillAll') }}"><h4>Quản lí bill |</h4></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('admin.getBannerList') }}"><h4>Quản lí banner |</h4></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('admin.getTypeList') }}"><h4>Quản lí Type-Products |</h4></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('admin.listLhAll') }}"><h4>Quản lí Contacts </h4></a>
            </li>
        </ul>
        <form class="form-inline my-2 my-lg-0" method="get" action="{{route('admin.postLogin')}}">
            @csrf
            <button class="btn btn-info" type="submit"><i class="fas fa-sign-out-alt"></i> Đăng Xuất</button>
        </form>
    </div>
</nav>