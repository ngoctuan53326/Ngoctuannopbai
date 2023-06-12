@extends('admin.category.layouts.master')
    @section('content1')
    <div>
        @if(session('success'))
            <br>
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <br>
        <div>
            <a href="{{route('admin.getBannerAdd')}}">
                <button type="button" class="btn btn-outline-primary">Thêm mới</button>
            </a>
        </div>
        <br>
        <table class="table">
            <thead>
            <tr>
                <th>STT</th>
                <th>Link</th>
                <th>Image</th>
                <th colspan='2'> <center>Cài đặt</center></th>
            </tr>
            </thead>
            <tbody>
                @isset($banners)
                    @php
                        $i=1;
                    @endphp
                    @foreach($banners as $banner)
                        <tr>
                            <td>{{$i}}</td>
                            <td>{{$banner->Link}}</td>
                            <td><img src="{{ asset('/source/image/slide/'.$banner->image) }}" alt="" width="100px" height="50px"/></td>               
                            <td>
                                <a href="{{route('admin.getBannerEdit',[$banner->id])}}"><button class="btn btn-outline-primary"><i class="fas fa-cog  fa-lg"></i></button></a>
                            </td>
                            <td>
                                <form action="{{route('admin.getBannerDelete',[$banner->id])}}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button class="btn btn-outline-primary" type="submit"><i class="fa fa-trash-alt"></i></button>
                                </form>
                            </td>
                        </tr>
                        @php
                            $i=$i+1;
                        @endphp
                    @endforeach
                @endisset
            </tbody>
        </table>
    </div>
    @endsection
