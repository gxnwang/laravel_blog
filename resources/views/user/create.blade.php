@extends('layouts.default')

@section('content')
    <div class="card">
        <div class="card-header">
            用户注册
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="">昵称</label>
                <input type="text" name="email" class="form-control" >

            </div>

        </div>
        <div class="card-footer text-muted">
            <button type="submit" class="btn btn-success">注册</button>
        </div>
    </div>

@endsection

